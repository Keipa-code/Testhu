<?php

declare(strict_types=1);


namespace App\Tests\unit\Entity;


use App\DataFixtures\UserFixtures;
use App\Entity\Test;
use App\Entity\User;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class UserTest extends DatabaseDependantTestCase
{

    use FixturesTrait;

    public function testUserAddedInDB()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\UserFixtures',
        ));
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $userRecord */
        $userRecord = $userRepository->findOneBy(['username' => 'TestUser']);
        $testRecord = $userRecord->getTests()->first();
        $questionRecord = $testRecord->getQuestions()->first();

        self::assertEquals('TestUser', $userRecord->getUsername());
        self::assertEquals('test@test.com', $userRecord->getEmail());
        self::assertEquals('My test', $testRecord->getTestName());
        self::assertEquals('one variant', $questionRecord->getQuestionType());
    }
}