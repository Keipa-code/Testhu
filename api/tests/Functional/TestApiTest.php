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
                    'description' => 'Тест (от англ. test «испытание, проверка») или испытание — способ изучения глубинных процессов деятельности системы, посредством помещения системы в разные ситуации и отслеживание доступных наблюдению изменений в ней.',
                    'rules' => '«Правила игры» (фр. La Règle du jeu) — художественный фильм режиссёра Жана Ренуара, снятый в 1939 году во Франции. На протяжении многих десятилетий признаётся киноведами и кинокритиками одним из высших достижений европейского кинематографа.',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'apiTestTest',
            'date' => '2021-07-20T04:10:47+00:00',
            'description' => 'Тест (от англ. test «испытание, проверка») или испытание — способ изучения глубинных процессов деятельности системы, посредством помещения системы в разные ситуации и отслеживание доступных наблюдению изменений в ней.',
            'rules' => '«Правила игры» (фр. La Règle du jeu) — художественный фильм режиссёра Жана Ренуара, снятый в 1939 году во Франции. На протяжении многих десятилетий признаётся киноведами и кинокритиками одним из высших достижений европейского кинематографа.',
        ]);
        self::assertMatchesRegularExpression('~^/api/tests/\d+$~', $response->toArray()['@id']);
    }

    public function testAddQuestion()
    {
        $response = self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/tests/2',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'question' => ['/api/questions/2'],
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'apiTestTest',
            'date' => '2021-07-20T04:10:47+00:00',
            'description' => 'Тест (от англ. test «испытание, проверка») или испытание — способ изучения глубинных процессов деятельности системы, посредством помещения системы в разные ситуации и отслеживание доступных наблюдению изменений в ней.',
            'rules' => '«Правила игры» (фр. La Règle du jeu) — художественный фильм режиссёра Жана Ренуара, снятый в 1939 году во Франции. На протяжении многих десятилетий признаётся киноведами и кинокритиками одним из высших достижений европейского кинематографа.',
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