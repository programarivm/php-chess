<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\CenterEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class CenterEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expectedResult = [
            'w' => 30.52,
            'b' => 33.9,
        ];

        $expectedExplanation = [
            "Black has a slightly better control of the center.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
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
            'w' => 39.06,
            'b' => 35.86,
        ];

        $expectedExplanation = [
            "White has a slightly better control of the center.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
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
            'w' => 48.2,
            'b' => 37.92,
        ];

        $expectedExplanation = [
            "White is totally controlling the center.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');
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
            'w' => 38.85,
            'b' => 35.86,
        ];

        $expectedExplanation = [
            "White has a slightly better control of the center.",
        ];

        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');
        $board = (new SanPlay($C60))->validate()->board;
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function a6()
    {
        $expectedResult = [
            'w' => 2.1,
            'b' => 1.1,
        ];

        $expectedExplanation = [
            'White has a slightly better control of the center.',
        ];

        $fen = '7k/8/P7/8/8/8/8/7K w - -';
        $board = FenToBoardFactory::create($fen);
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function a7()
    {
        $expectedResult = [
            'w' => 1.1,
            'b' => 1.1,
        ];

        $expectedExplanation = [
        ];

        $fen = '7k/8/P7/8/8/8/8/7K w - -';
        $board = FenToBoardFactory::create($fen);
        $board->play('w', 'a7');
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function h6()
    {
        $expectedResult = [
            'w' => 2.1,
            'b' => 1.0,
        ];

        $expectedExplanation = [
            'White has a slightly better control of the center.',
        ];

        $fen = 'k7/8/7P/8/8/8/8/K7 w - -';
        $board = FenToBoardFactory::create($fen);
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }

    /**
     * @test
     */
    public function h7()
    {
        $expectedResult = [
            'w' => 1.0,
            'b' => 1.0,
        ];

        $expectedExplanation = [
        ];

        $fen = 'k7/8/7P/8/8/8/8/K7 w - -';
        $board = FenToBoardFactory::create($fen);
        $board->play('w', 'h7');
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedResult, $centerEval->result);
        $this->assertSame($expectedExplanation, $centerEval->explain());
    }
}
