<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\PressureEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class PressureEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expected = [
            'w' => [],
            'b' => [],
        ];

        $result = (new PressureEval(new Board()))->result;

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['c3'],
        ];

        $expectedExplanation = [
            "The black player is pressuring more squares than its opponent.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/opening/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $pressureEval = new PressureEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $pressureEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $pressureEval->explain());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedResult = [
            'w' => ['c6'],
            'b' => ['d4', 'e4'],
        ];

        $expectedExplanation = [
            "The black player is pressuring more squares than its opponent.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/opening/B56.pgn');
        $board = (new SanPlay($B56))->validate()->board;
        $pressureEval = new PressureEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $pressureEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $pressureEval->explain());
    }

    /**
     * @test
     */
    public function C67()
    {
        $expected = [
            'w' => ['c6', 'e5'],
            'b' => ['d2', 'f2'],
        ];

        $C67 = file_get_contents(self::DATA_FOLDER.'/opening/C67.pgn');
        $board = (new SanPlay($C67))->validate()->board;
        $result = (new PressureEval($board))->result;

        $this->assertEqualsCanonicalizing($expected['w'], $result['w']);
        $this->assertEqualsCanonicalizing($expected['b'], $result['b']);
    }
}
