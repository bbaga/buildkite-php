<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class User
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    public function whoami(): array
    {
        $response = $this->api->get('user');

        return $this->api->getResponseBody($response);
    }
}
