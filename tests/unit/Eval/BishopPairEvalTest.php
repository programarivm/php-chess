<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BishopPairEval;
use Chess\Play\SAN;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class BishopPairEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function B25()
    {
        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');

        $board = (new SAN($B25))->play()->getBoard();

        $bishopPairEval = (new BishopPairEval($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $bishopPairEval);
    }

    /**
     * @test
     */
    public function C68()
    {
        $C68 = file_get_contents(self::DATA_FOLDER.'/sample/C68.pgn');

        $board = (new SAN($C68))->play()->getBoard();

        $bishopPairEval = (new BishopPairEval($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $this->assertSame($expected, $bishopPairEval);
    }

    /**
     * @test
     */
    public function B_B_vs_b_b()
    {
        $board = (new StrToBoard('8/5b2/4k3/4b3/8/8/1KBB4/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absForkEval = (new BishopPairEval($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }

    /**
     * @test
     */
    public function B_B_vs_n_b()
    {
        $board = (new StrToBoard('8/5n2/4k3/4b3/8/8/1KBB4/8 w - -'))
            ->create();

        $expected = [
            'w' => 1,
            'b' => 0,
        ];

        $absForkEval = (new BishopPairEval($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }

    /**
     * @test
     */
    public function N_B_vs_b_b()
    {
        $board = (new StrToBoard('8/3k4/2bb4/8/8/4BN2/4K3/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $absForkEval = (new BishopPairEval($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }
}
