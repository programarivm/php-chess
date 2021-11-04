<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\CheckmateEvaluation;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class CheckmateEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $checkmateEvald = (new CheckmateEvaluation($board))->evaluate();

        $this->assertEquals($expected, $checkmateEvald);
    }

    /**
     * @test
     */
    public function f3_e5_g4()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'f3'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'g4'));

        $expected = [
            Symbol::WHITE => 1,
            Symbol::BLACK => 0,
        ];

        $checkmateEvald = (new CheckmateEvaluation($board))->evaluate();

        $this->assertEquals($expected, $checkmateEvald);
    }
}
