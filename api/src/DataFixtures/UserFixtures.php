<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Network;
use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture implements FixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('myname');
        $user->setDate(new DateTimeImmutable('2021-07-20 04:10:47'));
        $user->setEmail('test@test.com');
        $user->setPasswordResetToken('4ed161b5-0d3c-4f06-8381-5f14678e1300');
        $user->setNewEmail('new-test@test.com');
        $user->setPassword($this->hasher->hashPassword($user, '12345678'));

        $network = new Network();
        $network->setName('mail.ru');

        $result = new Result();
        $result->setLink('https://result.com');


        $user->addNetwork($network);
        $user->addResult($result);

        $manager->persist($user);
        $manager->persist($network);
        $manager->persist($result);

        $manager->flush();
    }
}
