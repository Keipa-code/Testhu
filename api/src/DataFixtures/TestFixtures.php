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
use Faker\Factory;
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
        $faker = Factory::create('ru_RU');

        $tagPhysics = new Tag();
        $tagPhysics->setTagName('Физика');

        $tagChemistry = new Tag();
        $tagChemistry->setTagName('Химия');

        $tagMath = new Tag();
        $tagMath->setTagName('Математика');

        $tagGeo = new Tag();
        $tagGeo->setTagName('География');

        $tagLit = new Tag();
        $tagLit->setTagName('Литература');

        $tagProgramming = new Tag();
        $tagProgramming->setTagName('Программирование');

        $tag = [$tagPhysics, $tagChemistry, $tagMath, $tagGeo, $tagLit, $tagProgramming];

        foreach ($tag as $item) {
            $manager->persist($item);
        }
        $batchSize = 20;
        for ($i = 1; $i <= 5; $i++) {
            $test = new Test();
            $test->setTestName($faker->sentence(2));
            $test->setDescription($faker->sentence(13));
            $test->setRules($faker->sentence(13));
            $test->setDate(new DateTimeImmutable($faker->date('Y-m-d', 'now')));
            $test->setTimeLimit(60);
            $test->setDone($faker->numberBetween(310, 700));
            $test->setPassed($faker->numberBetween(50, 300));
            $test->setIsSubmitted(true);
            shuffle($tag);
            $test->addTag($tag['0']);
            $test->addTag($tag['1']);
            $test->addTag($tag['2']);

            /*for ($i = 1; $i <= 3; $i++) {
                $question = new Question();
                $question->setQuestionText($faker->sentence(8));
                $question->setQuestionType('choose');
                $question->setPosition($i);
                $question->setPoints($faker->numberBetween(5, 20));
                for ($i = 1; $i <= 4; $i++) {
                    $question->setVariants([
                        'id' => $i,
                        'correct' => ($i === 1) ?? false,
                        'text' => $faker->sentence(5)
                    ]);
                }
                $test->addQuestion($question);
                $manager->persist($question);
                $manager->persist($test);
                if (($i % $batchSize) === 0) {
                    $manager->flush();
                    $manager->clear(); // Detaches all objects from Doctrine!
                }

            }*/

            $manager->persist($test);
            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear(); // Detaches all objects from Doctrine!
            }

        }
        $manager->flush();
        $manager->clear();



        /*$user = new User();
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
        $manager->flush();*/
    }
}
