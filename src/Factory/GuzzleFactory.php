<?php
declare(strict_types=1);

namespace Tebe\HttpFactory\Factory;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\UploadedFile;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class GuzzleFactory implements FactoryInterface
{
    /**
     * Check whether Guzzle Http is available
     */
    public static function isInstalled(): bool
    {
        return class_exists('GuzzleHttp\\Psr7\\Response')
            && class_exists('GuzzleHttp\\Psr7\\ServerRequest')
            && class_exists('GuzzleHttp\\Psr7\\Stream')
            && class_exists('GuzzleHttp\\Psr7\\Uri');
    }

    /**
     * @inheritdoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, [], null, '1.1', $reasonPhrase);
    }

    /**
     * @inheritdoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }

    /**
     * @inheritdoc
     */
    public function createServerRequestFromGlobals(): ServerRequestInterface
    {
        return ServerRequest::fromGlobals();
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
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
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
        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
