<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\CenterEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class CenterEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function B25()
    {
        $expectedEval = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $expectedPhrase = [
            "White has a somewhat better control of the center.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->getBoard();
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedEval, $centerEval->getResult());
        $this->assertSame($expectedPhrase, $centerEval->getPhrases());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedEval = [
            'w' => 47.0,
            'b' => 36.8,
        ];

        $expectedPhrase = [
            "White has an absolute control of the center.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');
        $board = (new SanPlay($B56))->validate()->getBoard();
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedEval, $centerEval->getResult());
        $this->assertSame($expectedPhrase, $centerEval->getPhrases());
    }

    /**
     * @test
     */
    public function C60()
    {
        $expectedEval = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $expectedPhrase = [
            "White has a somewhat better control of the center.",
        ];

        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');
        $board = (new SanPlay($C60))->validate()->getBoard();
        $centerEval = new CenterEval($board);

        $this->assertSame($expectedEval, $centerEval->getResult());
        $this->assertSame($expectedPhrase, $centerEval->getPhrases());
    }
}
