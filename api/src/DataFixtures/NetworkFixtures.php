<?php

namespace App\DataFixtures;

use App\Entity\Network;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NetworkFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('NetworkUser');

        $network = new Network();
        $network->setName('mail.ru');
        $network->setIdentity('identity identification');

        $user->addNetwork($network);

        $manager->persist($network);
        $manager->persist($user);

        $manager->flush();
    }
}
