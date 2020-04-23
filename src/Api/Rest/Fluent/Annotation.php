<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

final class Annotation
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $style;

    /**
     * @var string
     */
    private $bodyHtml;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    public function __construct(array $map = [])
    {
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
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @return string
     */
    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
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
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    private function populate(array $map): void
    {
        $this->id = (string)($map['id'] ?? '');
        $this->context = (string)($map['context'] ?? '');
        $this->style = (string)($map['style'] ?? '');
        $this->bodyHtml = (string)($map['body_html'] ?? '');
        $this->createdAt = (string)($map['created_at'] ?? '');
        $this->updatedAt = (string)($map['updated_at'] ?? '');
    }
}
