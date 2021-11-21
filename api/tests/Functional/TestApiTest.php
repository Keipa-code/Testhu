<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class TestApiTest extends ApiTestCase
{
    private $id;

    protected function setUp(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tests',
            [
                'json' => [
                    'testName' => 'apiTestForID',
                ],
            ]
        );

        $this->id = $response->toArray()['id'];
    }

    public function testCreateTestSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tests',
            [
                'json' => [
                    'testName' => 'apiTestTest',
                    'date' => '2021-07-20 04:10:47',
                    'timeLimit' => ['hour' => 2, 'minute' => 57],
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests/' . $response->toArray()['id']
        );

        $this->assertResponseStatusCodeSame(200);

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Test',
            'testName' => 'apiTestTest',
            'date' => '2021-07-20T04:10:47+00:00',
            'timeLimit' => ['hour' => 2, 'minute' => 57],

        ]);
        self::assertMatchesRegularExpression('~^/api/tests/\d+$~', $response->toArray()['@id']);
    }

    public function testAddQuestion()
    {
        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/' . $this->id,
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                    'Content-Type' => 'application/ld+json',
                ],
                'json' => [
                    'questions' => ['/api/questions/2'],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests/' . $this->id,
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'apiTestForID',
            'questions' => [
                '/api/questions/2'
            ]]);
    }

    public function testAddResultSuccess()
    {
        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/4',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                    'Content-Type' => 'application/ld+json',
                ],
                'json' => [
                    'results' => ['/api/results/3'],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests/4',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой тест',
            'results' => ['/api/results/3']
        ]);
    }

    /**
     * @group ignore
     */
    public function testPagination()
    {
        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests?tags.tagName=Физика&page=1',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой тест',
            'results' => ['/api/results/3']
        ]);
    }

    protected function createAuthenticatedClient($username = 'frontend_anonymous', $password = '12345678')
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/login',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]
        );

        return $response->toArray();
    }
}