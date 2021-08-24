<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\MailerClient;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class UserApiTest extends ApiTestCase
{
    use FixturesTrait;

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

    public function testCreateUserSuccess(): void
    {
        $mailer = new MailerClient();
        $mailer->clear();

        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/users',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'username' => 'apiTestUser',
                    'date' => '2021-07-20 04:10:47',
                    'email' => 'mail@app.test',
                    'plainPassword' => 'Privet78',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'username' => 'apitestuser',
            'roles' => ['ROLE_USER'],
            'date' => '2021-07-20T04:10:47+00:00',
            'email' => 'mail@app.test',
        ]);
        self::assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);

        self::assertTrue($mailer->hasEmailSentTo('mail@app.test'));
    }

    public function testGetUser(): void
    {
        $data = $this->createAuthenticatedClient('myname', '12345678');
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`

        $user = self::createClient()->request(
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

    public function testIncorrectEmailFormat(): void
    {
        $response = self::createClient()->request(
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
            ]
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'hydra:description' => 'email: Веденные вами данные не являются email адресом',
        ]);
    }

    public function testIncorrectPasswordFormat(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/users',
            [
                'auth_bearer' => $this->token,
                'json' => [
                    'username' => 'apiTestUser',
                    'date' => '2021-07-20 04:10:47',
                    'email' => 'test@mail.ru',
                    'plainPassword' => '12345678',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'hydra:description' => 'plainPassword: Пароль не должен быть короче 8 символов и должен содержать хотя бы 1 большую и 1 маленькую букву алфавита, а также хотя бы 1 цифру',
        ]);
    }

    public function testChangePassword(): void
    {
        $data = $this->createAuthenticatedClient('myname', '12345678');
        $response = self::createClient()->request(
            'PUT',
            'http://localhost:8081/api/users/5',
            [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $data['token']),
                ],
                'json' => [
                    'plainPassword' => 'Priv1234567890',
                ],
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/5',
            '@type' => 'User',
            'id' => 5,
            'username' => 'myname',
        ]);
    }

    public function testHasByUsernameTrue(): void
    {
        $response = self::createClient()->request(
            'GET',
            'http://localhost:8081/api/users?username=myname',
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertJsonContains([
            'hydra:member' => [[
                'username' => 'myname',
                'email' => 'test@test.com',
            ]],
        ]);
    }

    public function testHasByEmailTrue(): void
    {
        $response = self::createClient()->request(
            'GET',
            'http://localhost:8081/api/users?email=test@test.com',
            [
                'auth_bearer' => $this->token,
            ]
        );

        $this->assertJsonContains([
            'hydra:member' => [[
                'username' => 'myname',
                'email' => 'test@test.com',
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
