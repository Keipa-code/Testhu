<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Test;

class TestApiTest extends ApiTestCase
{
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
            'http://localhost:8081/api/tests/2',
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
            'http://localhost:8081/api/tests/2',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой функиональный тест',
            'questions' => [
                '/api/questions/2'
            ]]);
    }

    public function testAddResultSuccess()
    {
        self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/2',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                    'Content-Type' => 'application/ld+json',
                ],
                'json' => [
                    'results' => ['/api/results/2'],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/tests/2',
            [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой функиональный тест',
            'results' => ['/api/results/2']
        ]);
    }

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
            'hydra:member' => [[
                'testName' => 'Мой тест',
                'description' => 'Этой мой тест. Я очень люблю свой тест. Мой тест самый лучший в мире',
                'rules' => 'Время прохождения теста 2 часа 58 минут. Нужно выбирать один вариант',
                'tags' => [[
                    '@id' => '/api/tags/1',
                    '@type' => 'Tag',
                    'id' => 1,
                    'tagName' => 'Физика',
                ]],
            ]]
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