<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Annotations
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Annotation[]
     */
    private $annotations;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * @var string
     */
    private $pipelineSlug;

    /**
     * @var int
     */
    private $buildNumber;

    /**
     * @param RestApiInterface $api
     * @param string $organizationSlug
     * @param string $pipelineSlug
     * @param int $buildNumber
     * @param Annotation[] $annotations
     */
    public function __construct(RestApiInterface $api, string $organizationSlug, string $pipelineSlug, int $buildNumber, array $annotations = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;
        $this->pipelineSlug = $pipelineSlug;
        $this->buildNumber = $buildNumber;
        $this->annotations = $annotations;
    }

    /**
     * @param array $queryParameters
     * @return Annotation[]
     */
    public function get(array $queryParameters = []): array
    {
        if (count($this->annotations) === 0) {
            $this->annotations = $this->fetch($queryParameters);
        }

        return $this->annotations;
    }

    /**
     * @param array $queryParameters
     * @return Annotation[]
     */
    public function fetch(array $queryParameters = []): array
    {
        $api = $this->api->annotation();
        $annotations = $api->list($this->organizationSlug, $this->pipelineSlug, $this->buildNumber, $queryParameters);

        $list = [];

        /** @var array $annotation */
        foreach ($annotations as $annotation) {
            $list[] = new Annotation($annotation);
        }

        $this->annotations = $list;

        return $list;
    }
}
