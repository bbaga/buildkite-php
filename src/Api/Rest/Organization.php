<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Organization
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    public function list(array $queryParameters = []): array
    {
        return $this->api->getResponseBody($this->api->get('organizations', ['query' => $queryParameters]));
    }

    public function get(string $organizationSlug): array
    {
        $response = $this->api->get(sprintf('organizations/%s', $organizationSlug));

        return $this->api->getResponseBody($response);
    }
}
