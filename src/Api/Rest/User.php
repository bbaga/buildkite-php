<?php
declare(strict_types=1);

namespace BuildkiteApi\Api\Rest;

use BuildkiteApi\Api\RestApi;

final class User
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function whoami(): array
    {
        $response = $this->api->get('user');

        return $this->api->getResponseBody($response);
    }
}
