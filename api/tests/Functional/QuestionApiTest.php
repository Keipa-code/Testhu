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

    public function testGetQuestionByPosition(): void
    {
        $response = self::createClient()->request(
            'GET',
            'http://localhost:8081/api/questions?test=1&position=5',
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertJsonContains([
            'hydra:member' => [[
                'questionText' => 'Два паравоза выехали из точкии А и Б. Какая марка у этих паравозов',
                'questionType' => 'one variant',
                'variants' => [
                        0 => 'Mersedes',
                        1 => 'BMW',
                        2 => 'Volga',
                    ],
                'answer' => [
                        0 => 'BMW',
                    ],
                'points' => 50,
                'position' => 5,
                'test' => '/api/tests/1',
            ]],
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