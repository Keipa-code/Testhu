<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');
        $batchSize = 20;
        for ($itest = 1; $itest <= 100; $itest++) {
            for ($i = 1; $i <= 20; $i++) {
                $question = new Question();
                $question->setQuestionText($faker->sentence(8));
                $question->setQuestionType('choose');
                $question->setPosition($i);
                $question->setPoints($faker->numberBetween(5, 20));
                for ($iVariants = 1; $iVariants <= 4; $iVariants++) {
                    $question->setVariants([
                        'id' => $iVariants,
                        'correct' => ($iVariants === 1) ?? false,
                        'text' => $faker->sentence(5)
                    ]);
                }
                $question->setTest($this->getReference(TestFixtures::TESTS_REFERENCE . $itest));
                $manager->persist($question);
                if (($i % $batchSize) === 0) {
                    $manager->flush();
                    $manager->clear(); // Detaches all objects from Doctrine!
                }

            }
            $manager->flush();
            $manager->clear();

        }
    }

    public function getDependencies()
    {
        return [
            TestFixtures::class,
        ];
    }
}
