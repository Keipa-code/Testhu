<?php

namespace App\Security;

use App\Entity\User;
use App\Service\TemplatedEmailFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    CONST VERIFY_URL = 'app_verify_email';

    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct
    (
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        EntityManagerInterface $manager
    )
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(UserInterface $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            self::VERIFY_URL,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $email = TemplatedEmailFactory::create($user->getEmail());

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     * @param User $user
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        if ($user->getNewEmail()) {
            $user->setEmail($user->getNewEmail());
        }
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
