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
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TestFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('TestUser');
        $user->setPassword($this->hasher->hashPassword($user, '12345678'));


        $tag1 = new Tag();
        $tag1->setTagName('Физика');

        $tag2 = new Tag();
        $tag2->setTagName('Химия');

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
        $test->addTag($tag1);
        $test->addTag($tag2);

        $user->addTest($test);
        $test->addResult($result);
        $test->addQuestion($question);

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($user);
        $manager->persist($result);
        $manager->persist($question);
        $manager->persist($test);
        $manager->flush();

        $test1 = new Test();
        $test1->setTestName('Мой тест');
        $test1->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test1->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test1->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test1->setTimeLimit(60);

        $test2 = new Test();
        $test2->setTestName('Мой тест');
        $test2->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test2->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test2->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test2->setTimeLimit(60);

        $test3 = new Test();
        $test3->setTestName('Мой тест');
        $test3->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test3->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test3->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test3->setTimeLimit(60);

        $test4 = new Test();
        $test4->setTestName('Мой тест');
        $test4->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test4->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test4->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test4->setTimeLimit(60);

        $test5 = new Test();
        $test5->setTestName('Мой тест');
        $test5->setDescription('Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире');
        $test5->setRules('Время прохождения теста 40 минут. Нужно выбирать один вариант');
        $test5->setDate(new DateTimeImmutable('2021-07-20 06:10:47'));
        $test5->setTimeLimit(60);

        $manager->persist($test1);
        $manager->persist($test2);
        $manager->persist($test3);
        $manager->persist($test4);
        $manager->persist($test5);
        $manager->flush();
    }
}
