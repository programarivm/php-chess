<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\Bishop;
use Chess\Tests\AbstractUnitTestCase;

class BishopTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function travel_a2()
    {
        $bishop = new Bishop('w', 'a2');
        $travel = (object) [
            'upLeft' => [],
            'upRight' => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            'bottomLeft' => [],
            'bottomRight' => ['b1']
        ];

        $this->assertEquals($travel, $bishop->getTravel());
    }

    /**
     * @test
     */
    public function travel_d5()
    {
        $bishop = new Bishop('w', 'd5');
        $travel = (object) [
            'upLeft' => ['c6', 'b7', 'a8'],
            'upRight' => ['e6', 'f7', 'g8'],
            'bottomLeft' => ['c4', 'b3', 'a2'],
            'bottomRight' => ['e4', 'f3', 'g2', 'h1']
        ];

        $this->assertEquals($travel, $bishop->getTravel());
    }

    /**
     * @test
     */
    public function travel_a8()
    {
        $bishop = new Bishop('w', 'a8');
        $travel = (object) [
            'upLeft' => [],
            'upRight' => [],
            'bottomLeft' => [],
            'bottomRight' => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1']
        ];

        $this->assertEquals($travel, $bishop->getTravel());
    }
}
