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

        $phi = [ 0, 1773698656, 3419667868, 4112950023, 3617952222, 581457560, 1575763591, 373255501, 2316804500, 2401554125, 1372673221, 1146967291, 2654943006, 2365377848, 2433268054, 1938592384, 1073100079, 322525770, 3534798029, 573201243 ];
      
        $board = SanSignal::decode(new Board(), $phi);

        $this->assertEquals($expected, $board->movetext());
    }
}
