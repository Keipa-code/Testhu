<?php

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $hasher,
        private EmailVerifier $emailVerifier
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = []): void
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->hasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        $data->setUsername(mb_strtolower($data->getUserIdentifier()));
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if ($data->getEmail()) {
            $this->emailVerifier->sendEmailConfirmation($data);
        }

        if ($data->getNewEmail() && $data->getNewEmail() !== $data->getEmail()) {
            $data->setEmail($data->getNewEmail());
            $this->emailVerifier->sendEmailConfirmation($data);
        }
    }

    public function remove($data, array $context = []): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
