<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Network;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class NetworkTest extends DatabaseDependantTestCase
{
    public function testNetworkAddedInDB(): void
    {
        $networkRepository = $this->entityManager->getRepository(Network::class);
        /** @var Network $networkRecord */
        $networkRecord = $networkRepository->findOneBy(['name' => 'mail.ru']);
        $userRecord = $networkRecord->getUserId();

        self::assertEquals('mail.ru', $networkRecord->getName());
        self::assertEquals('identity identification', $networkRecord->getIdentity());
        self::assertEquals('myname', $userRecord->getUserIdentifier());
    }
}
