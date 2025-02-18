<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RType;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\FenToBoardFactory;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class RTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function flow_a2()
    {
        $expected = [
            0 => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            1 => ['a1'],
            2 => [],
            3 => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
        ];

        $flow = (new R('w', 'a2', self::$square, RType::R))->flow;

        $this->assertEquals($expected, $flow);
    }

    /**
     * @test
     */
    public function flow_d5()
    {
        $expected = [
            0 => ['d6', 'd7', 'd8'],
            1 => ['d4', 'd3', 'd2', 'd1'],
            2 => ['c5', 'b5', 'a5'],
            3 => ['e5', 'f5', 'g5', 'h5'],
        ];

        $flow = (new R('w', 'd5', self::$square, RType::R))->flow;

        $this->assertEquals($expected, $flow);
    }

    /**
     * @test
     */
    public function line_b7()
    {
        $expected = [
            'b3',
            'b4',
            'b5',
            'b6',
        ];

        $board = FenToBoardFactory::create('4K3/1R6/8/8/8/8/1k6/8 b - -');
        $line = self::$square->line('b7', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_c2()
    {
        $expected = [
            'c3',
            'c4',
            'c5',
            'c6',
        ];

        $board = FenToBoardFactory::create('4K3/2k5/8/8/8/8/2R5/8 b - -');
        $line = self::$square->line('c2', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_a4()
    {
        $expected = [
            'b4',
            'c4',
            'd4',
            'e4',
        ];

        $board = FenToBoardFactory::create('7k/8/8/8/r4K2/8/8/8 w - -');
        $line = self::$square->line('a4', $board->piece(Color::W, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_f5()
    {
        $expected = [
            'b5',
            'c5',
            'd5',
            'e5',
        ];

        $board = FenToBoardFactory::create('7k/8/8/K4r2/8/8/8/8 w - -');
        $line = self::$square->line('f5', $board->piece(Color::W, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }
}
