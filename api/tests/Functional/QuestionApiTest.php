<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class QuestionApiTest extends ApiTestCase
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

    public function testCreateQuestionSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/questions',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'questionText' => 'Вопрос о смысле бытия',
                    'questionType' => 'Тип вопроса',
                    'variants' => [
                        'variants1', 'variants2'
                    ],
                    'answer' => [
                        'single answer'
                    ],
                    'points' => 50,
                    'position' => 2,
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(201);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/questions/' . $response->toArray()['id'],
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Question',
            'questionText' => 'Вопрос о смысле бытия',
            'questionType' => 'Тип вопроса',
            'variants' => [
                'variants1', 'variants2'
            ],
            'answer' => [
                'single answer'
            ],
            'points' => 50,
            'position' => 2,
        ]);
        self::assertMatchesRegularExpression('~^/api/questions/\d+$~', $response->toArray()['@id']);
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