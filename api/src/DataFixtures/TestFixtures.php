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
    }
}
