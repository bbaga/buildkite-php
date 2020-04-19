<?php
declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest;

final class EmojiTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testList(): void
    {
        $this->assertNotEmpty($this->api->emoji()->list($this->organization));
    }
}
