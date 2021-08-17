<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $test = new Test();
        $test->setTestName('Мой тест');

        $tag = new Tag();
        $tag->setTagName('Физика');

        $question = new Question();
        $question->setQuestionText('Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов');
        $question->setQuestionType('one variant');
        $question->setVariants(['Mersedes', 'BMW', 'Volga']);
        $question->setAnswer(['BMW']);
        $question->setPoints(50);
        $question->setPosition(5);
        $question->addTag($tag);

        $test->addQuestion($question);

        $manager->persist($tag);
        $manager->persist($question);
        $manager->persist($test);

        $manager->flush();
    }
}
