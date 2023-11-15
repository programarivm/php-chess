<?php

namespace Chess\Tests\Unit\ML\Supervised\Classification;

use Chess\EvalFunction;
use Chess\Heuristics;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\ML\Supervised\Classification\PermutationLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class PermutationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $eval = (new EvalFunction())->getEval();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 4, 16 ],
                count($eval),
                100
            );
    }

    /**
     * @test
     */
    public function w_e4_b_e5_labelled()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $expected = [
            'w' => 2,
            'b' => 0,
        ];

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Na6_labelled()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Na6');

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 4,
            'b' => 0,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Nc6_labelled()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Nc6');

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A00_labelled()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 2,
            'b' => 0,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 0,
            'b' => 8,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59_labelled()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 5,
            'b' => 7,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function B25_labelled()
    {
        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');

        $board = (new SanPlay($B25))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 2,
            'b' => 0,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }
}
