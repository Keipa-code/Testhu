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
                    'tags' => ['/api/tags/1', '/api/tags/2']
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
            'tags' => [
                [
                    '@id' => '/api/tags/1',
                    '@type' => 'Tag',
                    'id' => 1,
                    'tagName' => 'Физика',
                ],
                [
                    '@id' => '/api/tags/2',
                    '@type' => 'Tag',
                    'id' => 2,
                    'tagName' => 'Химия',
                ]],
        ]);
        self::assertMatchesRegularExpression('~^/api/questions/\d+$~', $response->toArray()['@id']);
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
            'testName' => 'My test',
            'results' => [
                [
                    '@id' => '/api/results/3',
                    '@type' => 'Result',
                    'link' => 'https://result.com',
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