<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\CapablancaFischer\StartPosition;

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
