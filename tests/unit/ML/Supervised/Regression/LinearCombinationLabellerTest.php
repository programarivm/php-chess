<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\Board;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\HeuristicPicture;
use Chess\ML\Supervised\Regression\LinearCombinationLabeller;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Sicilian\Open as ClosedSicilian;

class LinearCombinationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $dimensions = (new HeuristicPicture(''))->getDimensions();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 8, 13, 21, 34],
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

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))
            ->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 50,
            Symbol::BLACK => 50,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))
            ->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Na6_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Na6'));

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 69.5,
            Symbol::BLACK => 48.5,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))
            ->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Nc6_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 56.5,
            Symbol::BLACK => 56.5,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function fool_checkmate_labelled()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 32.25,
            Symbol::BLACK => 68.0,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 59.0,
            Symbol::BLACK => 49.83,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function benko_gambit_labelled()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 52.25,
            Symbol::BLACK => 44.88,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function closed_sicilian_labelled()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => 43.51,
            Symbol::BLACK => 34.74,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }
}
