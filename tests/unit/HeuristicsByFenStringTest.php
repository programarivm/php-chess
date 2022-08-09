<?php

namespace Chess\Tests\Unit;

use Chess\HeuristicsByFenString;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicsByFenStringTest extends AbstractUnitTestCase
{
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
    public function eval_e4_e5()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 26.88,
            'b' => 26.88,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function eval_e4_e5_Nf3_Nf6()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 28.32,
            'b' => 28.32,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function eval_A59()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 26.4,
            'b' => 25.12,
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
    public function get_balance_e4_e5()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e5_Nf3_Nf6()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_A59()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0.02, 0.22, -0.02, 0.09, -0.07, 0, 0, 0, 0, 0.05, 0, -0.02, 0, 0, 0, 0, 0, 0, 0, 0, -0.03 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_scholar_checkmate()
    {
        $fen = 'r1bqkb1r/pppp1Qpp/2n2n2/4p3/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0.02, 0.12, -0.25, 0.04, 0.06, 0.08, -0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, -0.02 ];

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
    public function get_resized_balance_e4_e5()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $balance = (new HeuristicsByFenString($fen))->getResizedBalance(0, 1);

        $expected = [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_e4_e5_Nf3_Nf6()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $balance = (new HeuristicsByFenString($fen))->getResizedBalance(0, 1);

        $expected = [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_A59()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $balance = (new HeuristicsByFenString($fen))->getResizedBalance(0, 1);

        $expected = [ 0.51, 0.61, 0.49, 0.55, 0.47, 0.5, 0.5, 0.5, 0.5, 0.53, 0.5, 0.49, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.49 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_resized_balance_scholar_checkmate()
    {
        $fen = 'r1bqkb1r/pppp1Qpp/2n2n2/4p3/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -';

        $balance = (new HeuristicsByFenString($fen))->getResizedBalance(0, 1);

        $expected = [ 0.51, 0.56, 0.38, 0.52, 0.53, 0.54, 0.49, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.49 ];

        $this->assertEquals($expected, $balance);
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
    public function get_result_e4_e5()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 1, 0.7, 0.4, 0.4, 0, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2 ],
            'b' => [ 1, 0.7, 0.4, 0.4, 0, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2 ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_e4_e5_Nf3_Nf6()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 1, 0.88, 0.52, 0.42, 0.02, 0.02, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2 ],
            'b' => [ 1, 0.88, 0.52, 0.42, 0.02, 0.02, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2 ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function get_result_A59()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 0.88, 1, 0.45, 0.59, 0, 0.02, 0, 0, 0, 0.05, 0, 0, 0, 0, 0, 0, 0.02, 0, 0, 0, 0.07 ],
            'b' => [ 0.86, 0.78, 0.47, 0.5, 0.07, 0.02, 0, 0, 0, 0, 0, 0.02, 0, 0, 0, 0, 0.02, 0, 0, 0, 0.1 ],
        ];

        $this->assertEquals($expected, $result);
    }
}
