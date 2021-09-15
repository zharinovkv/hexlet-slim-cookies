<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = new \GuzzleHttp\Client([
            'cookies' => true,
            'base_uri' => 'http://localhost:8080'
        ]);
    }

    public function testCourses()
    {
        $response = $this->client->get('/');
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('is empty', $body);
        $formParams = ['item' => ['id' => '1', 'name' => 'One']];
        $response = $this->client->post('/cart-items', [
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('One', $body);
        $this->assertStringContainsString('1', $body);

        $formParams = ['item' => ['id' => '1', 'name' => 'One']];
        $response = $this->client->post('/cart-items', [
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('One', $body);
        $this->assertStringContainsString('2', $body);

        $cookieJar = $this->client->getConfig('cookies');
        [$cookie] = $cookieJar->toArray();
        $decodedCookie = json_decode($cookie['Value'], true);
        $count = array_reduce($decodedCookie, fn($acc, $item) => $acc + $item['count'], 0);
        $this->assertEquals(2, $count);

        $formParams = ['item' => ['id' => '2', 'name' => 'Two']];
        $response = $this->client->post('/cart-items', [
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('Two', $body);
        $formParams = ['item' => ['id' => '2', 'name' => 'Two']];
        $response = $this->client->post('/cart-items', [
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('Two', $body);

        $formParams = ['item' => ['id' => '2', 'name' => 'Two']];
        $response = $this->client->post('/cart-items', [
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('Two', $body);
        $this->assertStringContainsString('3', $body);

        $response = $this->client->delete('/cart-items');
        $body = $response->getBody()->getContents();
        $this->assertStringContainsString('is empty', $body);
        $this->assertStringNotContainsString('Two: 2', $body);
    }
}
