<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

final class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(
        EmailVerifier $emailVerifier
    ) {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->json(
                ['Error' => 'Не найден id'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json',
                ]]
            );
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->json(
                ['Error' => 'Не найден пользователь'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json',
                ]]
            );
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            return $this->json(
                ['Error' => 'Ссылка не работает'],
                404,
                ['headers' => [
                    'Content-Type' => 'application/json',
                ]]
            );
        }

        return $this->json(
            ['Success' => 'Почта успешно подтверждена'],
            200,
            ['headers' => [
                'Content-Type' => 'application/json',
            ]]
        );
    }
}
