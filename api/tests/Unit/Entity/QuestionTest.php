<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Question;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class QuestionTest extends DatabaseDependantTestCase
{
    public function testQuestionAddedInDB(): void
    {
        $questionRepository = $this->entityManager->getRepository(Question::class);
        /** @var Question $questionRecord */
        $questionRecord = $questionRepository->findOneBy(['id' => '1']);
        $testRecord = $questionRecord->getTest();


        self::assertEquals(
            'Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов',
            $questionRecord->getQuestionText()
        );
        self::assertEquals('one variant', $questionRecord->getQuestionType());
        self::assertEquals(['Mersedes', 'BMW', 'Volga'], $questionRecord->getVariants());
        self::assertEquals(['BMW'], $questionRecord->getAnswers());
        self::assertEquals(50, $questionRecord->getPoints());
        self::assertEquals(5, $questionRecord->getPosition());
        self::assertEquals('Мой тест', $testRecord->getTestName());
    }
}
