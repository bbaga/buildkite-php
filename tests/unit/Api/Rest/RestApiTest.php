<?php
declare(strict_types=1);

namespace PHPUnit\Util;

use PHPUnit\Framework\TestCase;

final class RestApiTest extends TestCase
{
    public function test(): void
    {
        $this->assertSame(1,1);
    }
}
