<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\Knight;
use Chess\Tests\AbstractUnitTestCase;

class KnightTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function travel_d4()
    {
        $knight = new Knight('w', 'd4');
        $travel = [
            'c6',
            'b5',
            'b3',
            'c2',
            'e2',
            'f3',
            'f5',
            'e6'
        ];

        $this->assertSame($travel, $knight->getTravel());
    }

    /**
     * @test
     */
    public function travel_h1()
    {
        $knight = new Knight('w', 'h1');
        $travel = [
            'g3',
            'f2'
        ];

        $this->assertSame($travel, $knight->getTravel());
    }

    /**
     * @test
     */
    public function travel_b1()
    {
        $knight = new Knight('w', 'b1');
        $travel = [
            'a3',
            'd2',
            'c3'
        ];

        $this->assertSame($travel, $knight->getTravel());
    }
}
