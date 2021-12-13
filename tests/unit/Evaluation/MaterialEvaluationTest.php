<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\MaterialEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense as RuyLopezLucenaDefense;

class MaterialEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function starting_position()
    {
        $board = new Board();

        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $mtlEvald = (new MaterialEvaluation($board))->evaluate();

        $this->assertEquals($expected, $mtlEvald);
    }

    /**
     * @test
     */
    public function ruy_lopez_lucena_defense()
    {
        $board = (new RuyLopezLucenaDefense(new Board()))->play();

        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $mtlEvald = (new MaterialEvaluation($board))->evaluate();

        $this->assertSame($expected, $mtlEvald);
    }

    /**
     * @test
     */
    public function w_e4_b_Nf6()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Nf6');

        $expected = [
            'w' => 40.06,
            'b' => 40.06,
        ];

        $mtlEvald = (new MaterialEvaluation($board))->evaluate();

        $this->assertEquals($expected, $mtlEvald);
    }
}
