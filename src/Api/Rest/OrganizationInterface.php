<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface OrganizationInterface
{
    public function list(array $queryParameters = []): array;

    public function get(string $organizationSlug): array;
}
