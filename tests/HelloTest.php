<?php

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass InterestAccount\Hello
 */
class HelloTest extends TestCase
{
    protected $hello;

    /**
     * @covers ::world
     */
    public function testWorld()     {
        $this->hello = new InterestAccount\Hello();
        $this->assertSame('world', $this->hello->world());
    }
}
