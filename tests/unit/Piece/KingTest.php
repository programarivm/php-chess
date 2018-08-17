<?php

namespace PGNChess\Tests\Unit\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\King;
use PHPUnit\Framework\TestCase;

class KingTest extends TestCase
{
    /**
     * @test
     */
    public function scope_a2()
    {
        $king = new King(Symbol::WHITE, 'a2');
        $scope = (object) [
            'up' => 'a3',
            'bottom' => 'a1',
            'right' => 'b2',
            'upRight' => 'b3',
            'bottomRight' => 'b1'
        ];
        $this->assertEquals($scope, $king->getScope());
    }

    /**
     * @test
     */
    public function scope_d5()
    {
        $king = new King(Symbol::WHITE, 'd5');
        $scope = (object) [
            'up' => 'd6',
            'bottom' => 'd4',
            'left' => 'c5',
            'right' => 'e5',
            'upLeft' => 'c6',
            'upRight' => 'e6',
            'bottomLeft' => 'c4',
            'bottomRight' => 'e4'
        ];
        $this->assertEquals($scope, $king->getScope());
    }
}
