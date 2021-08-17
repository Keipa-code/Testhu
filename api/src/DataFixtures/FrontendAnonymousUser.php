<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontendAnonymousUser extends Fixture implements FixtureInterface
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('frontend_anonymous');
        $user->setDate(new \DateTimeImmutable('now'));
        $user->setRoles('ROLE_ANON');
        $user->setPassword($this->hasher->hashPassword($user, '12345678'));

        $manager->persist($user);

        $manager->flush();
    }
}
