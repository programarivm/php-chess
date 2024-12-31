<?php

namespace Chess\Tests\Unit;

use Chess\EvalArray;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;

class EvalArrayTest extends AbstractUnitTestCase
{
    static private CompleteFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new CompleteFunction();
    }

    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedNormd = [ -1.0, -0.33, 0.01, 0.08, 0.08, 0.08, 0.08, 0.42, 1.0 ];
        $expectedMean = 0.42;
        $expectedMedian = 0.08;
        $expectedMode = 0.08;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $normalization = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normalization);

        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);

        $this->assertSame($expectedNormd, $normalization);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
    }

    /**
     * @test
     */
    public function kaufman_07()
    {
        $expectedNormd = [ -1.0, -0.31, -0.31, 0.1, 0.1, 0.1, 0.58, 1.0 ];
        $expectedMean = 0.26;
        $expectedMedian = 0.1;
        $expectedMode = 0.1;

        $board = FenToBoardFactory::create('2r2rk1/1bqnbpp1/1p1ppn1p/pP6/N1P1P3/P2B1N1P/1B2QPP1/R2R2K1 b - -');

        $normalization = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normalization);

        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);

        $this->assertSame($expectedNormd, $normalization);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
    }

    /**
     * @test
     */
    public function kaufman_08()
    {
        $expectedNormd = [ -1.0, -0.4, -0.2, 0.09, 0.09, 0.09, 0.09, 0.11, 0.19, 0.3, 0.3, 0.35, 1 ];
        $expectedMean = 1.01;
        $expectedMedian = 0.09;
        $expectedMode = 0.09;

        $board = FenToBoardFactory::create('5r1k/6pp/1n2Q3/4p3/8/7P/PP4PK/R1B1q3 b - -');

        $normalization = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normalization);

        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);

        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
    }

    /**
     * @test
     */
    public function no_mode()
    {
        $expectedNormd = [ -1.0, -0.4, -0.2, 0.09, 0.09, 0.09, 0.09, 0.11, 0.19, 0.3, 0.3, 0.35, 1 ];
        $expectedMean = 0.24;
        $expectedMedian = 0.24;
        $expectedMode = null;

        $board = FenToBoardFactory::create('rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -');

        $normalization = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normalization);

        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);

        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
    }
}
