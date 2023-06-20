<?php

namespace Chess\Tests\Unit\Eval\Material;

use Chess\Eval\ConnectivityEval;
use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class ConnectivityEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $connEval = (new ConnectivityEval(new Board()))->eval();

        $expected = [
            'w' => 20,
            'b' => 20,
        ];

        $this->assertSame($expected, $connEval);
    }

    /**
     * @test
     */
    public function C60()
    {
        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');

        $board = (new SAN($C60))->play()->getBoard();

        $expected = [
            'w' => 19,
            'b' => 23,
        ];

        $connEval = (new ConnectivityEval($board))->eval();

        $this->assertSame($expected, $connEval);
    }
}
