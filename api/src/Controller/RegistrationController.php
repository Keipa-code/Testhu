<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use DateTimeImmutable;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{

    private VerifyEmailHelperInterface $helper;
    private EmailVerifier $emailVerifier;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        EmailVerifier $emailVerifier
    )
    {
        $this->helper = $helper;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if(null === $id) {
            return $this->json(
                ['Error' => 'Не найден id'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json'
                ]]);
        }

        $user = $userRepository->find($id);

        if(null === $user) {
            return $this->json(
                ['Error' => 'Не найден пользователь'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json'
                ]]);
        }

        try{
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e){
            return $this->json(
                ['Error' => 'Ссылка не работает'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json'
                ]]);
        }

        return $this->json(
            ['Success' => 'Почта успешно подтверждена'],
            200,
            ['headers' => [
                'Content-Type' => 'application/json'
            ]]);
    }
}
