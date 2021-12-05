<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\Entity\Network;
use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixturesForTest extends Fixture implements FixtureInterface, DependentFixtureInterface, FixtureGroupInterface
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

        /** @var Network $network */
        $network = $this->getReference('network');

        /** @var Result $result */
        $result = $this->getReference('result');

        /** @var Test $test */
        $test = $this->getReference('test');

        $user->addNetwork($network);
        $user->addResult($result);
        $user->addTest($test);

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ResultFixturesForTest::class,
            NetworkFixturesForTest::class,
            TestFixturesForTest::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['TestsGroup'];
    }
}
