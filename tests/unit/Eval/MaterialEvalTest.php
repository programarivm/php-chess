<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\MaterialEval;
use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class MaterialEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function starting_position()
    {
        $board = new Board();

        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $mtlEval = (new MaterialEval($board))->eval();

        $this->assertEquals($expected, $mtlEval);
    }

    /**
     * @test
     */
    public function A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SAN($A59))->play()->getBoard();

        $expected = [
            'w' => 35.73,
            'b' => 34.73,
        ];

        $mtlEval = (new MaterialEval($board))->eval();

        $this->assertSame($expected, $mtlEval);
    }

    /**
     * @test
     */
    public function C60()
    {
        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');

        $board = (new SAN($C60))->play()->getBoard();

        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $mtlEval = (new MaterialEval($board))->eval();

        $this->assertSame($expected, $mtlEval);
    }
}
