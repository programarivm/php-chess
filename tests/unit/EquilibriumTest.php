<?php

namespace Chess\Tests\Unit;

use Chess\Equilibrium;
use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class EquilibriumTest extends AbstractUnitTestCase
{
    public function phi_start()
    {
        $expectedPhi = 3362626364;

        $board = new Board();

        $phi = Equilibrium::phi($board);

        $this->assertEquals($expectedPhi, $phi);
    }

    public function phi_kaufman_06()
    {
        $expectedPhi = 1938861923;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $phi = Equilibrium::phi($board);

        $this->assertEquals($expectedPhi, $phi);
    }

    /**
     * @test
     */
    public function decode_A59()
    {
        $expected = '1.d4 Nf6 2.c4 c5 3.d5 b5 4.cxb5 a6 5.bxa6 Bxa6 6.Nc3 d6 7.e4 Bxf1 8.Kxf1 g6 9.g3';

        $phi = [ 0, 3530412349, 2532887542, 3682245942, 1091364578, 797883169, 1714254863, 1407706963, 3795852998, 162706843, 2611601400, 286899090, 3587108835, 1239806223, 1504064155, 2780015000, 1446645633, 3949123297 ];

        $board = Equilibrium::decode(new Board(), $phi);

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function decode_A74()
    {
        $expected = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7 8.Be2 O-O 9.O-O a6 10.a4';

        $phi = [ 0, 3530412349, 2532887542, 3682245942, 1091364578, 797883169, 4263999068, 1954069693, 184339593, 833351396, 837113606, 2941659443, 435177712, 3142879859, 3065508635, 3834767935, 1073257767, 2732156664, 3777063103, 716798583 ];

        $board = Equilibrium::decode(new Board(), $phi);

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function decode_B92()
    {
        $expected = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 a6 6.Be2 e6 7.O-O Be7 8.a4 Nc6 9.Be3 O-O 10.f4 Qc7 11.Kh1 Re8 12.Bf3 Rb8 13.Qd2 Na5 14.Qf2 Nc4 15.Bc1 e5 16.Nde2 exf4 17.Nxf4 Be6 18.b3 Ne5 19.Bb2 Rbc8 20.Rac1 Qc5 21.Qg3 g6 22.Nce2 Nxf3 23.gxf3 b5 24.axb5 axb5 25.Bd4 Qc6 26.Qg2 b4 27.Ng3 Qb5 28.Nxe6 fxe6 29.f4 e5 30.Bb2 Rc5 31.f5 g5 32.Rce1 Qc6 33.Re2 Kf7 34.Bc1 Rg8 35.Be3 Rc3 36.Bd2 Rxc2 37.Bxb4 Rxe2 38.Qxe2 h5 39.Nxh5 Nxe4 40.Qf3 g4 41.Qg2 Rh8';

        $phi = [ 0, 2792297990, 1347607459, 3298259197, 1729086327, 2467388911, 3714192221, 2596254253, 1673909189, 2658520466, 3401579937, 3066637573, 2776828088, 3808001149, 2653223424, 3639319877, 3203541474, 779221393, 1412610403, 3713751640, 214747504, 3510948799, 2768925330, 2960891217, 2998322399, 3054005463, 3354127120, 740418829, 2540639318, 3078318630, 990851160, 4020430066, 1239710334, 655252171, 1795382511, 3010462454, 2908130775, 854041307, 3249225548, 80285692, 1362813914, 173106779, 3207450276, 335103144, 3146906880, 1981995540, 83645580, 3330342175, 1266860055, 1442405694, 3981990031, 1192561550, 1272717616, 458063200, 2647854660, 3550038771, 3667404248, 3303415005, 2851397127, 439215108, 1207405080, 3657188211, 3060429804, 1954779366, 3500783907, 1925530443, 645044320, 1374558194, 2361183720, 1067319068, 820342829, 4060372602, 3427147625, 80683894, 127379710, 3294219233, 3563471177, 3791575743, 2829148492, 365580708, 340312920, 1958880060, 4191773848 ];

        $board = Equilibrium::decode(new Board(), $phi);

        $this->assertEquals($expected, $board->movetext());
    }
}
