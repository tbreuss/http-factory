<?php
declare(strict_types=1);

namespace Tebe\HttpFactory\Factory;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class SlimFactory implements FactoryInterface
{
    /**
     * Check whether Slim Http is available
     */
    public static function isInstalled(): bool
    {
        return class_exists('Slim\\Http\\Environment')
            && class_exists('Slim\\Http\\Response')
            && class_exists('Slim\\Http\\Request')
            && class_exists('Slim\\Http\\Stream')
            && class_exists('Slim\\Http\\Uri');
    }

    /**
     * @inheritdoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response($code);

        return $reasonPhrase !== '' ? $response->withStatus($code, $reasonPhrase) : $response;
    }

    /**
     * @inheritdoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new Request(
            $method,
            is_string($uri) ? $this->createUri($uri) : $uri,
            new Headers(),
            [],
            $serverParams,
            $this->createStream()
        );
    }

    /**
     * @inheritdoc
     */
    public function createServerRequestFromGlobals(): ServerRequestInterface
    {
        $environment = Environment::mock();
        return Request::createFromEnvironment($environment);
    }

    /**
     * @inheritdoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return Uri::createFromString($uri);
    }

    /**
     * @inheritdoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = $this->createStreamFromFile('php://temp', 'r+');
        $stream->write($content);

        return $stream;
    }

    /**
     * @inheritdoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->createStreamFromResource(fopen($filename, $mode));
    }

    /**
     * @inheritdoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
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
        if ($size === null) {
            $size = $stream->getSize();
        }
        $meta = $stream->getMetadata();
        $file = $meta['uri'];
        if ($file === 'php://temp') {
            // Slim needs an actual path to the file
            $file = tempnam(sys_get_temp_dir(), 'factory-test');
            file_put_contents($file, $stream->getContents());
        }
        return new UploadedFile($file, $clientFilename, $clientMediaType, $size, $error);
    }
}
