<?php

declare(strict_types=1);


namespace App\Tests\Functional;

use App\Tests\WebApiTestCase;

class ResultApiTest extends WebApiTestCase
{

    public function testCreateResultSuccess(): void
    {
        $response = self::createClient()->request(
            'POST',
            'http://localhost:8081/api/results',
            [
                'json' => [
                    'date' => '2021-07-20 04:10:47',
                    'correctAnswersCount' => 30,
                    'link' => 'https://ya.ru',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(201);

        self::createClient()->request(
            'GET',
            'http://localhost:8081/api/results/' . $response->toArray()['id']
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Result',
            'date' => '2021-07-20T04:10:47+00:00',
            'correctAnswersCount' => 30,
            'link' => 'https://ya.ru',
        ]);
        self::assertMatchesRegularExpression('~^/api/results/\d+$~', $response->toArray()['@id']);
    }

}