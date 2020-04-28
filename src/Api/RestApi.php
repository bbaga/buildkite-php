<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api;

use bbaga\BuildkiteApi\Api\Rest\Agent;
use bbaga\BuildkiteApi\Api\Rest\AgentInterface;
use bbaga\BuildkiteApi\Api\Rest\Annotation;
use bbaga\BuildkiteApi\Api\Rest\AnnotationInterface;
use bbaga\BuildkiteApi\Api\Rest\Artifact;
use bbaga\BuildkiteApi\Api\Rest\ArtifactInterface;
use bbaga\BuildkiteApi\Api\Rest\Build;
use bbaga\BuildkiteApi\Api\Rest\BuildInterface;
use bbaga\BuildkiteApi\Api\Rest\Emoji;
use bbaga\BuildkiteApi\Api\Rest\EmojiInterface;
use bbaga\BuildkiteApi\Api\Rest\Job;
use bbaga\BuildkiteApi\Api\Rest\Organization;
use bbaga\BuildkiteApi\Api\Rest\OrganizationInterface;
use bbaga\BuildkiteApi\Api\Rest\Pipeline;
use bbaga\BuildkiteApi\Api\Rest\PipelineInterface;
use bbaga\BuildkiteApi\Api\Rest\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;
use function is_array;

final class RestApi implements RestApiInterface
{
    /**
     * @var HttpClientInterface
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

    public const BASE_URI = 'https://api.buildkite.com/v2/';

    /**
     * @param HttpClientInterface $client
     * @param string $accessToken Buildkite API Access Token
     * @param string $uri Buildkite API uri
     */
    public function __construct(HttpClientInterface $client, string $accessToken, string $uri = self::BASE_URI)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
        $this->uri = $uri;
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

    public function get(string $resource, array $options = []): ResponseInterface
    {
        $request = $this->client->createRequest('GET', sprintf('%s%s', $this->uri, $resource));

        return $this->client->send($this->addAuthorizationHeader($request), $options);
    }

    private function addAuthorizationHeader(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->accessToken));
    }

    public function post(string $resource, array $body = []): ResponseInterface
    {
        $request = $this->client->createRequest('POST', sprintf('%s%s', $this->uri, $resource))
            ->withBody(stream_for(json_encode($body)));

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function patch(string $resource, array $body = []): ResponseInterface
    {
        $request = $this->client->createRequest('PATCH', sprintf('%s%s', $this->uri, $resource))
            ->withBody(stream_for(json_encode($body)));

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function put(string $resource, array $body = []): ResponseInterface
    {
        $request = $this->client->createRequest('PUT', sprintf('%s%s', $this->uri, $resource))
            ->withBody(stream_for(json_encode($body)));

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function delete(string $resource): ResponseInterface
    {
        $request = $this->client->createRequest('DELETE', sprintf('%s%s', $this->uri, $resource));

        return $this->client->send($this->addAuthorizationHeader($request));
    }

    public function organization(): OrganizationInterface
    {
        return new Organization($this);
    }

    public function pipeline(): PipelineInterface
    {
        return new Pipeline($this);
    }

    public function build(): BuildInterface
    {
        return new Build($this);
    }

    public function user(): User
    {
        return new User($this);
    }

    public function emoji(): EmojiInterface
    {
        return new Emoji($this);
    }

    public function annotation(): AnnotationInterface
    {
        return new Annotation($this);
    }

    public function artifact(): ArtifactInterface
    {
        return new Artifact($this);
    }

    public function job(): Job
    {
        return new Job($this);
    }

    public function agent(): AgentInterface
    {
        return new Agent($this);
    }
}
