<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApi;

final class Emoji
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug): array
    {
        $response = $this->api->get(sprintf('organizations/%s/emojis', $organizationSlug));

        return $this->api->getResponseBody($response);
    }
}
