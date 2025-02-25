<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\SanSignal;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SanSignalTest extends AbstractUnitTestCase
{
    public function phi_start()
    {
        $expected = 3362626364;

        $board = new Board();

        $phi = SanSignal::phi($board);

        $this->assertEquals($expected, $phi);
    }

    public function phi_kaufman_06()
    {
        $expected = 1938861923;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $phi = SanSignal::phi($board);

        $this->assertEquals($expected, $phi);
    }

    /**
     * @test
     */
    public function decode_A74()
    {
        $expected = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7 8.Be2 O-O 9.O-O a6 10.a4';

        $phi = [ 0, 1472424003, 41084857, 471135854, 4080978861, 1344110896, 1078627363, 2201876602, 907191305, 2154183662, 3245098227, 2158578178, 1873853, 2661948760, 2718166577, 3655178092, 3489103341, 175157769, 1039073045, 3314885846 ];
      
        $board = SanSignal::decode(new Board(), $phi);

        $this->assertEquals($expected, $board->movetext());
    }
}
