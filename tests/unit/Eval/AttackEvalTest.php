<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AttackEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class AttackEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $result = (new AttackEval(new Board()))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->getBoard();
        $result = (new AttackEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B56()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');
        $board = (new SanPlay($B56))->validate()->getBoard();
        $result = (new AttackEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Nxe5()
    {
        $expectedEval = [
            'w' => 0,
            'b' => 2.33,
        ];

        $expectedPhrase = [
            "Black's pawn on a6 is attacking White's bishop on b5.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'a6');
        $board->play('w', 'Nxe5');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedEval, $attackEval->getResult());
        $this->assertSame($expectedPhrase, $attackEval->getPhrases());
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_a3_Nxe4_d3()
    {
        $expectedEval = [
            'w' => 2.2,
            'b' => 0,
        ];

        $expectedPhrase = [
            "White's pawn on d3 is attacking Black's knight on e4.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'a3');
        $board->play('b', 'Nxe4');
        $board->play('w', 'd3');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedEval, $attackEval->getResult());
        $this->assertSame($expectedPhrase, $attackEval->getPhrases());
    }

    /**
     * @test
     */
    public function e4_Nf6_e5()
    {
        $expectedEval = [
            'w' => 2.2,
            'b' => 0,
        ];

        $expectedPhrase = [
            "White's pawn on e5 is attacking Black's knight on f6.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Nf6');
        $board->play('w', 'e5');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedEval, $attackEval->getResult());
        $this->assertSame($expectedPhrase, $attackEval->getPhrases());
    }
}
