<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

final class GraphQLApi extends AbstractApi implements GraphQLApiInterface
{
    public const BASE_URI = 'https://graphql.buildkite.com/v1/';

    public function post(string $query = '{}', string $variables = '{}'): ResponseInterface
    {
        $request = (new Request('POST', $this->uri))
            ->withBody(stream_for(json_encode(['query' => $query, 'variables' => $variables])));

        return $this->client->sendRequest($this->addHeaders($request));
    }
}
