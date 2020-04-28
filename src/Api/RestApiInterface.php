<?php

namespace bbaga\BuildkiteApi\Api;

use bbaga\BuildkiteApi\Api\Rest\Agent;
use bbaga\BuildkiteApi\Api\Rest\AgentInterface;
use bbaga\BuildkiteApi\Api\Rest\Annotation;
use bbaga\BuildkiteApi\Api\Rest\AnnotationInterface;
use bbaga\BuildkiteApi\Api\Rest\Artifact;
use bbaga\BuildkiteApi\Api\Rest\ArtifactInterface;
use bbaga\BuildkiteApi\Api\Rest\Build;
use bbaga\BuildkiteApi\Api\Rest\BuildInterface;
use bbaga\BuildkiteApi\Api\Rest\Emoji;
use bbaga\BuildkiteApi\Api\Rest\EmojiInterface;
use bbaga\BuildkiteApi\Api\Rest\Job;
use bbaga\BuildkiteApi\Api\Rest\Organization;
use bbaga\BuildkiteApi\Api\Rest\OrganizationInterface;
use bbaga\BuildkiteApi\Api\Rest\Pipeline;
use bbaga\BuildkiteApi\Api\Rest\PipelineInterface;
use bbaga\BuildkiteApi\Api\Rest\User;
use Psr\Http\Message\ResponseInterface;

interface RestApiInterface
{
    public function getResponseBody(ResponseInterface $response): array;

    public function get(string $resource, array $options = []): ResponseInterface;

    public function post(string $resource, array $body = []): ResponseInterface;

    public function patch(string $resource, array $body = []): ResponseInterface;

    public function put(string $resource, array $body = []): ResponseInterface;

    public function delete(string $resource): ResponseInterface;

    public function organization(): OrganizationInterface;

    public function pipeline(): PipelineInterface;

    public function build(): BuildInterface;

    public function user(): User;

    public function emoji(): EmojiInterface;

    public function annotation(): AnnotationInterface;

    public function artifact(): ArtifactInterface;

    public function job(): Job;

    public function agent(): AgentInterface;
}
