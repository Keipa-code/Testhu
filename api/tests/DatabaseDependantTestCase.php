<?php

declare(strict_types=1);


namespace App\Tests;


use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseDependantTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;
    private ?ORMExecutor $fixtureExecutor;
    private ?ContainerAwareLoader $fixtureLoader;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }


}