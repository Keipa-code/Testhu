<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class UserTest extends DatabaseDependantTestCase
{
    use FixturesTrait;

    public function testUserAddedInDB(): void
    {
        $this->loadFixtures([
            'App\DataFixtures\UserFixtures',
        ]);

        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $userRecord */
        $userRecord = $userRepository->findOneBy(['username' => 'myname']);
        $testRecord = $userRecord->getTests()->first();
        $resultRecord = $userRecord->getResults()->first();
        $networkRecord = $userRecord->getNetwork()->first();

        self::assertEquals('myname', $userRecord->getUsername());
        self::assertEquals('test@test.com', $userRecord->getEmail());
        self::assertEquals('2021-Jul-20 04:10:47', $userRecord->getDate()->format('Y-M-d h:i:s'));
        self::assertEquals('4ed161b5-0d3c-4f06-8381-5f14678e1300', $userRecord->getPasswordResetToken());
        self::assertEquals('new-test@test.com', $userRecord->getNewEmail());
        self::assertEquals('My test', $testRecord->getTestName());
        self::assertEquals('https://result.com', $resultRecord->getLink());
        self::assertEquals('mail.ru', $networkRecord->getName());
    }
}
