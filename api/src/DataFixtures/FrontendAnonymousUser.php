<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class FrontendAnonymousUser extends Fixture implements FixtureInterface, FixtureGroupInterface
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('frontend_anonymous');
        $user->setDate(new DateTimeImmutable('now'));
        $user->setRoles('ROLE_ANON');
        $user->setPassword($this->hasher->hashPassword($user, '12345678'));

        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['FakeDataGroup'];
    }
}
