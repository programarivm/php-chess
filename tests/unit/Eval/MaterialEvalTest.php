<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\MaterialEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class MaterialEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function starting_position()
    {
        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $board = new Board();
        $result = (new MaterialEval($board))->result;

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedResult = [
            'w' => 35.73,
            'b' => 34.73,
        ];

        $expectedExplanation = [
            "White has a slight material advantage.",
        ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/opening/A59.pgn');
        $board = (new SanPlay($A59))->validate()->board;
        $materialEval = new MaterialEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $materialEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $materialEval->explain());
    }

    /**
     * @test
     */
    public function C60()
    {
        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $C60 = file_get_contents(self::DATA_FOLDER.'/opening/C60.pgn');
        $board = (new SanPlay($C60))->validate()->board;
        $result = (new MaterialEval($board))->result;

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function C00()
    {
        $expectedResult = [
            'w' => 39.06,
            'b' => 40.06,
        ];

        $expectedExplanation = [
            "Black has a slight material advantage.",
        ];

        $C00 = file_get_contents(self::DATA_FOLDER.'/opening/C00.pgn');
        $board = (new SanPlay($C00))->validate()->board;
        $materialEval = new MaterialEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $materialEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $materialEval->explain());
    }
}
