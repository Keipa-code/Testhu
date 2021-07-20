<?php

declare(strict_types=1);


namespace App\Tests\unit\Entity;


use App\Entity\Result;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class ResultTest extends DatabaseDependantTestCase
{
    use FixturesTrait;

    public function testResultAddedInDB()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\ResultFixtures',
        ));

        $resultRepository = $this->entityManager->getRepository(Result::class);
        /** @var Result $resultRecord */
        $resultRecord = $resultRepository->findOneBy(['link' => 'https://result.com']);
        $userRecord = $resultRecord->getUserId();
        $testRecord = $resultRecord->getTest();

        self::assertEquals('https://result.com', $resultRecord->getLink());
        self::assertEquals(40, $resultRecord->getCorrectAnswersCount());
        self::assertEquals('2021-Jul-20 07:10:47', $resultRecord->getDate()->format('Y-M-d h:i:s'));
        self::assertEquals('ResultUser', $userRecord->getUsername());
        self::assertEquals('Мой тест с результатом', $testRecord->getTestName());
    }
}