<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\CenterEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class CenterEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expectedResult = [
            'w' => 29.4,
            'b' => 33.0,
        ];

        $expectedExplanation = [
            "Black has a slightly better control of the center.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/opening/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedResult = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $expectedExplanation = [
            "White has a slightly better control of the center.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/opening/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedResult = [
            'w' => 47.0,
            'b' => 36.8,
        ];

        $expectedExplanation = [
            "White is totally controlling the center.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/opening/B56.pgn');
        $board = (new SanPlay($B56))->validate()->board;
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function C60()
    {
        $expectedResult = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $expectedExplanation = [
            "White has a slightly better control of the center.",
        ];

        $C60 = file_get_contents(self::DATA_FOLDER.'/opening/C60.pgn');
        $board = (new SanPlay($C60))->validate()->board;
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }
}
