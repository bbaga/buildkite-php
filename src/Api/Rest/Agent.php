<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Agent implements AgentInterface
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

    public function stop(string $organizationSlug, string $agentId, bool $force = true): void
    {
        $uri = sprintf('organizations/%s/agents/%s/stop', $organizationSlug, $agentId);
        $this->api->put($uri, ['force' => $force]);
    }
}
