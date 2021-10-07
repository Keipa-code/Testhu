<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class TestApiTest extends ApiTestCase
{
    private $token;

    protected function setUp(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/login',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'username' => 'frontend_anonymous',
                    'password' => '12345678',
                ],
            ]
        );

        $this->token = $response->toArray()['token'];
    }

    public function testCreateTestSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tests',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'testName' => 'apiTestTest',
                    'date' => '2021-07-20 04:10:47',
                    'tags' => ['/api/tags/1', '/api/tags/2']
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests/' . $response->toArray()['id'],
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertResponseStatusCodeSame(200);

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Test',
            'testName' => 'apiTestTest',
            'date' => '2021-07-20T04:10:47+00:00',
            'timeLimit' => NULL,
            'tags' => [
                [
                    '@id' => '/api/tags/1',
                    '@type' => 'Tag',
                    'id' => 1,
                    'tagName' => 'Математика',
                ],
                [
                    '@id' => '/api/tags/2',
                    '@type' => 'Tag',
                    'id' => 2,
                    'tagName' => 'Физика',
                ]],
        ]);
        self::assertMatchesRegularExpression('~^/api/tests/\d+$~', $response->toArray()['@id']);
    }

    public function testAddQuestion()
    {
        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/2',
            [
                'auth_bearer' => $this->token,
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
            'http://localhost:8081/api/tests/2',
            [
                'auth_bearer' => $this->token,
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой тест с результатом',
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
                'auth_bearer' => $this->token,
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
                'auth_bearer' => $this->token,
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

    public function testPagination()
    {
        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests?page=2',
            [
                'auth_bearer' => $this->token,
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