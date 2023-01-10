<?php
declare(strict_types=1);

namespace Tebe\HttpFactory;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Tebe\HttpFactory\Factory\DiactorosFactory;
use Tebe\HttpFactory\Factory\FactoryInterface;
use Tebe\HttpFactory\Factory\GuzzleFactory;
use Tebe\HttpFactory\Factory\NyholmFactory;
use Tebe\HttpFactory\Factory\SlimFactory;

/**
 * Simple class to create instances of PSR-7 classes.
 */
class HttpFactory implements FactoryInterface
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private static $strategies = [
        DiactorosFactory::class,
        GuzzleFactory::class,
        SlimFactory::class,
        NyholmFactory::class
    ];

    /**
     * @param FactoryInterface $factory
     * @return HttpFactory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        if ($this->factory !== null) {
            return $this->factory;
        }

        foreach (self::$strategies as $className) {
            if (!class_exists($className)) {
                continue;
            }

            if (strpos($className, __NAMESPACE__) === 0 && !$className::isInstalled()) {
                continue;
            }

            return $this->factory = new $className();
        }

        throw new RuntimeException('No PSR-7 library detected');
    }

    /**
     * @inheritdoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return $this->getFactory()->createResponse($code, $reasonPhrase);
    }

    /**
     * @inheritdoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return $this->getFactory()->createServerRequest($method, $uri, $serverParams);
    }

    /**
     * @inheritdoc
     */
    public function createServerRequestFromGlobals() : ServerRequestInterface
    {
        return $this->getFactory()->createServerRequestFromGlobals();
    }

    /**
     * @inheritdoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return $this->getFactory()->createStream($content);
    }

    /**
     * @inheritdoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->getFactory()->createStreamFromFile($filename, $mode);
    }

    /**
     * @inheritdoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return $this->getFactory()->createStreamFromResource($resource);
    }

    /**
     * @inheritdoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return $this->getFactory()->createUri($uri);
    }

    /**
     * @inheritdoc
     */
    public function createUploadedFile(
        StreamInterface $stream,
        int $size = null,
        int $error = \UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface {
        return $this->getFactory()->createUploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }

    /**
     * @return static
     */
    public static function instance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * @param array $strategies
     */
    public static function setStrategies(array $strategies)
    {
        static::$strategies = $strategies;
    }

    /**
     * @return array
     */
    public static function getStrategies()
    {
        return static::$strategies;
    }
}
