<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Organizations
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @param RestApiInterface $api
     * @param Organization[] $organizations
     */
    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    /** @return Organization[] */
    public function get(): array
    {
        $organizations = $this->api->getResponseBody($this->api->get('organizations'));

        $list = [];

        /** @var array $organization */
        foreach ($organizations as $organization) {
            $list[] = new Organization($this->api, $organization);
        }

        return $list;
    }
}
