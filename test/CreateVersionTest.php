<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;
use \RuntimeException;

class CreateVersionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::modifyResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturns201withVersions()
    {
        $mock = new MockHandler(
            [
            new Response(200, ['Link' => '<http://localhost:8080/rest/path/to/resource/fcr:versions>;rel="timemap"']),
            new Response(201, ['Location' => "SOME URI"])
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->createVersion('');
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testThrowsExceptionWithoutTimemapUri()
    {
        $mock = new MockHandler(
            [
            new Response(200, []),
            new Response(201, ['Location' => "SOME URI"])
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $this->expectException(\RuntimeException::class);
        $result = $api->createVersion('');
    }
}
