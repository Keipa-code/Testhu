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

        $tag1 = new Tag();
        $tag1->setTagName('Физика');

        $tag2 = new Tag();
        $tag2->setTagName('Химия');

        $question = new Question();
        $question->setQuestionText('Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов');
        $question->setQuestionType('one variant');
        $question->setVariants(['Mersedes', 'BMW', 'Volga']);
        $question->setAnswer(['BMW']);
        $question->setPoints(50);
        $question->setPosition(5);
        $question->addTag($tag1);
        $question->addTag($tag2);

        $test->addQuestion($question);

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($question);
        $manager->persist($test);

        $manager->flush();
    }
}
