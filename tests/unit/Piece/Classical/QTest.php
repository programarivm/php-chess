<?php

namespace Chess\Tests\Unit\Piece\Classical;

use Chess\Piece\Classical\Q;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;

class QTest extends AbstractUnitTestCase
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
        $queen = new Q('w', 'a2', self::$square);
        $mobility = [
            'up' => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'down' => ['a1'],
            'left' => [],
            'right' => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
            'upLeft' => [],
            'upRight' => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            'downLeft' => [],
            'downRight' => ['b1']
        ];

        $this->assertEquals($mobility, $queen->mobility);
    }

    /**
     * @test
     */
    public function mobility_d5()
    {
        $queen = new Q('w', 'd5', self::$square);
        $mobility = [
            'up' => ['d6', 'd7', 'd8'],
            'down' => ['d4', 'd3', 'd2', 'd1'],
            'left' => ['c5', 'b5', 'a5'],
            'right' => ['e5', 'f5', 'g5', 'h5'],
            'upLeft' => ['c6', 'b7', 'a8'],
            'upRight' => ['e6', 'f7', 'g8'],
            'downLeft' => ['c4', 'b3', 'a2'],
            'downRight' => ['e4', 'f3', 'g2', 'h1']
        ];

        $this->assertEquals($mobility, $queen->mobility);
    }

    /**
     * @test
     */
    public function legal_sqs_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = (new SanPlay($A74))->validate()->board;

        $queen = $board->pieceBySq('d1');

        $expected = [ 'd2', 'd3', 'd4', 'e1', 'c2', 'b3' ];

        $this->assertSame($expected, $queen->legalSqs());
    }
}
