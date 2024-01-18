<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DefenseEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class DefenseEvalTest extends AbstractUnitTestCase
{
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

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function attacking_rook_with_a_knight_shielding_the_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('4qk2/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function attacking_rook_with_a_knight_shielding_the_unprotected_queen()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedPhrase = [
            "If the knight on e6 is moved, these pieces will be exposed to attack: The rook on e3, Black's queen on e8.",
        ];

        $board = (new StrToBoard('4q1k1/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('4qk2/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_unprotected_queen()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedPhrase = [
            "If the knight on c6 is moved, these pieces will be exposed to attack: The bishop on a4, Black's queen on e8.",
        ];

        $board = (new StrToBoard('4q1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_unprotected_rook()
    {
        $expectedResult = [
            'w' => 1.9,
            'b' => 0,
        ];

        $expectedPhrase = [
            "If the knight on c6 is moved, the rook on e8 will be exposed to attack.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }

    /**
     * @test
     */
    public function c60()
    {
        $expectedResult = [
            'w' => 1.0,
            'b' => 0,
        ];

        $expectedPhrase = [
            "If the knight on c6 is moved, the pawn on e5 will be exposed to attack.",
        ];

        $board = (new StrToBoard('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'))
            ->create();

        $relativeSkewerEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $relativeSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $relativeSkewerEval->getPhrases());
    }
}
