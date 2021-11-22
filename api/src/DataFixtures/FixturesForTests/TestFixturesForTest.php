<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\DataFixtures\TagFixtures;
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

final class TestFixturesForTest extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{

    public function load(ObjectManager $manager): void
    {

        $tag = new Tag();
        $tag->setTagName('Физика');
        $test = new Test();
        $test->setTestName('Мой тест');
        $test->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test->setRules('Время прохождения теста 2 часа 58 минут. Нужно выбирать один вариант');
        $test->setDate(new DateTimeImmutable('2021-Jul-20 06:10:47'));
        $test->setTimeLimit([
            Test::HOUR => 2,
            Test::MINUTE => 58
        ]);
        $test->setDone(30);
        $test->setPassed(50);
        $test->setIsSubmitted(false);

        /** @var Tag $tag */
        $tag = $this->getReference(TagFixtures::TAGS_REFERENCE . 1);
        $test->addTag($tag);

        /** @var Question $question */
        $question = $this->getReference('question');
        $test->addQuestion($question);

        /** @var Result $result */
        $result = $this->getReference('result');
        $test->addResult($result);

        $this->addReference('test', $test);


        $testFunc = new Test();
        $testFunc->setTestName('Мой функиональный тест');
        $testFunc->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $testFunc->setRules('Время прохождения теста 2 часа 58 минут. Нужно выбирать один вариант');
        $testFunc->setDate(new DateTimeImmutable('2021-Jul-20 06:10:47'));
        $testFunc->setTimeLimit([
            Test::HOUR => 2,
            Test::MINUTE => 58
        ]);
        $testFunc->setDone(30);
        $testFunc->setPassed(50);
        $testFunc->setIsSubmitted(false);

        $testSubmitted = new Test;
        $testSubmitted->setTestName('Submitted test');
        $testSubmitted->setIsSubmitted(true);
        /** @var Question $question */
        $question3 = $this->getReference('question3');
        $testSubmitted->addQuestion($question3);

        $manager->persist($test);
        $manager->persist($testFunc);
        $manager->persist($testSubmitted);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
            QuestionFixturesForTest::class,
            ResultFixturesForTest::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['TestsGroup'];
    }
}
