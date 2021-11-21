<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 */
abstract class DatabaseDependantTestCase extends ApiTestCase
{
    protected EntityManagerInterface $entityManager;

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

    protected function fetch($url, $data) {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/' . $url,
            $data
        );

        return $response->toArray()['id'];
    }
}
