<?php

namespace Chess\Tests\Unit;

use Chess\HeuristicsByFenString;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicsByFenStringTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_get_picture()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 1, 0.7, 0.4, 0.4, 0, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 1, 0.7, 0.4, 0.4, 0, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e5_get_balance()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e5_eval()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 34.08,
            'b' => 34.08,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_get_picture()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 1, 0.88, 0.52, 0.42, 0.02, 0.02, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 1, 0.88, 0.52, 0.42, 0.02, 0.02, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_get_balance()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_eval()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 35.52,
            'b' => 35.52,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function benko_gambit_get_picture()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $result = (new HeuristicsByFenString($fen))->getResult();

        $expected = [
            'w' => [ 0.88, 1, 0.45, 0.59, 0, 0.02, 0, 0, 0, 0.05, 0, 0.02, 0, 0, 0, 0, 0.02, 0, 0 ],
            'b' => [ 0.86, 0.78, 0.47, 0.5, 0.07, 0.02, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.02, 0, 0 ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function benko_gambit_get_balance()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0.02, 0.22, -0.02, 0.09, -0.07, 0, 0, 0, 0, 0.05, 0, 0.02, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function benko_gambit_eval()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $evaluation = (new HeuristicsByFenString($fen))->eval();

        $expected = [
            'w' => 33.24,
            'b' => 31.52,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function scholar_checkmate_get_balance()
    {
        $fen = 'r1bqkb1r/pppp1Qpp/2n2n2/4p3/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -';

        $balance = (new HeuristicsByFenString($fen))->getBalance();

        $expected = [ 0.02, 0.12, -0.25, 0.04, 0.06, 0.08, -0.02, 0, 0, 0, 0, 0, -0.83, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }
}
