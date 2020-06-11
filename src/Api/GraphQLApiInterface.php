<?php

namespace bbaga\BuildkiteApi\Api;

use Psr\Http\Message\ResponseInterface;

interface GraphQLApiInterface
{
    public function getResponseBody(ResponseInterface $response): array;

    public function post(string $query = '{}', string $variables = '{}'): ResponseInterface;
}
