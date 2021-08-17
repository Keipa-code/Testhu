<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ResultFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('ResultUser');

        $test = new Test();
        $test->setTestName('Мой тест с результатом');

        $result = new Result();
        $result->setLink('https://result.com');
        $result->setCorrectAnswersCount(40);
        $result->setDate(new DateTimeImmutable('2021-07-20 07:10:47'));

        $user->addResult($result);
        $test->addResult($result);
        $manager->persist($user);
        $manager->persist($test);
        $manager->persist($result);

        $manager->flush();
    }
}
