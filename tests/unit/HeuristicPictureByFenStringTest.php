<?php

namespace Chess\Tests\Unit;

use Chess\HeuristicPictureByFenString;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicPictureByFenStringTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_take_get_picture()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $pic = (new HeuristicPictureByFenString($fen))->take()->getPicture();

        $expected = [
            Symbol::WHITE => [ 1, 0.05, 0.4, 0.4, 0, 0.02, 0, 0 ],
            Symbol::BLACK => [ 1, 0.05, 0.4, 0.4, 0, 0.02, 0, 0 ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function e4_e5_take_get_balance()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $balance = (new HeuristicPictureByFenString($fen))->take()->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e5_evaluate()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $evaluation = (new HeuristicPictureByFenString($fen))->evaluate();

        $expected = [
            Symbol::WHITE => 43.21,
            Symbol::BLACK => 43.21,
        ];

        $this->assertEquals($expected, $evaluation);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_take_get_picture()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $pic = (new HeuristicPictureByFenString($fen))->take()->getPicture();

        $expected = [
            Symbol::WHITE => [ 1, 0.05, 0.51, 0.41, 0, 0, 0, 0 ],
            Symbol::BLACK => [ 1, 0.05, 0.51, 0.41, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_take_get_balance()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $balance = (new HeuristicPictureByFenString($fen))->take()->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_evaluate()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $evaluation = (new HeuristicPictureByFenString($fen))->evaluate();

        $expected = [
            Symbol::WHITE => 44.56,
            Symbol::BLACK => 44.56,
        ];

        $this->assertEquals($expected, $evaluation);
    }

    /**
     * @test
     */
    public function benko_gambit_take_get_picture()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $pic = (new HeuristicPictureByFenString($fen))->take()->getPicture();

        $expected = [
            Symbol::WHITE => [ 1, 0.08, 0.5, 0.67, 0, 0.03, 0, 0 ],
            Symbol::BLACK => [ 0.97, 0.06, 0.53, 0.56, 0.08, 0.03, 0, 0 ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function benko_gambit_take_get_balance()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $balance = (new HeuristicPictureByFenString($fen))->take()->getBalance();

        $expected = [ 0.03, 0.02, -0.03, 0.11, -0.08, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function benko_gambit_evaluate()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $evaluation = (new HeuristicPictureByFenString($fen))->evaluate();

        $expected = [
            Symbol::WHITE => 47.14,
            Symbol::BLACK => 46.01,
        ];

        $this->assertEquals($expected, $evaluation);
    }
}
