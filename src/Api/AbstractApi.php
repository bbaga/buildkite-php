<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function is_array;

class AbstractApi
{
    public const BASE_URI = '';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param ClientInterface $client
     * @param string $accessToken Buildkite API Access Token
     * @param string $uri Buildkite API uri
     */
    public function __construct(ClientInterface $client, string $accessToken, string $uri = null)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
        $this->uri = $uri ?? (string) static::BASE_URI;
    }

    public function getResponseBody(ResponseInterface $response): array
    {
        /** @var mixed $data */
        $data = json_decode((string) $response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return is_array($data) ? $data : [$data];
    }

    protected function addHeaders(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->accessToken))
            ->withHeader('Accept', 'application/json');
    }
}
