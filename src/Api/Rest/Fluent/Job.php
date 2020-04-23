<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;
use function is_string;

final class Job
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
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $stepKey;

    /**
     * @var array
     */
    private $agentQueryRules;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $buildUrl;

    /**
     * @var string
     */
    private $web_url;

    /**
     * @var string
     */
    private $log_url;

    /**
     * @var string
     */
    private $raw_log_url;

    /**
     * @var string
     */
    private $artifactsUrl;

    /**
     * @var string
     */
    private $command;

    /**
     * @var bool
     */
    private $softFailed;

    /**
     * @var int
     */
    private $exitStatus;

    /**
     * @var array
     */
    private $artifactPaths;

    /**
     * @var array
     */
    private $agent;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $scheduledAt;

    /**
     * @var string
     */
    private $runnableAt;

    /**
     * @var string
     */
    private $startedAt;

    /**
     * @var string
     */
    private $finishedAt;

    /**
     * @var bool
     */
    private $retried;

    /**
     * @var string
     */
    private $retriedInJobId;

    /**
     * @var int
     */
    private $retriesCount;

    /**
     * @var string
     */
    private $parallelGroupIndex;

    /**
     * @var int
     */
    private $parallelGroupTotal;

    /**
     * @var User
     */
    private $unblockedBy;

    /**
     * @var string
     */
    private $unblockedAt;

    /**
     * @var bool
     */
    private $unblockable;

    /**
     * @var string
     */
    private $unblockUrl;

    public function __construct(RestApiInterface $api, Build $build, array $map = [])
    {
        $this->api = $api;
        $this->build = $build;

        if (!isset($map['id']) || !is_string($map['id'])) {
            throw new \InvalidArgumentException(
                'The "id" (representing the job id) must be an string value'
            );
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStepKey(): string
    {
        return $this->stepKey;
    }

    /**
     * @return array
     */
    public function getAgentQueryRules(): array
    {
        return $this->agentQueryRules;
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
    public function getBuildUrl(): string
    {
        return $this->buildUrl;
    }

    /**
     * @return string
     */
    public function getWebUrl(): string
    {
        return $this->web_url;
    }

    /**
     * @return string
     */
    public function getLogUrl(): string
    {
        return $this->log_url;
    }

    /**
     * @return string
     */
    public function getRawLogUrl(): string
    {
        return $this->raw_log_url;
    }

    /**
     * @return string
     */
    public function getArtifactsUrl(): string
    {
        return $this->artifactsUrl;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return bool
     */
    public function isSoftFailed(): bool
    {
        return $this->softFailed;
    }

    /**
     * @return int
     */
    public function getExitStatus(): int
    {
        return $this->exitStatus;
    }

    /**
     * @return array
     */
    public function getArtifactPaths(): array
    {
        return $this->artifactPaths;
    }

    /**
     * @return array
     */
    public function getAgent(): array
    {
        return $this->agent;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getScheduledAt(): string
    {
        return $this->scheduledAt;
    }

    /**
     * @return string
     */
    public function getRunnableAt(): string
    {
        return $this->runnableAt;
    }

    /**
     * @return string
     */
    public function getStartedAt(): string
    {
        return $this->startedAt;
    }

    /**
     * @return string
     */
    public function getFinishedAt(): string
    {
        return $this->finishedAt;
    }

    /**
     * @return bool
     */
    public function isRetried(): bool
    {
        return $this->retried;
    }

    /**
     * @return string
     */
    public function getRetriedInJobId(): string
    {
        return $this->retriedInJobId;
    }

    /**
     * @return int
     */
    public function getRetriesCount(): int
    {
        return $this->retriesCount;
    }

    /**
     * @return string
     */
    public function getParallelGroupIndex(): string
    {
        return $this->parallelGroupIndex;
    }

    /**
     * @return int
     */
    public function getParallelGroupTotal(): int
    {
        return $this->parallelGroupTotal;
    }

    /**
     * @return User
     */
    public function getUnblockedBy(): User
    {
        return $this->unblockedBy;
    }

    /**
     * @return string
     */
    public function getUnblockedAt(): string
    {
        return $this->unblockedAt;
    }

    /**
     * @return bool
     */
    public function isUnblockable(): bool
    {
        return $this->unblockable;
    }

    /**
     * @return string
     */
    public function getUnblockUrl(): string
    {
        return $this->unblockUrl;
    }

    /**
     * @return Artifact[]
     */
    public function getArtifacts(): array
    {
        $result = $this->api->artifact()->getByJob(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId()
        );

        $artifacts = [];

        /** @var array $artifact */
        foreach ($result as $artifact) {
            $artifacts[] = new Artifact($this->api, $this->build, $artifact);
        }

        return $artifacts;
    }

    public function retry(): self
    {
        $result = $this->api->job()->retry(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId()
        );

        $this->populate($result);

        return $this;
    }

    public function unblock(array $fields = [], string $userId = null): self
    {
        $result = $this->api->job()->unblock(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId(),
            $fields,
            $userId
        );

        $this->populate($result);

        return $this;
    }

    public function getLogOutput(): array
    {
        return $this->api->job()->getLogOutput(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId()
        );
    }

    public function deleteLogOutput(): void
    {
        $this->api->job()->deleteLogOutput(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId()
        );
    }

    public function getEnvironmentVariables(): array
    {
        $result = $this->api->job()->getEnvironmentVariables(
            $this->build->getOrganizationSlug(),
            $this->build->getPipelineSlug(),
            $this->build->getNumber(),
            $this->getId()
        );

        /** @var array $result['env'] */
        return $result['env'] ?? [];
    }

    private function populate(array $map): void
    {
        $this->id = (string)($map['id'] ?? '');
        $this->type = (string)($map['type'] ?? '');
        $this->name = (string)($map['name'] ?? '');
        $this->stepKey = (string)($map['step_key'] ?? '');
        $this->agentQueryRules = (array)($map['agent_query_rules'] ?? []);
        $this->state = (string)($map['state'] ?? '');
        $this->buildUrl = (string)($map['build_url'] ?? '');
        $this->web_url = (string)($map['web_url'] ?? '');
        $this->log_url = (string)($map['log_url'] ?? '');
        $this->raw_log_url = (string)($map['raw_log_url'] ?? '');
        $this->artifactsUrl = (string)($map['artifacts_url'] ?? '');
        $this->command = (string)($map['command'] ?? '');
        $this->softFailed = (bool)($map['soft_failed'] ?? false);
        $this->exitStatus = (int)($map['exit_status'] ?? 0);
        $this->artifactPaths = (array)($map['artifact_paths'] ?? []);
        $this->agent = (array)($map['agent'] ?? []);
        $this->createdAt = (string)($map['created_at'] ?? '');
        $this->scheduledAt = (string)($map['scheduled_at'] ?? '');
        $this->runnableAt = (string)($map['runnable_at'] ?? '');
        $this->startedAt = (string)($map['started_at'] ?? '');
        $this->finishedAt = (string)($map['finished_at'] ?? '');
        $this->retried = (bool)($map['retried'] ?? false);
        $this->retriedInJobId = (string)($map['retried_in_job_id'] ?? '');
        $this->retriesCount = (int)($map['retries_count'] ?? 0);
        $this->parallelGroupIndex = (string)($map['parallel_group_index'] ?? '');
        $this->parallelGroupTotal = (int)($map['parallel_group_total'] ?? 0);
        $this->unblockedAt = (string)($map['unblocked_at'] ?? '');
        $this->unblockable = (bool)($map['unblockable'] ?? false);
        $this->unblockUrl = (string)($map['unblock_url'] ?? '');

        /** @var array|User $unblockedBy */
        $unblockedBy = $map['unblocked_by'] ?? [];

        if ($unblockedBy instanceof User) {
            $this->unblockedBy = $unblockedBy;
        } else {
            $this->unblockedBy = new User($this->api, (array)$unblockedBy);
        }
    }
}
