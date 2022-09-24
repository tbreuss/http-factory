<?php

namespace Tests\Tebe\HttpFactory;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Tebe\HttpFactory\Factory\DiactorosFactory;
use Tebe\HttpFactory\Factory\FactoryInterface;
use Tebe\HttpFactory\Factory\GuzzleFactory;
use Tebe\HttpFactory\Factory\SlimFactory;
use Tebe\HttpFactory\HttpFactory;

class HttpFactoryTest extends TestCase
{
    /**
     * @var HttpFactory
     */
    private $factory;

    protected function setUp(): void
    {
        HttpFactory::setStrategies([
            DiactorosFactory::class,
            GuzzleFactory::class,
            SlimFactory::class
        ]);
        $this->factory = new HttpFactory();
    }

    public function testSetFactory()
    {
        $mock = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $this->assertInstanceOf(HttpFactory::class, $this->factory->setFactory($mock));
    }

    public function testGetFactory()
    {
        $this->assertInstanceOf(FactoryInterface::class, $this->factory->getFactory());
    }

    public function testGetFactoryWithPreviouslySetFactory()
    {
        $this->factory->setFactory($this->getMockBuilder(FactoryInterface::class)->getMock());
        $this->assertInstanceOf(FactoryInterface::class, $this->factory->getFactory());
    }

    public function testGetFactoryWithMissingStrategies()
    {
        $this->expectException(RuntimeException::class);
        HttpFactory::setStrategies([]);
        $this->factory->getFactory();
    }

    public function testCreateResponse()
    {
        $this->assertInstanceOf(ResponseInterface::class, $this->factory->createResponse(200));
    }

    public function testCreateServerRequest()
    {
        $this->assertInstanceOf(ServerRequestInterface::class, $this->factory->createServerRequest('GET', '/'));
    }

    public function testCreateServerRequestFromGlobals()
    {
        $this->assertInstanceOf(ServerRequestInterface::class, $this->factory->createServerRequestFromGlobals());
    }

    public function testCreateStream()
    {
        $this->assertInstanceOf(StreamInterface::class, $this->factory->createStream('fromString'));
    }

    public function testCreateStreamFromFile()
    {
        $file = __DIR__ . '/resources/file.txt';
        $this->assertInstanceOf(StreamInterface::class, $this->factory->createStreamFromFile($file));
    }

    public function testCreateStreamFromResource()
    {
        $resource = fopen(__DIR__ . '/resources/file.txt', 'r');
        $this->assertInstanceOf(StreamInterface::class, $this->factory->createStreamFromResource($resource));
    }

    public function testCreateUri()
    {
        $this->assertInstanceOf(UriInterface::class, $this->factory->createUri('http://example.com'));
    }

    public function testCreateUploadedFile()
    {
        $file = __DIR__ . '/resources/file.txt';
        $stream = $this->factory->createStreamFromFile($file);
        $size = $stream->getSize();
        $uploadedFile = $this->factory->createUploadedFile($stream, $size);
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(HttpFactory::class, HttpFactory::instance());
    }

    public function testSetGetStrategies()
    {
        $strategies = [GuzzleFactory::class];
        HttpFactory::setStrategies($strategies);
        $this->assertEquals($strategies, HttpFactory::getStrategies());
    }
}
