<?php

namespace Chess\Tests\Unit;

use Chess\SanDecoder;
use Chess\Function\FastFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SanDecoderTest extends AbstractUnitTestCase
{
    static private FastFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new FastFunction();
    }

    /**
     * @test
     */
    public function A59()
    {
        $expected = '1.d4 Nf6 2.c4 c5 3.d5 b5 4.cxb5 a6 5.bxa6 Bxa6 6.Nc3 d6 7.e4 Bxf1 8.Kxf1 g6 9.g3';

        $mean = [ 0, 0.5381, 0.5465, 0.26965, 0.6575, 0.28393, 0.4208, 0.29222, 0.1088, 0.34909, 0.349, 0.12338, -0.10694, -0.05983, -0.06842, 0.03005, 0.03099, 0.02949 ];

        $board = SanDecoder::mean(self::$f, new Board(), $mean);

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function A74()
    {
        $expected = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7 8.Be2 O-O 9.O-O a6 10.a4';

        $mean = [ 0, 0.5381, 0.5465, 0.26965, 0.6575, 0.28393, -0.02384, -0.08606, -0.08089, 0.13706, -0.01835, 0.15238, 0.15232, 0.09696, 0.00857, -0.07362, -0.06497, 0.09693, 0.07819, -0.10148 ];

        $board = SanDecoder::mean(self::$f, new Board(), $mean);

        $this->assertEquals($expected, $board->movetext());
    }
}
