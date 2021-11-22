<?php

declare(strict_types=1);


namespace App\Tests\Functional;


use App\Tests\WebApiTestCase;

final class TagApiTest extends WebApiTestCase
{
    public function testCreateTagSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/tags',
            [
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
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'testName' => 'Мой функиональный тест',
            'tags' => [
                [
                    'tagName' => 'Спорт',
                ]
            ]]);
    }
}