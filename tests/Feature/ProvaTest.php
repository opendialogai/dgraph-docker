<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Tests\TestCase;

class ProvaTest extends TestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();

        $dgraphUrl = $_ENV['DGRAPH_URL'];
        $dGraphPort = $_ENV['DGRAPH_PORT'];

        $this->client = new Client([
            'base_uri' => $dgraphUrl . ":" . $dGraphPort
        ]);
    }

    public function testDropSchema()
    {
        $result = $this->alter('{"drop_all": true}');

        $this->assertEquals('Success', $result['code']);
    }

    public function testInitSchema()
    {
        $schema = "<name>: default .";
        $result = $this->alter($schema);

        $this->assertEquals('Success', $result['code']);
    }

    private function alter(string $alter)
    {
        $response = $this->client->request(
            'POST',
            'alter',
            ['body' => $alter]
        );

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }
}
