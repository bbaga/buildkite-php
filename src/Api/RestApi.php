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
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;
use function is_array;

final class RestApi implements RestApiInterface
{
    /**
     * @var ClientInterface
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
     * @param ClientInterface $client
     * @param string $accessToken Buildkite API Access Token
     * @param string $uri Buildkite API uri
     */
    public function __construct(ClientInterface $client, string $accessToken, string $uri = self::BASE_URI)
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
        $query = '';

        if (isset($options['query'])) {
            if (!\is_array($options['query'])) {
                throw new \InvalidArgumentException('query must be an array');
            }

            $query = '?' . \http_build_query(
                $options['query'],
                '',
                '&',
                PHP_QUERY_RFC3986
            );
        }

        $request = new Request('GET', sprintf('%s%s%s', $this->uri, $resource, $query));

        return $this->client->sendRequest(
            $this->addHeaders($request)
        );
    }

    private function addHeaders(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->accessToken))
            ->withHeader('Accept', 'application/json');
    }

    public function post(string $resource, array $body = []): ResponseInterface
    {
        $options = count($body) === 0 ? JSON_FORCE_OBJECT : 0;
        $request = (new Request('POST', sprintf('%s%s', $this->uri, $resource)))
            ->withBody(stream_for(json_encode($body, $options)));

        return $this->client->sendRequest($this->addHeaders($request));
    }

    public function patch(string $resource, array $body = []): ResponseInterface
    {
        $options = count($body) === 0 ? JSON_FORCE_OBJECT : 0;
        $request = (new Request('PATCH', sprintf('%s%s', $this->uri, $resource)))
            ->withBody(stream_for(json_encode($body, $options)));

        return $this->client->sendRequest($this->addHeaders($request));
    }

    public function put(string $resource, array $body = []): ResponseInterface
    {
        $options = count($body) === 0 ? JSON_FORCE_OBJECT : 0;
        $request = (new Request('PUT', sprintf('%s%s', $this->uri, $resource)))
            ->withBody(stream_for(json_encode($body, $options)));

        return $this->client->sendRequest($this->addHeaders($request));
    }

    public function delete(string $resource): ResponseInterface
    {
        $request = new Request('DELETE', sprintf('%s%s', $this->uri, $resource));

        return $this->client->sendRequest($this->addHeaders($request));
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
