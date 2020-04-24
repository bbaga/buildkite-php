<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface AgentInterface
{
    public function list(string $organizationSlug, array $queryParameters = []): array;

    public function get(string $organizationSlug, string $agentId): array;

    public function stop(string $organizationSlug, string $agentId, bool $force = true): void;
}
