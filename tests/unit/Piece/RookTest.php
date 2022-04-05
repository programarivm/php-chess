<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;

class RookTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function travel_a2()
    {
        $rook = new Rook('w', 'a2', RookType::PROMOTED);
        $travel = (object) [
            'up' => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'bottom' => ['a1'],
            'left' => [],
            'right' => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2']
        ];

        $this->assertEquals($travel, $rook->getTravel());
    }

    /**
     * @test
     */
    public function travel_d5()
    {
        $rook = new Rook('w', 'd5', RookType::PROMOTED);
        $travel = (object) [
            'up' => ['d6', 'd7', 'd8'],
            'bottom' => ['d4', 'd3', 'd2', 'd1'],
            'left' => ['c5', 'b5', 'a5'],
            'right' => ['e5', 'f5', 'g5', 'h5']
        ];

        $this->assertEquals($travel, $rook->getTravel());
    }
}
