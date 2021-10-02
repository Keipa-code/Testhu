<?php

declare(strict_types=1);


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

final class TagApiTest extends ApiTestCase
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

    public function testCreateTagSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tags',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'tagName' => 'Музыка',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Tag',
            'tagName' => 'Музыка',
        ]);
        self::assertMatchesRegularExpression('~^/api/tags/\d+$~', $response->toArray()['@id']);
    }

    public function testAttachTagToTest()
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tags',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'tagName' => 'Спорт',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);

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
                    'tags' => ['/api/tags/' . $response->toArray()['id']],
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
            'tags' => [
                [
                    'tagName' => 'Спорт',
                ]
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