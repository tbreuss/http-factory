<?php

namespace Tests\Tebe\HttpFactory;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\Uri;
use Tebe\HttpFactory\Factory\SlimFactory;

class SlimeFactoryTest extends TestCase
{
    /**
     * @var SlimFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new SlimFactory();
    }

    public function testIsInstalled()
    {
        $this->assertTrue(SlimFactory::isInstalled());
    }

    public function testCreateResponse()
    {
        $this->assertInstanceOf(Response::class, $this->factory->createResponse());
    }

    public function testCreateServerRequest()
    {
        $this->assertInstanceOf(Request::class, $this->factory->createServerRequest('GET', '/'));
    }

    public function testCreateStream()
    {
        $this->assertInstanceOf(Stream::class, $this->factory->createStream('fromString'));
    }

    public function testCreateStreamFromFile()
    {
        $file = dirname(__DIR__) . '/resources/file.txt';
        $this->assertInstanceOf(Stream::class, $this->factory->createStreamFromFile($file));
    }

    public function testCreateStreamFromResource()
    {
        $resource = fopen(dirname(__DIR__) . '/resources/file.txt', 'r');
        $this->assertInstanceOf(Stream::class, $this->factory->createStreamFromResource($resource));
    }

    public function testCreateUri()
    {
        $this->assertInstanceOf(Uri::class, $this->factory->createUri('http://example.com'));
    }

    public function testCreateUploadedFile()
    {
        $file = dirname(__DIR__) . '/resources/file.txt';
        $stream = $this->factory->createStreamFromFile($file);
        $size = $stream->getSize();
        $uploadedFile = $this->factory->createUploadedFile($stream, $size);
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
    }
}
