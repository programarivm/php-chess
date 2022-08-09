<?php

namespace Chess\Tests\Unit\ML\Supervised\Classification;

use Chess\Board;
use Chess\Heuristics;
use Chess\Player;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\ML\Supervised\Classification\PermutationLabeller;
use Chess\Tests\AbstractUnitTestCase;

class PermutationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $dimensions = (new Heuristics(''))->getDims();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 4, 20 ],
                count($dimensions),
                100
            );
    }

    /**
     * @test
     */
    public function start_labelled()
    {
        $board = new Board();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $label);
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
            'w' => 0,
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
            'w' => 1,
            'b' => 2,
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
            'w' => 1,
            'b' => 2,
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

        $board = (new Player($A00))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 2,
            'b' => 4,
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

        $board = (new Player($movetext))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 0,
            'b' => 20,
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

        $board = (new Player($A59))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 9,
            'b' => 11,
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

        $board = (new Player($B25))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 3,
            'b' => 4,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }
}
