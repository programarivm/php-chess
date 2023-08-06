<?php

namespace Chess\Tests\Unit;

use Chess\Heuristics;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FEN\StrToBoard;

class HeuristicsTest extends AbstractUnitTestCase
{
    /*
    |--------------------------------------------------------------------------
    | getEvalNames()
    |--------------------------------------------------------------------------
    |
    | Returns the dimensions names.
    |
    */

    /**
     * @test
     */
    public function get_eval_names()
    {
        $evalNames = (new Heuristics())->getEvalNames();

        $expected = [
            'Material',
            'Center',
            'Connectivity',
            'Space',
            'Pressure',
            'King safety',
            'Tactics',
            'Attack',
            'Doubled pawn',
            'Passed pawn',
            'Isolated pawn',
            'Backward pawn',
            'Absolute pin',
            'Relative pin',
            'Absolute fork',
            'Relative fork',
            'Square outpost',
            'Knight outpost',
            'Bishop outpost',
            'Bishop pair',
            'Bad bishop',
            'Direct opposition',
        ];

        $this->assertSame($expected, $evalNames);
    }

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
            'w' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
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
            'w' => [ 0, 1.0, 0.0, 1.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 1.0, 0.0, 1.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $end);
    }

    /**
     * @test
     */
    public function end_A00()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->getBoard();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 0, 0.43, 0.9, 0.33, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function end_scholar_checkmate()
    {
        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 1, 1, 0.07, 0.92, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0.8, 0.93, 0.77, 0.4, 0, 0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0 ],
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
    public function eval_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $heuristics = new Heuristics($board->getMovetext());

        $evaluation = $heuristics->eval();

        $expected = [
            'w' => 20.72,
            'b' => 20.64,
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
            [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
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
            [ 0.0, 1.0, -1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
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
            [ 0, 1, -1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.25, -0.5, 0.12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
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
            [ 0.0, 0.52, -0.57, 0.57, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.13, -0.28, 0.07, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.61, -0.71, 0.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.39, -0.43, 0.36, 0.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
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
            [ 0.0, 0.52, -0.50, 0.57, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.13, -0.25, 0.07, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.61, -0.63, 0.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.39, -0.38, 0.36, 0.0, 0.0, -0.5, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.35, 0.12, 0.14, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 0.16, -0.5, 0.14, -0.5, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_A00()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, 0.29, 0.2, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.57, 0.6, -0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.43, 0.5, -0.34, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.57, 0.9, -0.67, -1, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_scholar_checkmate()
    {
        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, 0.42, -0.28, 0.62, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.24, -0.07, 0.07, 0.2, 0.25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.08, -0.35, 0, 0.2, 0.25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.15, -0.64, 0.31, 0.8, 0.25, 0.2, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.11, -1, 0.31, 0.4, 0.25, -0.8, -1, 0, 0, 0, 0, 1, 0, 0, -1, 0, 0, 0, 0, 0, 0 ],
            [ 1, 0.2, -0.86, 0.15, 0.6, 1, -0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, -1, 0 ],
            [ 1, 0.2, -0.86, 0.15, 0.6, 1, -0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, -1, 0 ],
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
            [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
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
            [ 0.5, 1.0, 0.0, 1.0, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
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
            [ 0.5, 1.0, 0.0, 1.0, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.63, 0.25, 0.56, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
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
            [ 0.5, 0.76, 0.22, 0.79, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.57, 0.36, 0.54, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.81, 0.15, 0.75, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 1.0, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.7, 0.29, 0.68, 0.5, 0.5, 0, 0.5, 0.5, 0.5, 0.5, 1.0, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
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
            [ 0.5, 0.76, 0.25, 0.79, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.57, 0.38, 0.54, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.81, 0.19, 0.75, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.7, 0.31, 0.68, 0.5, 0.5, 0.25, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.68, 0.56, 0.57, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            [ 0.5, 0.58, 0.25, 0.57, 0.25, 0.5, 0, 0.5, 0.5, 0.5, 0.5, 1, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /*
    |--------------------------------------------------------------------------
    | getEval()
    |--------------------------------------------------------------------------
    |
    | Returns the evaluation features also known as dimensions.
    |
    */

    /**
     * @test
     */
    public function get_eval()
    {
        $heuristics = new Heuristics('');

        $weights = array_values($heuristics->getEval());

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
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
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
                [ 0, 1.0, 0.0, 1.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1.0, 0.0, 1.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0.0, 1.0, 0.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1.0, 0.0, 1.0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
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
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.75, 0.5, 0.88, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
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
                [ 0, 0.52, 0.43, 0.57, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.52, 0.43, 0.57, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.87, 0, 0.93, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.39, 0.71, 0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.39, 0.71, 0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.48, 0.43, 0.57, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_Rb4__Kg6_start_fen()
    {
        $startFen = '8/8/4k3/6K1/8/8/1r6/8 b - -';

        $movetext = '1...Rb4 2.Kh6 Rb5 3.Kg6 Kd7 4.Kf6';

        $board = (new StrToBoard($startFen))->create();

        $result = (new Heuristics($movetext, $board))->getResult();

        $expected = [
            'w' => [
                [ 0, 0.22, 0, 0.18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0, 0, 0, 0, 0.6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.19, 0, 0.18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1 ],
                [ 0, 0.19, 0, 0.18, 0, 0.4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.41, 0, 0.18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 1, 1, 0, 1, 0, 0.6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 1, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 1, 0.81, 0, 0.82, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 1, 0.81, 0, 0.82, 0, 0.4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 1, 0.73, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 1, 0.73, 0, 1, 0, 0.6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
