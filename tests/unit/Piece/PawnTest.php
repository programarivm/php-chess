<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\Pawn;
use Chess\Tests\AbstractUnitTestCase;

class PawnTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function white_a2()
    {
        $pawn = new Pawn('w', 'a2');

        $position = 'a2';
        $scope = (object) ['up' => ['a3', 'a4']];
        $captureSquares = ['b3'];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }

    /**
     * @test
     */
    public function white_d5()
    {
        $pawn = new Pawn('w', 'd5');

        $position = 'd5';
        $scope = (object) ['up' => ['d6']];
        $captureSquares = ['c6', 'e6'];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }

    /**
     * @test
     */
    public function white_f7()
    {
        $pawn = new Pawn('w', 'f7');

        $position = 'f7';
        $scope = (object) ['up' => ['f8']];
        $captureSquares = ['e8', 'g8'];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }

    /**
     * @test
     */
    public function white_f8()
    {
        $pawn = new Pawn('w', 'f8');

        $position = 'f8';
        $scope = (object) ['up' => []];
        $captureSquares = [];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }

    /**
     * @test
     */
    public function black_a2()
    {
        $pawn = new Pawn('b', 'a2');

        $position = 'a2';
        $scope = (object) ['up' => ['a1']];
        $captureSquares = ['b1'];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }

    /**
     * @test
     */
    public function black_d5()
    {
        $pawn = new Pawn('b', 'd5');

        $position = 'd5';
        $scope = (object) ['up' => ['d4']];
        $captureSquares = ['c4', 'e4'];

        $this->assertSame($position, $pawn->getSquare());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertSame($captureSquares, $pawn->getCaptureSquares());
    }
}
