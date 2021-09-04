<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ResultApiTest extends ApiTestCase
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

    public function testCreateResultSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/results',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'date' => '2021-07-20 04:10:47',
                    'correctAnswersCount' => 30,
                    'link' => 'https://ya.ru',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(201);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/results/' . $response->toArray()['id'],
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Result',
            'date' => '2021-07-20T04:10:47+00:00',
            'correctAnswersCount' => 30,
            'link' => 'https://ya.ru',
        ]);
        self::assertMatchesRegularExpression('~^/api/results/\d+$~', $response->toArray()['@id']);
    }

    public function testAttachResultToTestSuccess()
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/results',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'date' => '2021-07-20 04:10:47',
                    'correctAnswersCount' => 30,
                    'link' => 'https://ya.ru',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertIsNumeric($response->toArray()['id']);

        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/3',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'results' => ['/api/results/' . $response->toArray()['id']],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/results/' . $response->toArray()['id'],
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'date' => '2021-07-20T04:10:47+00:00',
            'test' => '/api/tests/3',
            'correctAnswersCount' => 30,
            'link' => 'https://ya.ru',
            'user_id' => NULL,
            ]);
    }

    public function testAttachResultToUserSuccess()
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/results',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'date' => '2021-07-20 04:10:47',
                    'correctAnswersCount' => 30,
                    'link' => 'https://ya.ru',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertIsNumeric($response->toArray()['id']);

        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/users/5',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'results' => ['/api/results/' . $response->toArray()['id']],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/results/' . $response->toArray()['id'],
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'date' => '2021-07-20T04:10:47+00:00',
            'test' => null,
            'correctAnswersCount' => 30,
            'link' => 'https://ya.ru',
            'user_id' => '/api/users/5',
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