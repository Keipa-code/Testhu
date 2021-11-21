<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Test;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class TestTest extends DatabaseDependantTestCase
{
    public function testTestAddedInDB(): void
    {
        $testRepository = $this->entityManager->getRepository(Test::class);
        /** @var Test $testRecord */
        $testRecord = $testRepository->findOneBy(['testName' => 'Мой тест']);
        $userRecord = $testRecord->getUserId();
        $resultRecord = $testRecord->getResults()->first();
        $questionRecord = $testRecord->getQuestions()->first();
        $tag = $testRecord->getTags()->first();

        self::assertEquals('Мой тест', $testRecord->getTestName());
        self::assertEquals(
            'Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире',
            $testRecord->getDescription()
        );
        self::assertEquals(
            'Время прохождения теста 2 часа 58 минут. Нужно выбирать один вариант',
            $testRecord->getRules()
        );
        self::assertEquals('2021-Jul-20 06:10:47', $testRecord->getDate()->format('Y-M-d h:i:s'));
        self::assertEquals('myname', $userRecord->getUserIdentifier());
        self::assertEquals('https://result.com', $resultRecord->getLink());
        self::assertEquals('one variant', $questionRecord->getQuestionType());
        self::assertEquals([Test::HOUR => 2, Test::MINUTE => 58], $testRecord->getTimeLimit());
        self::assertEquals(30, $testRecord->getDone());
        self::assertEquals(50, $testRecord->getPassed());
        self::assertEquals(false, $testRecord->isSubmitted());
        self::assertEquals('Физика', $tag->getTagName());
    }
}
