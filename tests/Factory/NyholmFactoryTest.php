<?php

namespace Tests\Tebe\HttpFactory;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Tebe\HttpFactory\Factory\NyholmFactory;

class NyholmFactoryTest extends TestCase
{
    /**
     * @var NyholmFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new NyholmFactory();
    }

    public function testIsInstalled()
    {
        $this->assertTrue(NyholmFactory::isInstalled());
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
