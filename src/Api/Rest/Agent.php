<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Agent
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
        $response = $this->api->get(
            sprintf('organizations/%s/agents', $organizationSlug),
            ['query' => $queryParameters]
        );

        return $this->api->getResponseBody($response);
    }

    public function get(string $organizationSlug, string $agentId): array
    {
        $uri = sprintf('organizations/%s/agents/%s', $organizationSlug, $agentId);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function stop(string $organizationSlug, string $agentId, bool $force = true): array
    {
        $uri = sprintf('organizations/%s/agents/%s/stop', $organizationSlug, $agentId);
        $response = $this->api->put($uri, ['force' => $force]);

        return $this->api->getResponseBody($response);
    }
}
