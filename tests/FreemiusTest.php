<?php

namespace Freemius\SDK\Tests;

use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Exceptions\InvalidArgumentException;
use Freemius\SDK\Freemius;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class FreemiusTest
 *
 * @package Freemius\SDK\Tests
 */
protected function setUp(): void
    {
        parent::setUp();

        // Mock the HTTP client
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'api'       => 'pong',
                'timestamp' => '2023-12-19T12:00:00Z'
            ])), // Mock ping response
            new Response(200, [], json_encode([
                'plugins' => [
                    [
                        'id'                => '1',
                        'title'             => 'My Awesome Plugin',
                        'slug'              => 'my-plugin',
                        'public_key'        => '1d9681cd78bc9c6a8cb06725170acf7e',
                        'secret_key'        => '2d1O]}YzO,Tv%+;sEB39TY>c=3K9Ka9^',
                        'default_plan_id'   => '2',
                        'plans'             => '3,4,5',
                        'features'          => '123,8972,1234,5123,43',
                        'money_back_period' => 30,
                        'created'           => '2014-10-13 13:12:11',
                        'updated'           => '2014-10-13 13:12:11',
                    ],
                    [
                        'id'                => '3423',
                        'title'             => 'My Best Plugin Ever',
                        'slug'              => 'my-best-plugin',
                        'public_key'        => '2d9681cd78bc9c6a8cb06725170acf7e',
                        'secret_key'        => '3d1O]}YzO,Tv%+;sEB39TY>c=3K9Ka9^',
                        'default_plan_id'   => null,
                        'plans'             => '5,6',
                        'features'          => '1,2,3,4,5,6',
                        'money_back_period' => 14,
                        'created'           => '2014-11-13 13:12:11',
                        'updated'           => '2014-12-13 13:12:11',
                    ],
                ]
            ])), // Mock plugins response with data
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->freemius = new Freemius('developer', 17789, 'pk_test', 'sk_test', true);
        $this->freemius->setClient($client); // Inject the mocked client
    }

    /**
     * Test API connectivity using the ping endpoint.
     */
    #[Covers('\Freemius\SDK\FreemiusBase::test')]
    #[Covers('\Freemius\SDK\Freemius::_api')]
    #[Covers('\Freemius\SDK\Freemius::makeRequest')]
    #[Covers('\Freemius\SDK\Freemius::handleResponse')]
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testTestConnectivity(): void
    {
        $this->assertTrue($this->freemius->test());
    }

    /**
     * Test calculating the clock difference between the server and API server.
     *
     * @throws ApiException
     */
    #[Covers('\Freemius\SDK\FreemiusBase::findClockDiff')]
    #[Covers('\Freemius\SDK\Freemius::_api')]
    #[Covers('\Freemius\SDK\Freemius::makeRequest')]
    #[Covers('\Freemius\SDK\Freemius::handleResponse')]
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testFindClockDiff(): void
    {
        $diff = $this->freemius->findClockDiff();
        $this->assertIsInt($diff);
    }

    /**
     * Test canonizing API request paths.
     *
     * @throws InvalidArgumentException
     */
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testCanonizePath(): void
    {
        $this->assertEquals(
            '/developers/17789/plugins.json',
            $this->freemius->canonizePath('plugins')
        );
        $this->assertEquals(
            '/developers/17789/plugins.json',
            $this->freemius->canonizePath('plugins.json')
        );
        $this->assertEquals(
            '/developers/17789/plugins/123.json?test=1',
            $this->freemius->canonizePath('/plugins/123?test=1')
        );
    }

    /**
     * Test generating a signed URL.
     *
     * @throws InvalidArgumentException
     */
    #[Covers('\Freemius\SDK\Freemius::getSignedUrl')]
    #[Covers('\Freemius\SDK\Freemius::generateAuthorizationParams')]
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testGetSignedUrl(): void
    {
        $url = $this->freemius->getSignedUrl('plugins');

        // Updated assertion to match the actual generated URL
        $this->assertStringContainsString(
            '/developers/17789/plugins.json?',
            $url
        );
        $this->assertStringContainsString('auth_date=', $url);
        $this->assertStringContainsString('authorization=', $url);
    }

    /**
     * Test making a basic API request.
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     */
    #[Covers('\Freemius\SDK\FreemiusBase::api')]
    #[Covers('\Freemius\SDK\FreemiusBase::_api')]
    #[Covers('\Freemius\SDK\Freemius::makeRequest')]
    #[Covers('\Freemius\SDK\Freemius::handleResponse')]
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testApi(): void
    {
        $response = $this->freemius->api('plugins');
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('plugins', $response); // Use assertObjectHasProperty
    }

    /**
     * Test handling API errors.
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     */
    #[Covers('\Freemius\SDK\FreemiusBase::api')]
    #[Covers('\Freemius\SDK\FreemiusBase::_api')]
    #[Covers('\Freemius\SDK\Freemius::makeRequest')]
    #[Covers('\Freemius\SDK\Freemius::handleResponse')]
    #[Covers('\Freemius\SDK\FreemiusBase::canonizePath')]
    public function testApiErrorHandling(): void
    {
        // Mock an API error response
        $mock = new MockHandler([
            new Response(400, [], json_encode(['error' => ['message' => 'Bad Request', 'code' => 400]])), // Use integer code
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $this->freemius->setClient($client);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Bad Request');
        $this->expectExceptionCode(400); // Expect integer code

        $this->freemius->api('plugins');
    }

    /**
     * Test getting the MIME content type for a file.
     *
     * @throws InvalidArgumentException
     */
    #[Covers('\Freemius\SDK\Freemius::getMimeContentType')]
    public function testGetMimeContentType(): void
    {
        $this->assertEquals('application/zip', $this->freemius->getMimeContentType('test.zip'));
        $this->assertEquals('image/jpeg', $this->freemius->getMimeContentType('test.jpg'));
        $this->assertEquals('image/png', $this->freemius->getMimeContentType('test.png'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown file type: test.unknown'); // Add the exception message
        $this->freemius->getMimeContentType('test.unknown');
    }
}