<?php

declare(strict_types=1);


namespace App\Service;


use http\Exception\RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Webmozart\Assert\Assert;

final class PasswordHasher
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct()
    {
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);
        $this->passwordHasher = $factory->getPasswordHasher('common');
    }

    public function hash(string $password): string
    {
        Assert::notEmpty($password);
        $hash = $this->passwordHasher->hash($password);
        if ($hash === null) {
            throw new RuntimeException('Unable to generate password');
        }
        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}