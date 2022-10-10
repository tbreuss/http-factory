<?php

namespace Tests\Tebe\HttpFactory\Factory;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Tebe\HttpFactory\Factory\DiactorosFactory;

class DiactorosFactoryTest extends TestCase
{
    /**
     * @var DiactorosFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new DiactorosFactory();
    }

    public function testIsInstalled()
    {
        $this->assertTrue(DiactorosFactory::isInstalled());
    }

    public function testCreateResponse()
    {
        $this->assertInstanceOf(Response::class, $this->factory->createResponse());
    }

    public function testCreateServerRequest()
    {
        $this->assertInstanceOf(ServerRequest::class, $this->factory->createServerRequest('GET', '/'));
    }

    public function testCreateServerRequestFromGlobals()
    {
        $this->assertInstanceOf(ServerRequest::class, $this->factory->createServerRequestFromGlobals());
    }

    public function testCreateStream()
    {
        $this->assertInstanceOf(Stream::class, $this->factory->createStream('STREAM'));
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
