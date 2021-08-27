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
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой тест с результатом',
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
                [
                    '@id' => '/api/questions/2',
                    '@type' => 'Question',
                    'id' => 2,
                    'questionText' => NULL,
                ]
            ]]);
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