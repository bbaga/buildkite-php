<?php
declare(strict_types=1);

namespace BuildkiteApi\Api;

use BuildkiteApi\Api\Rest\Annotation;
use BuildkiteApi\Api\Rest\Artifact;
use BuildkiteApi\Api\Rest\Build;
use BuildkiteApi\Api\Rest\Job;
use BuildkiteApi\Api\Rest\Emoji;
use BuildkiteApi\Api\Rest\Organization;
use BuildkiteApi\Api\Rest\Pipeline;
use BuildkiteApi\Api\Rest\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function is_array;

final class RestApi
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $uri;

    /**
     * @param Client $client
     * @param string $accessToken Buildkite API Access Token
     * @param string $uri Buildkite API uri
     */
    public function __construct(Client $client, string $accessToken, string $uri = 'https://api.buildkite.com/v2/')
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
        $this->uri = $uri;
    }

    public function getResponseBody(ResponseInterface $response, callable $customHandler = null): array
    {
        if ($customHandler !== null) {
            /** @var mixed $data */
            $data = $customHandler($response);

            if (!is_array($data)) {
                throw new \RuntimeException('Return type of custom response handler must be array');
            }

            return $data;
        }

        /** @var mixed $data */
        $data = json_decode((string) $response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error_msg());
        }

        $data = is_array($data) ? $data : [$data];

        return $data;
    }

    public function get(string $resource, array $options = []): ResponseInterface
    {
        $request = new Request('GET', sprintf('%s%s', $this->uri, $resource));

        return $this->client->send($this->addAuthorizationHeader($request), $options);
    }

    private function addAuthorizationHeader(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->accessToken));
    }

    public function post(string $resource, array $body = []): ResponseInterface
    {
        $request = new Request(
            'POST',
            sprintf('%s%s', $this->uri, $resource),
            [],
            json_encode($body)
        );

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function patch(string $resource, array $body = []): ResponseInterface
    {
        $request = new Request(
            'PATCH',
            sprintf('%s%s', $this->uri, $resource),
            [],
            json_encode($body)
        );

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function put(string $resource, array $body = []): ResponseInterface
    {
        $request = new Request(
            'PUT',
            sprintf('%s%s', $this->uri, $resource),
            [],
            json_encode($body)
        );

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function delete(string $resource): ResponseInterface
    {
        $request = new Request('DELETE', sprintf('%s%s', $this->uri, $resource));

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function organization(): Organization
    {
        return new Organization($this);
    }

    public function pipeline(): Pipeline
    {
        return new Pipeline($this);
    }

    public function build(): Build
    {
        return new Build($this);
    }

    public function user(): User
    {
        return new User($this);
    }

    public function emoji(): Emoji
    {
        return new Emoji($this);
    }

    public function annotation(): Annotation
    {
        return new Annotation($this);
    }

    public function artifact(): Artifact
    {
        return new Artifact($this);
    }

    public function job(): Job
    {
        return new Job($this);
    }
}
