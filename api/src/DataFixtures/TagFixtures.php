<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $test = new Test();
        $test->setTestName('TestForTag');

        $tag = new Tag();
        $tag->setTagName('Математика');

        $test->addTag($tag);
        $manager->persist($test);
        $manager->persist($tag);

        $manager->flush();
    }
}
