<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Emoji
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug): array
    {
        $response = $this->api->get(sprintf('organizations/%s/emojis', $organizationSlug));

        return $this->api->getResponseBody($response);
    }
}
