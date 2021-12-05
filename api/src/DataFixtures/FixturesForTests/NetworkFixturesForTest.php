<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\Entity\Network;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class NetworkFixturesForTest extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $network = new Network();
        $network->setName('mail.ru');
        $network->setIdentity('identity identification');

        $this->addReference('network', $network);

        $manager->persist($network);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['TestsGroup'];
    }
}
