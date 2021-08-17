<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class UserApiTest extends ApiTestCase
{
    use FixturesTrait;

    private $token;

    protected function setUp(): void
    {
        $response = static::createClient()->request(
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

    protected function createAuthenticatedClient($username = 'frontend_anonymous', $password = '12345678')
    {
        $response = static::createClient()->request(
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
        /*
                $client = static::createClient()->withOptions([

                    'auth_bearer' => $data['token'],
                ]);

                return $client;*/
    }

    public function testCreateUser()
    {
        $response = static::createClient()->request(
            'POST',
            'http://localhost:8081/api/users',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'username' => 'apiTestUser',
                    'date' => '2021-07-20 04:10:47',
                    'email' => 'mail@mail.ru',
                    'plainPassword' => 'Privet78',
                ],
            ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'username' => 'apiTestUser',
            'roles' => ['ROLE_USER'],
            'date' => '2021-07-20T04:10:47+00:00',
            'email' => 'mail@mail.ru',
        ]);
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
    }

    public function testIncorrectEmail()
    {
        $response = static::createClient()->request(
            'POST',
            'http://localhost:8081/api/users',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'username' => 'apiTestUser',
                    'date' => '2021-07-20 04:10:47',
                    'email' => 'not_email',
                    'password' => '12345678',
                ],
            ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'hydra:description' => 'email: Веденные вами данные не являются email адресом',
        ]);
    }

    public function testGetUser(): void
    {
        $data = $this->createAuthenticatedClient('myname', '12345678');
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`

        $user = static::createClient()->request(
            'GET',
            'http://localhost:8081/getme',
            [
                'auth_bearer' => $data['token'],
            ]
        );
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/5',
            '@type' => 'User',
            'id' => 5,
            'username' => 'myname',
            'date' => '2021-07-20T04:10:47+00:00',
            'email' => 'test@test.com',
            'status' => 'registered',
            'passwordResetToken' => '4ed161b5-0d3c-4f06-8381-5f14678e1300',
            'newEmail' => 'new-test@test.com',
            'network' => [
                '/api/networks/2',
            ],
            'results' => [
                '/api/results/3',
            ],
            'tests' => [
                '/api/tests/4',
            ],
        ]);
    }
}
