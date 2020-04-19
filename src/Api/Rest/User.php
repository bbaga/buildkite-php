<?php
declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApi;

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
