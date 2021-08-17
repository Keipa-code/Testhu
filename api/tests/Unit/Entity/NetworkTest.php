<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Network;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class NetworkTest extends DatabaseDependantTestCase
{
    use FixturesTrait;

    public function testNetworkAddedInDB()
    {
        $this->loadFixtures([
            'App\DataFixtures\NetworkFixtures',
        ]);

        $networkRepository = $this->entityManager->getRepository(Network::class);
        /** @var Network $networkRecord */
        $networkRecord = $networkRepository->findOneBy(['name' => 'mail.ru']);
        $userRecord = $networkRecord->getUserId();

        self::assertEquals('mail.ru', $networkRecord->getName());
        self::assertEquals('identity identification', $networkRecord->getIdentity());
        self::assertEquals('NetworkUser', $userRecord->getUsername());
    }
}
