<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public const TAGS_REFERENCE = 'tag_';

    public function load(ObjectManager $manager): void
    {
        $tagPhysics = new Tag();
        $tagPhysics->setTagName('Физика');
        $this->addReference(self::TAGS_REFERENCE . '1', $tagPhysics);

        $tagChemistry = new Tag();
        $tagChemistry->setTagName('Химия');
        $this->addReference(self::TAGS_REFERENCE . '2', $tagChemistry);

        $tagMath = new Tag();
        $tagMath->setTagName('Математика');
        $this->addReference(self::TAGS_REFERENCE . '3', $tagMath);

        $tagGeo = new Tag();
        $tagGeo->setTagName('География');
        $this->addReference(self::TAGS_REFERENCE . '4', $tagGeo);

        $tagLit = new Tag();
        $tagLit->setTagName('Литература');
        $this->addReference(self::TAGS_REFERENCE . '5', $tagLit);

        $tagProgramming = new Tag();
        $tagProgramming->setTagName('Программирование');
        $this->addReference(self::TAGS_REFERENCE . '6', $tagProgramming);


        $tag = [$tagPhysics, $tagChemistry, $tagMath, $tagGeo, $tagLit, $tagProgramming];

        foreach ($tag as $item) {
            $manager->persist($item);
        }

        $manager->flush();
    }

}
