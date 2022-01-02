<?php

namespace Chess\Tests\Unit;

use Chess\Grandmaster;
use Chess\Tests\AbstractUnitTestCase;

class GrandmasterTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_repsonse()
    {
        $response = (new Grandmaster())->response('');

        $this->assertNotEmpty($response);
    }

    /**
     * @test
     */
    public function b_repsonse()
    {
        $response = (new Grandmaster())->response('1.e4');

        $this->assertNotEmpty($response);
    }
}
