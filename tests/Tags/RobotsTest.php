<?php

namespace Juampi92\TestSEO\Tests\DTOs;

use Juampi92\TestSEO\Tags\Robots;
use PHPUnit\Framework\TestCase;

class RobotsTest extends TestCase
{
    public function test_no_index_no_follow(): void
    {
        $robots = new Robots(' noindex , nofollow ');

        $this->assertTrue($robots->noindex());
        $this->assertFalse($robots->index());
        $this->assertTrue($robots->nofollow());
        $this->assertFalse($robots->follow());
    }

    public function test_none(): void
    {
        $robots = new Robots(' none ');

        $this->assertTrue($robots->noindex());
        $this->assertFalse($robots->index());
        $this->assertTrue($robots->nofollow());
        $this->assertFalse($robots->follow());
    }

    public function test_empty_on_invalid(): void
    {
        $robots = new Robots(' invalid ');

        $this->assertNull($robots->noindex());
        $this->assertNull($robots->index());
        $this->assertNull($robots->nofollow());
        $this->assertNull($robots->follow());
    }

    public function test_others_match(): void
    {
        $robots = new Robots('noimageindex,noarchive,nocache,nosnippet');

        $this->assertTrue($robots->noimageindex());
        $this->assertTrue($robots->noarchive());
        $this->assertTrue($robots->nocache());
        $this->assertTrue($robots->nosnippet());
    }

    public function test_others_dont_match(): void
    {
        $robots = new Robots('index');

        $this->assertFalse($robots->noimageindex());
        $this->assertFalse($robots->noarchive());
        $this->assertFalse($robots->nocache());
        $this->assertFalse($robots->nosnippet());
    }

    public function test_empty(): void
    {
        $robots = new Robots('');

        $this->assertTrue($robots->isEmpty());
        $this->assertNull($robots->index());
        $this->assertNull($robots->follow());
    }
}
