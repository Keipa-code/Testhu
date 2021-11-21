<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class QuestionFixturesForTest extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $question = new Question();
        $question->setQuestionText('Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов');
        $question->setQuestionType('one variant');
        $question->setPosition(5);
        $question->setPoints(50);
        $question->setVariants(['Mersedes', 'BMW', 'Volga']);
        $question->setAnswer(['BMW']);

        $question2 = new Question();
        $question2->setQuestionText('Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов');
        $question2->setQuestionType('one variant');
        $question2->setPosition(5);
        $question2->setPoints(50);
        $question2->setVariants(['Mersedes', 'BMW', 'Volga']);
        $question2->setAnswer(['BMW']);

        $this->addReference('question', $question);

        $manager->persist($question);
        $manager->persist($question2);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['TestsGroup'];
    }
}
