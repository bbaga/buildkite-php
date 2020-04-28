<?php

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

interface BuildInterface
{
    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return string
     */
    public function getWebUrl(): string;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return bool
     */
    public function isBlocked(): bool;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getCommit(): string;

    /**
     * @return string
     */
    public function getBranch(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return array
     */
    public function getEnv(): array;

    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @return User
     */
    public function getCreator(): User;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getScheduledAt(): string;

    /**
     * @return string
     */
    public function getStartedAt(): string;

    /**
     * @return string
     */
    public function getFinishedAt(): string;

    /**
     * @return array
     */
    public function getMetaData(): array;

    /**
     * @return array
     */
    public function getPullRequest(): array;

    /**
     * @return Pipeline
     */
    public function getPipeline(): Pipeline;

    /**
     * @return string
     */
    public function getPipelineSlug(): string;

    /**
     * @return Organization
     */
    public function getOrganization(): Organization;

    /**
     * @return string
     */
    public function getOrganizationSlug(): string;

    /**
     * @return Job[]
     */
    public function getJobs(): array;

    /**
     * @return Annotation[]
     */
    public function getAnnotations(array $queryParameters = []): array;

    /**
     * @return Artifact[]
     */
    public function getArtifacts(array $queryParameters = []): array;

    public function cancel(): BuildInterface;

    public function rebuild(): BuildInterface;

    public function fetch(): BuildInterface;
}
