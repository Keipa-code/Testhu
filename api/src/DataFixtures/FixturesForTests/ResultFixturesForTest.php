<?php

declare(strict_types=1);

namespace App\DataFixtures\FixturesForTests;

use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResultFixturesForTest extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager): void
    {
        $result = new Result();
        $result->setLink('https://result.com');
        $result->setCorrectAnswersCount(40);
        $result->setDate(new DateTimeImmutable('2021-07-20 07:10:47'));
        $result->setTestResults([
            '1' => false,
            '2' => true,
            '3' => true
        ]);

        $result2 = new Result();
        $result2->setLink('https://result2.com');
        $result2->setCorrectAnswersCount(40);
        $result2->setDate(new DateTimeImmutable('2021-07-20 07:10:47'));

        $this->addReference('result', $result);

        $manager->persist($result);
        $manager->persist($result2);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['TestsGroup'];
    }
}
