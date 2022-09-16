<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\StartPosition;

class StartPositionTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $arr = (new StartPosition())->create();

        $this->assertNotEmpty($arr);
    }
}
