<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\Q;

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
    public function flow_a2()
    {
        $queen = new Q('w', 'a2', self::$square);
        $flow = [
            0 => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            1 => ['a1'],
            2 => [],
            3 => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
            4 => [],
            5 => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            6 => [],
            7 => ['b1'],
        ];

        $this->assertEquals($flow, $queen->flow);
    }

    /**
     * @test
     */
    public function flow_d5()
    {
        $queen = new Q('w', 'd5', self::$square);
        $flow = [
            0 => ['d6', 'd7', 'd8'],
            1 => ['d4', 'd3', 'd2', 'd1'],
            2 => ['c5', 'b5', 'a5'],
            3 => ['e5', 'f5', 'g5', 'h5'],
            4 => ['c6', 'b7', 'a8'],
            5 => ['e6', 'f7', 'g8'],
            6 => ['c4', 'b3', 'a2'],
            7 => ['e4', 'f3', 'g2', 'h1'],
        ];

        $this->assertEquals($flow, $queen->flow);
    }

    /**
     * @test
     */
    public function legal_sqs_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/opening/A74.pgn');

        $board = (new SanPlay($A74))->validate()->board;

        $queen = $board->pieceBySq('d1');

        $expected = [ 'd2', 'd3', 'd4', 'e1', 'c2', 'b3' ];

        $this->assertSame($expected, $queen->moveSqs());
    }
}
