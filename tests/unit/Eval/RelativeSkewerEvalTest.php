<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\RelativeSkewerEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class RelativeSkewerEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_rook_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('4qk2/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_rook_pinned_knight_shielded_queen_skewer()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedPhrase = [
            "The knight on e6 is under attack because it is shielding a piece that is unprotected.",
        ];

        $board = (new StrToBoard('4q1k1/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('4qk2/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_queen_skewer()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedPhrase = [
            "The knight on c6 is under attack because it is shielding a piece that is unprotected.",
        ];

        $board = (new StrToBoard('4q1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_rook()
    {
        $expectedResult = [
            'w' => 1.9,
            'b' => 0,
        ];

        $expectedPhrase = [
            "The knight on c6 is under attack because it is shielding a piece that is unprotected.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new RelativeSkewerEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }
}
