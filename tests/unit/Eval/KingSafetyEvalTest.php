<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\KingSafetyEval;
use Chess\Eval\PressureEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class KingSafetyEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $board = new Board();
        $pressureEval = new PressureEval($board);
        $kingSafetyEval = new KingSafetyEval($board, $pressureEval);

        $this->assertSame($expectedResult, $kingSafetyEval->getResult());
    }

    /**
     * @test
     */
    public function A00()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The black pieces are timidly approaching the other side's king.",
        ];

        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');
        $board = (new SanPlay($A00))->validate()->board;
        $pressureEval = new PressureEval($board);
        $kingSafetyEval = new KingSafetyEval($board, $pressureEval);

        $this->assertSame($expectedResult, $kingSafetyEval->getResult());
        $this->assertSame($expectedExplanation, $kingSafetyEval->getExplanation());
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $pressureEval = new PressureEval($board);
        $kingSafetyEval = new KingSafetyEval($board, $pressureEval);

        $this->assertSame($expectedResult, $kingSafetyEval->getResult());
    }
}
