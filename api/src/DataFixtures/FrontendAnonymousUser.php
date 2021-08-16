<?php

declare(strict_types=1);


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FrontendAnonymousUser extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('frontend_anonymous');
        $user->setDate(new \DateTimeImmutable('now'));
        $user->setPassword('12345678');
        $user->setRoles('ROLE_ANON');


        $manager->persist($user);

        $manager->flush();
    }
}
