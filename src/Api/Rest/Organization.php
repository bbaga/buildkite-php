<?php
declare(strict_types=1);

namespace BuildkiteApiPhp\Api\Rest;

use BuildkiteApiPhp\Api\RestApi;

final class Organization
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function list(): array
    {
        return $this->api->getResponseBody($this->api->get('organizations'));
    }

    public function get(string $organizationSlug): array
    {
        $response = $this->api->get(sprintf('organizations/%s', $organizationSlug));

        return $this->api->getResponseBody($response);
    }
}
