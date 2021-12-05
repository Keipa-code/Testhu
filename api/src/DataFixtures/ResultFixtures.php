<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResultFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('ResultUser');
        $user->setPassword($this->hasher->hashPassword($user, '12345678'));

        $result = new Result();
        $result->setLink('https://result.com');
        $result->setCorrectAnswersCount(40);
        $result->setDate(new DateTimeImmutable('2021-07-20 07:10:47'));
        $result->setTestResults([
            '1' => false,
            '2' => true,
            '3' => true
        ]);

        $user->addResult($result);
        $manager->persist($user);
        $manager->persist($result);

        $manager->flush();
    }
}
