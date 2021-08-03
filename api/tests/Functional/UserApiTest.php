<?php

declare(strict_types=1);


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

final class UserApiTest extends ApiTestCase
{
    use FixturesTrait;

    public function testCreateUser()
    {
        $response = static::createClient()->request('POST', 'http://localhost:8081/api/users', ['json' => [
            'username' => 'api test user',
            'date' => '2021-07-20 04:10:47',
            'email' => 'mail@mail.ru',
            'password' => '123456',
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'username' => 'api test user',
            'date' => '2021-07-20T04:10:47+00:00',
            'email' => 'mail@mail.ru',
            'password' => '123456',
        ]);
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testGetCollection(): void
    {
        $this->loadFixtures(array(
            'App\DataFixtures\UserFixtures',
        ));
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', 'http://localhost:8081/api/users');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:member' => [[
                '@id' => '/api/users/1',
                '@type' => 'User',
                'id' => 1,
                'username' => 'TestUser',
                'date' => '2021-07-20T04:10:47+00:00',
                'email' => 'test@test.com',
                'password' => '123456',
                'status' => 'registered',
                'emailConfirmToken' => '4ed161b5-0d3c-4f06-8381-5f14678e13da',
                'passwordResetToken' => '4ed161b5-0d3c-4f06-8381-5f14678e1300',
                'newEmail' => 'new-test@test.com',
                'network' => [[
                    '@type' => 'Network',
                    'id' => 1,
                    'name' => 'mail.ru',
                    'userId' => '/api/users/1',
                ],],
                'results' => [[
                    '@type' => 'Result',
                    'id' => 1,
                    'userId' => '/api/users/1',
                    'link' => 'https://result.com',
                ],],
                'tests' => [[
                    '@type' => 'Test',
                    'id' => 1,
                    'testName' => 'My test',
                    'results' => [],
                    'questions' => [],
                    'user' => '/api/users/1',
                ],],]],
            'hydra:totalItems' => 1,
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(1, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

}