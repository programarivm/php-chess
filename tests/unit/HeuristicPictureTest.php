<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\HeuristicPicture;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;

class HeuristicPictureTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function weights()
    {
        $heuristicPicture = new HeuristicPicture('');

        $weights = array_values($heuristicPicture->getDimensions());

        $expected = 100;

        $this->assertEquals($expected, array_sum($weights));
    }

    /**
     * @test
     */
    public function start_take_get_picture()
    {
        $board = new Board();

        $pic = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getPicture();

        $expected = [
            Symbol::WHITE => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            Symbol::BLACK => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function start_take_end()
    {
        $board = new Board();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            Symbol::BLACK => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function start_take_get_balance()
    {
        $board = new Board();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_take_get_picture()
    {
        $movetext = '1.e4 e5';

        $pic = (new HeuristicPicture($movetext))
            ->take()
            ->getPicture();

        $expected = [
            Symbol::WHITE => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            Symbol::BLACK => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_take_end()
    {
        $movetext = '1.e4 e5';

        $end = (new HeuristicPicture($movetext))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            Symbol::BLACK => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_take_get_balance()
    {
        $movetext = '1.e4 e5';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function fool_checkmate_take_end()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => [ 0, 0, 0.9, 0.2, 0, 0, 0, 0, 0, 0, 0, 0 ],
            Symbol::BLACK => [ 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function fool_checkmate_take_get_balance()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, -1, 0.6, -0.6, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -1, 0.9, -0.8, -1, -1, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function scholar_checkmate_take_end()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $end = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->end();

        $expected = [
            Symbol::WHITE => [ 1, 0, 0.07, 0.8, 1, 1, 0, 0, 0, 0, 0, 0 ],
            Symbol::BLACK => [ 0, 1, 0.93, 0.4, 0.4, 0, 0.5, 0.1, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function scholar_checkmate_take_get_balance()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0, -0.35, 0, 0.2, 0.25, 0, 0, 0, 0, 0, 0 ],
            [ 0, -1, -1, 0.8, 0.4, 0.25, 0, -0.8, 0, 0, 0, 0 ],
            [ 1, -1, -0.86, 0.4, 0.6, 1, -0.5, -0.1, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function benko_gambit_evaluate()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $heuristicPicture = new HeuristicPicture($board->getMovetext());

        $evaluation = $heuristicPicture->evaluate();

        $expected = [
            Symbol::WHITE => 48.24,
            Symbol::BLACK => 17.04,
        ];

        $this->assertEquals($expected, $evaluation);
    }

    /**
     * @test
     */
    public function e4_e6_take_get_balance()
    {
        $movetext = '1.e4 e6';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 1, -1, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_take_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0.5, -0.4, 0.17, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 1, -0.6, 0.83, 0, 0, -1, -1, 0, 0, 0, -1 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_take_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0.5, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 1, -0.38, 0.83, 0, 0, -0.5, -0.5, 0, 0, 0, -1 ],
            [ 0, 1, -0.5, 0.33, -0.5, 0, -1, -1, 0, 0, 0, -1 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_e5_Nfd7_take_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6 4.e5 Nfd7';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 0.5, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 1, -0.38, 0.83, 0, 0, -0.5, -0.5, 0, 0, 0, -1 ],
            [ 0, 1, -0.5, 0.33, -0.5, 0, -1, -1, 0, 0, 0, -1 ],
            [ 0, 0.5, -0.38, 0.34, -0.5, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_take_get_picture()
    {
        $movetext = '1.e4 e6';

        $pic = (new HeuristicPicture($movetext))
            ->take()
            ->getPicture();

        $expected = [
            Symbol::WHITE => [
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            Symbol::BLACK => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_take_get_picture()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $pic = (new HeuristicPicture($movetext))
            ->take()
            ->getPicture();

        $expected = [
            Symbol::WHITE => [
                [ 0, 0.5, 0.6, 0.17, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            Symbol::BLACK => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0, 0.6, 0.17, 1, 0, 1, 1, 0, 0, 0, 1 ],
            ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_take_get_picture()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $pic = (new HeuristicPicture($movetext))
            ->take()
            ->getPicture();

        $expected = [
            Symbol::WHITE => [
                [ 0, 0.5, 0.38, 0.17, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 0.5, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0.5, 0.5, 0.5, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            Symbol::BLACK => [
                [ 0, 0, 0.63, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0, 0.38, 0.17, 0.5, 0, 0.5, 0.5, 0, 0, 0, 1 ],
                [ 0, 0, 1, 0.17, 1, 0, 1, 1, 0, 0, 0, 1 ],
            ],
        ];

        $this->assertEquals($expected, $pic);
    }

    /**
     * @test
     */
    public function w_e4_b_Nf6_take_get_balance()
    {
        $movetext = '1.e4 Nf6';

        $balance = (new HeuristicPicture($movetext))
            ->take()
            ->getBalance();

        $expected = [
            [ 0, 1, -1, 1, -1, 0, -1, -1, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }
}
