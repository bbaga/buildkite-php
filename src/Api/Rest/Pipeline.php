<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Pipeline implements PipelineInterface
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug, array $queryParameters = []): array
    {
        $response = $this->api->get(sprintf('organizations/%s/pipelines', $organizationSlug), ['query' => $queryParameters]);

        return $this->api->getResponseBody($response);
    }

    public function get(string $organizationSlug, string $pipelineSlug): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function create(string $organizationSlug, array $body): array
    {
        $uri = sprintf('organizations/%s/pipelines', $organizationSlug);
        $response = $this->api->post($uri, $body);

        return $this->api->getResponseBody($response);
    }

    public function update(string $organizationSlug, string $pipelineSlug, array $body): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $response = $this->api->patch($uri, $body);

        return $this->api->getResponseBody($response);
    }

    public function delete(string $organizationSlug, string $pipelineSlug): void
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $this->api->delete($uri);
    }
}
