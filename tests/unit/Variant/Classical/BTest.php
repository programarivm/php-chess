<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\FenToBoardFactory;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class BTest extends AbstractUnitTestCase
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
            0 => [],
            1 => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            2 => [],
            3 => ['b1'],
        ];

        $flow = (new B('w', 'a2', self::$square))->flow;

        $this->assertEquals($expected, $flow);
    }

    /**
     * @test
     */
    public function flow_d5()
    {
        $expected = [
            0 => ['c6', 'b7', 'a8'],
            1 => ['e6', 'f7', 'g8'],
            2 => ['c4', 'b3', 'a2'],
            3 => ['e4', 'f3', 'g2', 'h1'],
        ];

        $flow = (new B('w', 'd5', self::$square))->flow;

        $this->assertEquals($expected, $flow);
    }

    /**
     * @test
     */
    public function flow_a8()
    {
        $expected = [
            0 => [],
            1 => [],
            2 => [],
            3 => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1'],
        ];

        $flow = (new B('w', 'a8', self::$square))->flow;

        $this->assertEquals($expected, $flow);
    }

    /**
     * @test
     */
    public function line_e4()
    {
        $expected = [
            'c6',
            'd5',
        ];

        $board = FenToBoardFactory::create('8/1k6/8/8/4B3/4K3/8/8 b - -');
        $line = self::$square->line('e4', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_e5()
    {
        $expected = [
            'f6',
            'g7',
        ];

        $board = FenToBoardFactory::create('7k/8/8/4B3/8/4K3/8/8 b - -');
        $line = self::$square->line('e5', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_d6()
    {
        $expected = [
            'e5',
            'f4',
            'g3',
        ];

        $board = FenToBoardFactory::create('8/8/3B4/8/8/4K3/7k/8 b - -');
        $line = self::$square->line('d6', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function line_g6()
    {
        $expected = [
            'c2',
            'd3',
            'e4',
            'f5',
        ];

        $board = FenToBoardFactory::create('8/8/6B1/8/8/4K3/8/1k6 b - -');
        $line = self::$square->line('g6', $board->piece(Color::B, Piece::K)->sq);

        $this->assertEquals($expected, $line);
    }

    /**
     * @test
     */
    public function is_between_a_b_c()
    {
        $board = FenToBoardFactory::create('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1');

        $a = $board->pieceBySq('f3');
        $b = $board->pieceBySq('e4');
        $c = $board->pieceBySq('d5');

        $this->assertTrue(self::$square->isBetweenSqs($a->sq, $b->sq, $c->sq));
        $this->assertTrue(self::$square->isBetweenSqs($c->sq, $b->sq, $a->sq));
        $this->assertFalse(self::$square->isBetweenSqs($a->sq, $c->sq, $b->sq));
    }
}
