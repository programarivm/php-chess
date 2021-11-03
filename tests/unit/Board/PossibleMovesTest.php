<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class PossibleMovesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();
        $possibleMoves = $board->getPossibleMoves();

        $expected = [
            'Na3',
            'Nc3',
            'Nf3',
            'Nh3',
            'a3',
            'a4',
            'b3',
            'b4',
            'c3',
            'c4',
            'd3',
            'd4',
            'e3',
            'e4',
            'f3',
            'f4',
            'g3',
            'g4',
            'h3',
            'h4',
        ];

        $this->assertEquals($expected, $possibleMoves);
    }

    /**
     * @test
     */
    public function e4()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $possibleMoves = $board->getPossibleMoves();

        $expected = [
            'Na6',
            'Nc6',
            'Nf6',
            'Nh6',
            'a6',
            'a5',
            'b6',
            'b5',
            'c6',
            'c5',
            'd6',
            'd5',
            'e6',
            'e5',
            'f6',
            'f5',
            'g6',
            'g5',
            'h6',
            'h5',
        ];

        $this->assertEquals($expected, $possibleMoves);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_Be2_Be7()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Be2'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Be7'));
        $possibleMoves = $board->getPossibleMoves();

        $expected = [
            'Na3',
            'Nc3',
            'Kf1',
            'O-O',
            'Rg1',
            'Rf1',
            'a3',
            'a4',
            'b3',
            'b4',
            'c3',
            'c4',
            'd3',
            'd4',
            'g3',
            'g4',
            'h3',
            'h4',
            'Nxe5',
            'Nd4',
            'Ng1',
            'Nh4',
            'Ng5',
            'Bd3',
            'Bc4',
            'Bb5',
            'Ba6',
            'Bf1',
        ];

        $this->assertEquals($expected, $possibleMoves);
    }
}
