<?php

declare(strict_types=1);


namespace App\Tests\Unit\Entity;


use App\Entity\Test;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class TestTest extends DatabaseDependantTestCase
{
    use FixturesTrait;

    public function testTestAddedInDB()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\TestFixtures',
        ));

        $testRepository = $this->entityManager->getRepository(Test::class);
        /** @var Test $testRecord */
        $testRecord = $testRepository->findOneBy(['testName' => 'Мой тест']);
        $userRecord = $testRecord->getUserId();
        $resultRecord = $testRecord->getResults()->first();
        $questionRecord = $testRecord->getQuestions()->first();

        self::assertEquals('Мой тест', $testRecord->getTestName());
        self::assertEquals('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире',
            $testRecord->getDescription());
        self::assertEquals('Время прохождения теста 40 минут. Нужно выбирать один вариант',
            $testRecord->getRules());
        self::assertEquals('2021-Jul-20 06:10:47', $testRecord->getDate()->format('Y-M-d h:i:s'));
        self::assertEquals(60, $testRecord->getTimeLimit());
        self::assertEquals('TestUser', $userRecord->getUsername());
        self::assertEquals('https://result.com', $resultRecord->getLink());
        self::assertEquals('one variant', $questionRecord->getQuestionType());
    }
}