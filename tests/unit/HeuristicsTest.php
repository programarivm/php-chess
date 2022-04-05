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
    /**
     * @test
     */
    public function weights()
    {
        $heuristics = new Heuristics('');

        $weights = array_values($heuristics->getDimensions());

        $expected = 100;

        $this->assertSame($expected, array_sum($weights));
    }

    /**
     * @test
     */
    public function start_get_picture()
    {
        $board = new Board();

        $result = (new Heuristics($board->getMovetext()))->getResult();

        $expected = [
            'w' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function start_end()
    {
        $board = new Board();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $end);
    }

    /**
     * @test
     */
    public function start_get_balance()
    {
        $board = new Board();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_get_picture()
    {
        $movetext = '1.e4 e5';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_end()
    {
        $movetext = '1.e4 e5';

        $end = (new Heuristics($movetext))->end();

        $expected = [
            'w' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $end);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_get_balance()
    {
        $movetext = '1.e4 e5';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function fool_checkmate_end()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 0, 0.2, 0.9, 0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function fool_checkmate_get_balance()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, -0.8, 0.6, -0.6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.8, 0.9, -0.8, -1, -1, 0, 0, 0, 0, 0, 0, -1, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function scholar_checkmate_end()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $end = (new Heuristics($board->getMovetext()))->end();

        $expected = [
            'w' => [ 1, 1, 0.07, 0.8, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0 ],
            'b' => [ 0, 0.66, 0.93, 0.4, 0.4, 0, 0.1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function scholar_checkmate_get_balance()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.13, -0.35, 0, 0.2, 0.25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, -0.19, -1, 0.8, 0.4, 0.25, -0.8, -1, 0, 0, 0, 0, 0.03, 0, 0, 0, 0, 0, 0 ],
            [ 1, 0.34, -0.86, 0.4, 0.6, 1, -0.1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function benko_gambit_eval()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $heuristics = new Heuristics($board->getMovetext());

        $evaluation = $heuristics->eval();

        $expected = [
            'w' => 26.32,
            'b' => 20.52,
        ];

        $this->assertSame($expected, $evaluation);
    }

    /**
     * @test
     */
    public function e4_e6_get_balance()
    {
        $movetext = '1.e4 e6';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 1, -1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.27, -0.4, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.82, -0.6, 0.83, 0, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.27, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.82, -0.38, 0.83, 0, 0, -0.5, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.35, -0.5, 0.33, -0.5, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_e5_Nfd7_get_balance()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6 4.e5 Nfd7';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 0.25, -0.25, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.74, -0.38, 0.83, 0, 0, -0.5, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.32, -0.5, 0.33, -0.5, 0, -1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
            [ 0, 0.41, -0.38, 0.34, -0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function e4_e6_get_picture()
    {
        $movetext = '1.e4 e6';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_get_picture()
    {
        $movetext = '1.e4 e6 2.d4 d5';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0.27, 0.6, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.18, 0.6, 0.17, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e6_d4_d5_Nd2_Nf6_get_picture()
    {
        $movetext = '1.e4 e6 2.d4 d5 3.Nd2 Nf6';

        $result = (new Heuristics($movetext))->getResult();

        $expected = [
            'w' => [
                [ 0, 0.27, 0.38, 0.17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 1, 0, 1, 0.5, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.93, 0.5, 0.5, 0.5, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0.63, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.18, 0.38, 0.17, 0.5, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                [ 0, 0.58, 1, 0.17, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function w_e4_b_Nf6_get_balance()
    {
        $movetext = '1.e4 Nf6';

        $balance = (new Heuristics($movetext))->getBalance();

        $expected = [
            [ 0, 1, -1, 1, -1, 0, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $this->assertEquals($expected, $balance);
    }
}
