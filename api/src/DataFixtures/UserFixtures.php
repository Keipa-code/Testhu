<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('TestUser');
        $user->setDate(new \DateTimeImmutable());
        $user->setEmail('test@test.com');
        $user->setPasswordHash('123456');


        $test = new Test();
        $test->setTestName('My test');

        $question = new Question();
        $question->setQuestionType('one variant');
        $question->setAnswer(['b']);
        $question->setVariants(['a' => 'variant a', 'b' => 'variant b']);

        $test->addQuestion($question);
        $user->addTest($test);

        $manager->persist($user);
        $manager->persist($test);
        $manager->persist($question);

        $manager->flush();
    }
}
