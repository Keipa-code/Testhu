<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Result;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class ResultTest extends DatabaseDependantTestCase
{
    public function testResultAddedInDB(): void
    {
        $resultRepository = $this->entityManager->getRepository(Result::class);
        /** @var Result $resultRecord */
        $resultRecord = $resultRepository->findOneBy(['link' => 'https://result.com']);
        $userRecord = $resultRecord->getUserId();
        $testRecord = $resultRecord->getTest();

        self::assertEquals('https://result.com', $resultRecord->getLink());
        self::assertEquals(40, $resultRecord->getCorrectAnswersCount());
        self::assertEquals('2021-Jul-20 07:10:47', $resultRecord->getDate()->format('Y-M-d h:i:s'));
        self::assertEquals(true, $resultRecord->isWrongAnswersVisibles());
        self::assertEquals(true, $resultRecord->isPublic());
        self::assertEquals([
            '1' => false,
            '2' => true,
            '3' => true
        ], $resultRecord->getTestResults());
        self::assertEquals('myname', $userRecord->getUserIdentifier());
        self::assertEquals('Мой тест', $testRecord->getTestName());
    }
}
