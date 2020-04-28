<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;
use function is_string;

final class Artifact
{
    /**
     * @var RestApiInterface
     */
    private $api;
    /**
     * @var Build
     */
    private $build;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $jobId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $downloadUrl;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $dirName;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $fileSize;

    /**
     * @var string
     */
    private $sha1sum;

    public function __construct(RestApiInterface $api, Build $build, array $map = [])
    {
        $this->api = $api;
        $this->build = $build;

        if (!isset($map['id']) || !is_string($map['id'])) {
            throw new \InvalidArgumentException('The "id" must be a string identifying the artifact');
        }

        if (!isset($map['job_id']) || !is_string($map['job_id'])) {
            throw new \InvalidArgumentException('The "job_id" must be a string identifying the job that produced the artifact');
        }

        $this->populate($map);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDirName(): string
    {
        return $this->dirName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getSha1sum(): string
    {
        return $this->sha1sum;
    }

    public function delete(): void
    {
        $this->api->artifact()->delete(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->jobId,
            $this->id
        );
    }

    private function populate(array $map): void
    {
        $this->id = (string)($map['id'] ?? '');
        $this->jobId = (string)($map['job_id'] ?? '');
        $this->url = (string)($map['url'] ?? '');
        $this->downloadUrl = (string)($map['download_url'] ?? '');
        $this->state = (string)($map['state'] ?? '');
        $this->path = (string)($map['path'] ?? '');
        $this->dirName = (string)($map['dirname'] ?? '');
        $this->fileName = (string)($map['filename'] ?? '');
        $this->mimeType = (string)($map['mime_type'] ?? '');
        $this->fileSize = (int)($map['file_size'] ?? '');
        $this->sha1sum = (string)($map['sha1sum'] ?? '');
    }
}
