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
    use FixturesTrait;

    public function testQuestionAddedInDB(): void
    {
        $this->loadFixtures([
            'App\DataFixtures\QuestionFixtures',
        ]);

        $questionRepository = $this->entityManager->getRepository(Question::class);
        /** @var Question $questionRecord */
        $questionRecord = $questionRepository->findOneBy(['questionType' => 'one variant']);
        $testRecord = $questionRecord->getTest();
        $tag = $questionRecord->getTags()->first();

        self::assertEquals(
            'Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов',
            $questionRecord->getQuestionText()
        );
        self::assertEquals('one variant', $questionRecord->getQuestionType());
        self::assertEquals(['Mersedes', 'BMW', 'Volga'], $questionRecord->getVariants());
        self::assertEquals(['BMW'], $questionRecord->getAnswer());
        self::assertEquals(50, $questionRecord->getPoints());
        self::assertEquals(5, $questionRecord->getPosition());
        self::assertEquals('Мой тест', $testRecord->getTestName());
        self::assertEquals('Физика', $tag->getTagName());
    }
}
