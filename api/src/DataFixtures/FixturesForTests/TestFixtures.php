<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Tag;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TestFixtures extends Fixture
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
        $test->addTag($tag);

        $manager->persist($tag);
        $manager->persist($test);

        $manager->flush();
    }
}
