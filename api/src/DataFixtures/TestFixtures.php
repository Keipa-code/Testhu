<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Tag;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TestFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const TESTS_REFERENCE = 'test_';
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');



        $tag = ['1', '2', '3', '4', '5', '6'];

        $batchSize = 20;
        for ($i = 1; $i <= 100; $i++) {
            $test = new Test();
            $test->setTestName($faker->sentence(2));
            $test->setDescription($faker->sentence(13));
            $test->setRules($faker->sentence(13));
            $test->setDate(new DateTimeImmutable($faker->date('Y-m-d', 'now')));
            $test->setTimeLimit([
                Test::HOUR => 2,
                Test::MINUTE => 58
            ]);
            $test->setDone($faker->numberBetween(310, 700));
            $test->setPassed($faker->numberBetween(50, 300));
            $test->setIsSubmitted(false);
            shuffle($tag);
            $test->addTag($this->getReference(TagFixtures::TAGS_REFERENCE . $tag['0']));
            $test->addTag($this->getReference(TagFixtures::TAGS_REFERENCE . $tag['1']));
            $test->addTag($this->getReference(TagFixtures::TAGS_REFERENCE . $tag['2']));

            $this->addReference(self::TESTS_REFERENCE . $i, $test);

            $manager->persist($test);
            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear(); // Detaches all objects from Doctrine!
            }

        }

        $manager->flush();
        $manager->clear();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['FakeDataGroup'];
    }
}
