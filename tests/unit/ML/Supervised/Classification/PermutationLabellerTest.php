<?php

namespace Chess\Tests\Unit\ML\Supervised\Classification;

use Chess\Board;
use Chess\Heuristics;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\ML\Supervised\Classification\PermutationLabeller;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Sicilian\Open as ClosedSicilian;

class PermutationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $dimensions = (new Heuristics(''))->getDimensions();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 4, 28 ],
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
    public function fool_checkmate_labelled()
    {
        $board = (new FoolCheckmate(new Board()))->play();

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
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 0,
            'b' => 2,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function benko_gambit_labelled()
    {
        $board = (new BenkoGambit(new Board()))->play();

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
    public function closed_sicilian_labelled()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $expected = [
            'w' => 1,
            'b' => 4,
        ];

        $label = (new PermutationLabeller(self::$permutations))->label($end);

        $this->assertSame($expected, $label);
    }
}
