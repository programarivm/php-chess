<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\R;

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
        $rook = new R('w', 'a2', self::$square, RType::R);
        $flow = [
            0 => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            1 => ['a1'],
            2 => [],
            3 => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
        ];

        $this->assertEquals($flow, $rook->flow);
    }

    /**
     * @test
     */
    public function flow_d5()
    {
        $rook = new R('w', 'd5', self::$square, RType::R);
        $flow = [
            0 => ['d6', 'd7', 'd8'],
            1 => ['d4', 'd3', 'd2', 'd1'],
            2 => ['c5', 'b5', 'a5'],
            3 => ['e5', 'f5', 'g5', 'h5'],
        ];

        $this->assertEquals($flow, $rook->flow);
    }

    /**
     * @test
     */
    public function line_of_attack_b7()
    {
        $board = FenToBoardFactory::create('4K3/1R6/8/8/8/8/1k6/8 b - -');

        $expected = [
            'b3',
            'b4',
            'b5',
            'b6',
        ];

        $this->assertEquals($expected, $board->pieceBySq('b7')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_c2()
    {
        $board = FenToBoardFactory::create('4K3/2k5/8/8/8/8/2R5/8 b - -');

        $expected = [
            'c6',
            'c5',
            'c4',
            'c3',
        ];

        $this->assertEquals($expected, $board->pieceBySq('c2')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_a4()
    {
        $board = FenToBoardFactory::create('7k/8/8/8/r4K2/8/8/8 w - -');

        $expected = [
            'e4',
            'd4',
            'c4',
            'b4',
        ];

        $this->assertEquals($expected, $board->pieceBySq('a4')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_f5()
    {
        $board = FenToBoardFactory::create('7k/8/8/K4r2/8/8/8/8 w - -');

        $expected = [
            'b5',
            'c5',
            'd5',
            'e5',
        ];

        $this->assertEquals($expected, $board->pieceBySq('f5')->lineOfAttack());
    }
}
