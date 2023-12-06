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

        $result = (new PressureEval(new Board()))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedEval = [
            'w' => [],
            'b' => ['c3'],
        ];

        $expectedPhrase = [
            "Black is pressuring a little bit more squares than its opponent.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->getBoard();
        $pressureEval = new PressureEval($board);

        $this->assertEqualsCanonicalizing($expectedEval, $pressureEval->getResult());
        $this->assertEqualsCanonicalizing($expectedPhrase, $pressureEval->getPhrases());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedEval = [
            'w' => ['c6'],
            'b' => ['d4', 'e4'],
        ];

        $expectedPhrase = [
            "Black is pressuring a little bit more squares than its opponent.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');
        $board = (new SanPlay($B56))->validate()->getBoard();
        $pressureEval = new PressureEval($board);

        $this->assertEqualsCanonicalizing($expectedEval, $pressureEval->getResult());
        $this->assertEqualsCanonicalizing($expectedPhrase, $pressureEval->getPhrases());
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

        $C67 = file_get_contents(self::DATA_FOLDER.'/sample/C67.pgn');
        $board = (new SanPlay($C67))->validate()->getBoard();
        $result = (new PressureEval($board))->getResult();

        $this->assertEqualsCanonicalizing($expected['w'], $result['w']);
        $this->assertEqualsCanonicalizing($expected['b'], $result['b']);
    }
}
