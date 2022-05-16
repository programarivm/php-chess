<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\Heuristics;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;

class HeuristicsTest extends AbstractUnitTestCase
{
    /*
    |--------------------------------------------------------------------------
    | end()
    |--------------------------------------------------------------------------
    |
    | Returns the last element.
    */

    /**
     * @test
     */
    public function end_start()
    {
        $board = new Board();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $end);
    }

    /**
     * @test
     */
    public function end_e4_e5()
    {
        $movetext = '1.e4 e5';

        $end = (new Heuristics($movetext))->end();

        $expected = [
            'w' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $end);
    }

    /**
     * @test
     */
    public function end_fool_checkmate()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 0, 0.2, 0.9, 0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function end_scholar_checkmate()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 1, 1, 0.07, 0.8, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0.66, 0.93, 0.4, 0.4, 0, 0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /*
    |--------------------------------------------------------------------------
    | eval()
    |--------------------------------------------------------------------------
    |
    | Returns the evaluation of the chess position in a human readable format.
    | The result obtained suggests which player may be better.
    |
    */

    /**
     * @test
     */
    public function eval_benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $heuristics = new Heuristics($board->getMovetext());

        $evaluation = $heuristics->eval();

        $expected = [
            'w' => 25.56,
            'b' => 20.52,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /*
    |--------------------------------------------------------------------------
    | getBalance()
    |--------------------------------------------------------------------------
    |
    | Returns the balanced heuristics.
    |
    */

    /**
     * @test
     */
    public function get_balance_start()
    {
        $board = new Board();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e5()
    {
        $movetext = '1.e4 e5';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e6()
    {
        $movetext = '1.e4 e6';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 1, -1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e6_d4_d5()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.27, -0.4, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.82, -0.6, 0.83, 0, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e6_d4_d5_Nd2_Nf6()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.27, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.82, -0.38, 0.83, 0, 0, -0.5, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.35, -0.5, 0.33, -0.5, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e6_d4_d5_Nd2_Nf6_e5_Nfd7()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6 4.e5 Nfd7';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.25, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.74, -0.38, 0.83, 0, 0, -0.5, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.32, -0.5, 0.33, -0.5, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.41, -0.38, 0.34, -0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_fool_checkmate()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, -0.8, 0.6, -0.6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.8, 0.9, -0.8, -1, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_scholar_checkmate()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.13, -0.35, 0, 0.2, 0.25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.19, -1, 0.8, 0.4, 0.25, -0.8, -1, 0, 0, 0, 0, 1, 0, 0, -1, 0, 0, 0, 0 ],
            [ 1, 0.34, -0.86, 0.4, 0.6, 1, -0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /*
    |--------------------------------------------------------------------------
    | getResizedBalance()
    |--------------------------------------------------------------------------
    |
    | Returns the resized, balanced heuristics.
    |
    */

    /**
     * @test
     */
    public function get_resized_balance_start()
    {
        $board = new Board();

        $balance = (new Heuristics($board->getMovetext()))->getResizedBalance(0, 1);

        $expected = [
            [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_e4_e5()
    {
        $movetext = '1.e4 e5';

        $balance = (new Heuristics($movetext))->getResizedBalance(0, 1);

        $expected = [
            [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_e4_e6()
    {
        $movetext = '1.e4 e6';

        $balance = (new Heuristics($movetext))->getResizedBalance(0, 1);

        $expected = [
            [ 0.5, 1, 0, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_e4_e6_d4_d5()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $balance = (new Heuristics($movetext))->getResizedBalance(0, 1);

        $expected = [
            [ 0.5, 0.64, 0.3, 0.59, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.91, 0.2, 0.92, 0.5, 0.5, 0, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_e4_e6_d4_d5_Nd2_Nf6()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $balance = (new Heuristics($movetext))->getResizedBalance(0, 1);

        $expected = [
            [ 0.5, 0.64, 0.38, 0.59, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.91, 0.31, 0.92, 0.5, 0.5, 0.25, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.68, 0.25, 0.67, 0.25, 0.5, 0, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /*
    |--------------------------------------------------------------------------
    | getDimensions()
    |--------------------------------------------------------------------------
    |
    | Returns the evaluation features also known as dimensions.
    |
    */

    /**
     * @test
     */
    public function get_dimensions()
    {
        $heuristics = new Heuristics('');

        $weights = array_values($heuristics->getDimensions());

        $expected = 100;

        $this->assertSame($expected, array_sum($weights));
    }

    /*
    |--------------------------------------------------------------------------
    | getResult()
    |--------------------------------------------------------------------------
    |
    | Returns the heuristics.
    |
    */

    /**
     * @test
     */
    public function get_result_start()
    {
        $board = new Board();

        $result = (new Heuristics($board->getMovetext()))->getResult();

        $expected = [
            'w' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_e4_e5()
    {
        $movetext = '1.e4 e5';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_e4_e6()
    {
        $movetext = '1.e4 e6';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_e4_e6_d4_d5()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0.27, 0.6, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.18, 0.6, 0.17, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_e4_e6_d4_d5_Nd2_Nf6()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0.27, 0.38, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 0.5, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.93, 0.5, 0.5, 0.5, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0.63, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.18, 0.38, 0.17, 0.5, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.58, 1, 0.17, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
