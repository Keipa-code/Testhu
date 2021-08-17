<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $question = new Question();
        $question->setQuestionType('few variants');

        $tag = new Tag();
        $tag->setTagName('Математика');

        $question->addTag($tag);
        $manager->persist($question);
        $manager->persist($tag);

        $manager->flush();
    }
}
