<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\RelativePinEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class RelativePinEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $result = (new RelativePinEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 5.47,
        ];

        $expectedPhrase = [
            "the knight on f3 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->getResult());
        $this->assertSame($expectedPhrase, $relativePinEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_rook_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 3.7,
            'b' => 0,
        ];

        $expectedPhrase = [
            "the knight on e6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4q1k1/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->getResult());
        $this->assertSame($expectedPhrase, $relativePinEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 5.47,
            'b' => 0,
        ];

        $expectedPhrase = [
            "the knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4q1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->getResult());
        $this->assertSame($expectedPhrase, $relativePinEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_rook()
    {
        $expectedResult = [
            'w' => 1.77,
            'b' => 0,
        ];

        $expectedPhrase = [
            "the knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->getResult());
        $this->assertSame($expectedPhrase, $relativePinEval->getPhrases());
    }

    /**
     * @test
     */
    public function pinnig_bishop_pinned_knight_shielded_rook_and_attacked_rock()
    {
        $expectedResult = [
            'w' => 1.77,
            'b' => 0,
        ];

        $expectedPhrase = [
            "the knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B2R4/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->getResult());
        $this->assertSame($expectedPhrase, $relativePinEval->getPhrases());
    }
}
