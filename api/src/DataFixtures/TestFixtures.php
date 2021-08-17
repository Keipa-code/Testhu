<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('TestUser');

        $result = new Result();
        $result->setLink('https://result.com');

        $question = new Question();
        $question->setQuestionType('one variant');

        $test = new Test();
        $test->setTestName('Мой тест');
        $test->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test->setTimeLimit(60);

        $user->addTest($test);
        $test->addResult($result);
        $test->addQuestion($question);

        $manager->persist($user);
        $manager->persist($result);
        $manager->persist($question);
        $manager->persist($test);
        $manager->flush();
    }
}
