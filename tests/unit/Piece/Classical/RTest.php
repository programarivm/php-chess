<?php

namespace Chess\Tests\Unit\Piece\Classical;

use Chess\Piece\RType;
use Chess\Piece\Classical\R;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;

class RTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function mobility_a2()
    {
        $rook = new R('w', 'a2', self::$square, RType::PROMOTED);
        $mobility = [
            'up' => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'down' => ['a1'],
            'left' => [],
            'right' => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2']
        ];

        $this->assertEquals($mobility, $rook->mobility);
    }

    /**
     * @test
     */
    public function mobility_d5()
    {
        $rook = new R('w', 'd5', self::$square, RType::PROMOTED);
        $mobility = [
            'up' => ['d6', 'd7', 'd8'],
            'down' => ['d4', 'd3', 'd2', 'd1'],
            'left' => ['c5', 'b5', 'a5'],
            'right' => ['e5', 'f5', 'g5', 'h5']
        ];

        $this->assertEquals($mobility, $rook->mobility);
    }
}
