<?php

namespace Chess\Tests\Unit\Play;

use Chess\FenToBoard;
use Chess\Play\RavPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;

class RavPlayTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5()
    {
        $movetext = '1.e4 e5';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($movetext, $ravPlay->getBoard()->getMovetext());
    }

    /**
     * @test
     */
    public function breakdown_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = [
            '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5',
            '11.Nb1 h6 12.h4',
            '12.Nh4 g5 13.Nf5',
            '12...a5 13.g4 Nxg4',
            '11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5',
            '16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_starting_with_ellipsis_Nc6__h5()
    {
        $movetext = '2...Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = [
            '2...Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5',
            '11.Nb1 h6 12.h4',
            '12.Nh4 g5 13.Nf5',
            '12...a5 13.g4 Nxg4',
            '11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5',
            '16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5',
        ];

        $ravPlay = new RavPlay($movetext);

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_Ra7_Kg8__Rc8()
    {
        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6
            (6.Kc6 Kd8)
            6...Kb8
            (6...Kd8 7.Ra8#)
            7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#';

        $expected = [
            '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8',
            '5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#',
            '6.Kd6',
            '6.Kc6 Kd8',
            '6...Kb8',
            '6...Kd8 7.Ra8#',
            '7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#',
        ];

        $ravPlay = new RavPlay($movetext);

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_Ke2_Kd5__Ra1()
    {
        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 3.Rh5+)
            (2...Kc4 3.Rh5)
            3.Rh5
            (3...Kb4 4.Kd3)
            3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = [
            '1.Ke2 Kd5 2.Ke3 Kc4',
            '2...Ke5 3.Rh5+',
            '2...Kc4 3.Rh5',
            '3.Rh5',
            '3...Kb4 4.Kd3',
            '3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#',
        ];

        $ravPlay = new RavPlay($movetext);

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_starting_with_ellipsis_Kc4__Ra1()
    {
        $movetext = '2...Kc4
            (2...Ke5 3.Rh5+)
            (2...Kc4 3.Rh5)
            3.Rh5
            (3...Kb4 4.Kd3)
            3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = [
            '2...Kc4',
            '2...Ke5 3.Rh5+',
            '2...Kc4 3.Rh5',
            '3.Rh5',
            '3...Kb4 4.Kd3',
            '3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#',
        ];

        $ravPlay = new RavPlay($movetext);

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function validate_e4_e5__h5()
    {
        $movetext = '1. e4 $1 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 $2 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getBoard()->getMovetext());
    }

    /**
     * @test
     */
    public function get_fen_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R b KQkq -',
            'r1bqk2r/ppppbppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R w KQkq -',
            'r1bqk2r/ppppbppp/2n2n2/1B2p3/4P3/2NP1N2/PPP2PPP/R1BQK2R b KQkq -',
            'r1bqk2r/ppp1bppp/2np1n2/1B2p3/4P3/2NP1N2/PPP2PPP/R1BQK2R w KQkq -',
            'r1bqk2r/ppp1bppp/2np1n2/1B2p3/4P3/2NPBN2/PPP2PPP/R2QK2R b KQkq -',
            'r2qk2r/pppbbppp/2np1n2/1B2p3/4P3/2NPBN2/PPP2PPP/R2QK2R w KQkq -',
            'r2qk2r/pppbbppp/2np1n2/1B2p3/4P3/2NPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2qk2r/1ppbbppp/p1np1n2/1B2p3/4P3/2NPBN2/PPPQ1PPP/R3K2R w KQkq -',
            'r2qk2r/1ppbbppp/p1np1n2/4p3/B3P3/2NPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2qk2r/2pbbppp/p1np1n2/1p2p3/B3P3/2NPBN2/PPPQ1PPP/R3K2R w KQkq b6',
            'r2qk2r/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2q1rk1/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/R3K2R w KQ -',
            'r2q1rk1/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/2KR3R b - -',
            'r2q1rk1/2pbbppp/p1np1n2/4p3/1p2P3/1BNPBN2/PPPQ1PPP/2KR3R w - -',
            'r2q1rk1/2pbbppp/p1np1n2/3Np3/1p2P3/1B1PBN2/PPPQ1PPP/2KR3R b - -',
            'r2q1rk1/2pbbppp/p1np1n2/4p3/1p2P3/1B1PBN2/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P3/1B1PBN2/PPPQ1PPP/1NKR3R w - -',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P2P/1B1PBN2/PPPQ1PP1/1NKR3R b - h3',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P2N/1B1PB3/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbp2/p1np1n1p/4p1p1/1p2P2N/1B1PB3/PPPQ1PPP/1NKR3R w - g6',
            'r2q1rk1/2pbbp2/p1np1n1p/4pNp1/1p2P3/1B1PB3/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbpp1/2np1n1p/p3p3/1p2P2P/1B1PBN2/PPPQ1PP1/1NKR3R w - -',
            'r2q1rk1/2pbbpp1/2np1n1p/p3p3/1p2P1PP/1B1PBN2/PPPQ1P2/1NKR3R b - g3',
            'r2q1rk1/2pbbpp1/2np3p/p3p3/1p2P1nP/1B1PBN2/PPPQ1P2/1NKR3R w - -',
            'r2q1rk1/2pbbppp/p1np4/3np3/1p2P3/1B1PBN2/PPPQ1PPP/2KR3R w - -',
            'r2q1rk1/2pbbppp/p1np4/3Bp3/1p2P3/3PBN2/PPPQ1PPP/2KR3R b - -',
            '1r1q1rk1/2pbbppp/p1np4/3Bp3/1p2P3/3PBN2/PPPQ1PPP/2KR3R w - -',
            '1r1q1rk1/2pbbppp/p1np4/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2KR3R b - h3',
            '1r1q1rk1/2pbbpp1/p1np3p/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2KR3R w - -',
            '1r1q1rk1/2pbbpp1/p1np3p/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2K3RR b - -',
            '1r1q1rk1/2pbbpp1/2np3p/p2Bp3/1p2P2P/3PBN2/PPPQ1PP1/2K3RR w - -',
            '1r1q1rk1/2pbbpp1/2np3p/p2Bp3/1p2P1PP/3PBN2/PPPQ1P2/2K3RR b - g3',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1p1/1p2P1PP/3PBN2/PPPQ1P2/2K3RR w - g6',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1pP/1p2P1P1/3PBN2/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1P1/1p2P1P1/3PBN2/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pb1p2/2np3p/p2Bp1b1/1p2P1P1/3PBN2/PPPQ1P2/2K3RR w - -',
            '1r1q1rk1/2pb1p2/2np3p/p2Bp1N1/1p2P1P1/3PB3/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pb1p2/2np4/p2Bp1p1/1p2P1P1/3PB3/PPPQ1P2/2K3RR w - -',
            '1r1q1rk1/2pb1p2/2np4/p2Bp1pR/1p2P1P1/3PB3/PPPQ1P2/2K3R1 b - -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function filtered_e4_e5__h5()
    {
        $movetext = '1. e4 e5 {foo} 2. Nf3 {bar} Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 {foobar}
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = '1.e4 e5 {foo} 2.Nf3 {bar} Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 {foobar} (11.Nb1 h6 12.h4 (12.Nh4 g5 13.Nf5) 12...a5 13.g4 Nxg4) 11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5 (16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5)';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->filtered());
    }

    /**
     * @test
     */
    public function filtered_d4_d5__Nxb5_Ng4()
    {
        $movetext = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7 6.Bd3 dxc4 7.Bxc4 b5 8.Bd3 a6 9.e4 c5 10.e5 cxd4 11.Nxb5 Ng4';

        $expected = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7 6.Bd3 dxc4 7.Bxc4 b5 8.Bd3 a6 9.e4 c5 10.e5 cxd4 11.Nxb5 Ng4';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->filtered());
    }

    /**
     * @test
     */
    public function get_fen_d4_d5__Nxb5_Ng4()
    {
        $movetext = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3',
            'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq d6',
            'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq c3',
            'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -',
            'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -',
            'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -',
            'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function validate_and_get_fen_d4_d5__Nxb5_Ng4()
    {
        $movetext = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7';

        $expectedMovetext = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7';

        $expectedFen = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3',
            'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq d6',
            'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq c3',
            'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -',
            'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -',
            'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -',
            'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expectedMovetext, $ravPlay->getBoard()->getMovetext());
        $this->assertSame($expectedFen, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function validate_d4_d5__Nxb5_Ng4()
    {
        $movetext = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7 6.Bd3 dxc4 7.Bxc4 b5 8.Bd3 a6 9.e4 c5 10.e5 cxd4 11.Nxb5 Ng4';

        $expected = '1.d4 d5 2.c4 c6 3.Nc3 Nf6 4.e3 e6 5.Nf3 Nbd7 6.Bd3 dxc4 7.Bxc4 b5 8.Bd3 a6 9.e4 c5 10.e5 cxd4 11.Nxb5 Ng4';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getMovetext());
    }

    /**
     * @test
     */
    public function validate_d4_d5__Nxb5_Ng4_commented()
    {
        $movetext = '1. e4 e5 {foo} 2. Nf3 {bar} Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 {foobar}
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expectedMovetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expectedMovetext, $ravPlay->getBoard()->getMovetext());
    }

    /**
     * @test
     */
    public function breakdown_chess_fundamentals()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6
            (6.Kc6 Kd8)
            6...Kb8
            (6...Kd8 7.Ra8#)';

        $expected = [
            '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8',
            '5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#',
            '6.Kd6',
            '6.Kc6 Kd8',
            '6...Kb8',
            '6...Kd8 7.Ra8#',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = new RavPlay($movetext, $board);

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Kd5_Kc8()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Ra8()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Kd6()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Kd8()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6
            (6.Kc6 Kd8)';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
            '2k5/R7/2K5/8/8/8/8/8 b - -',
            '3k4/R7/2K5/8/8/8/8/8 w - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Kb8()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6
            (6.Kc6 Kd8)
            6...Kb8';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
            '2k5/R7/2K5/8/8/8/8/8 b - -',
            '3k4/R7/2K5/8/8/8/8/8 w - -',
            '1k6/R7/3K4/8/8/8/8/8 w - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ra7_Kg8__Kd8_Ra8()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6
            (6.Kc6 Kd8)
            6...Kb8
            (6...Kd8 7.Ra8#)';

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
            '2k5/R7/2K5/8/8/8/8/8 b - -',
            '3k4/R7/2K5/8/8/8/8/8 w - -',
            '1k6/R7/3K4/8/8/8/8/8 w - -',
            '3k4/R7/3K4/8/8/8/8/8 w - -',
            'R2k4/8/3K4/8/8/8/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ke2_Kd5__Kc4()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - -';

        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4';

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',

        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ke2_Kd5__Ke5_Rh5()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - -';

        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 3.Rh5+)';

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ke2_Kd5__Rh5()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - -';

        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 3.Rh5+)
            3.Rh5';

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
            '8/8/8/7R/2k5/4K3/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ke2_Kd5__Kb4_Kd3()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - -';

        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 3.Rh5+)
            3.Rh5
            (3...Kb4 4.Kd3)';

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
            '8/8/8/7R/2k5/4K3/8/8 b - -',
            '8/8/8/7R/1k6/4K3/8/8 w - -',
            '8/8/8/7R/1k6/3K4/8/8 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_chess_fundamentals_Ke2_Kd5__Ra1()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - -';

        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 3.Rh5+)
            3.Rh5
            (3...Kb4 4.Kd3)
            3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
            '8/8/8/7R/2k5/4K3/8/8 b - -',
            '8/8/8/7R/1k6/4K3/8/8 w - -',
            '8/8/8/7R/1k6/3K4/8/8 b - -',
            '8/8/8/7R/8/2k1K3/8/8 w - -',
            '8/8/8/8/7R/2k1K3/8/8 b - -',
            '8/8/8/8/7R/4K3/2k5/8 w - -',
            '8/8/8/8/2R5/4K3/2k5/8 b - -',
            '8/8/8/8/2R5/1k2K3/8/8 w - -',
            '8/8/8/8/2R5/1k1K4/8/8 b - -',
            '8/8/8/8/2R5/3K4/1k6/8 w - -',
            '8/8/8/8/1R6/3K4/1k6/8 b - -',
            '8/8/8/8/1R6/k2K4/8/8 w - -',
            '8/8/8/8/1R6/k1K5/8/8 b - -',
            '8/8/8/8/1R6/2K5/k7/8 w - -',
            '8/8/8/8/R7/2K5/k7/8 b - -',
            '8/8/8/8/R7/2K5/8/1k6 w - -',
            '8/8/8/R7/8/2K5/8/1k6 b - -',
            '8/8/8/R7/8/2K5/8/2k5 w - -',
            '8/8/8/8/8/2K5/8/R1k5 b - -',
        ];

        $board = (new StrToBoard($fen))->create();
        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_sicilian_defense_e4_c5_Nf3()
    {
        $movetext = '1. e4 c5
            (2.Nf3 d6)
            (2.Nf3 Nc6)
            (2.Nf3 e6)';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_sicilian_defense_e4_c5_Nf3_d6()
    {
        $movetext = '1. e4 c5
            (2.Nf3 d6)
            (2.Nf3 Nc6)
            (2.Nf3 e6)
            (2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6))';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3',
            'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function breakdown_sicilian()
    {
        $movetext = '1.e4 c5 {foo} (2.Nf3 d6 {foobar}) (2.Nf3 Nc6)';

        $expected = [
          '1.e4 c5 {foo}',
          '2.Nf3 d6 {foobar}',
          '2.Nf3 Nc6',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function get_fen_sicilian_uncommented()
    {
        $movetext = '1.e4 c5 (2.Nf3 d6) (2.Nf3 Nc6)';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_sicilian_commented()
    {
        $movetext = '1.e4 c5 {foo} (2.Nf3 d6 {foobar}) (2.Nf3 Nc6)';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_open_sicilian_tutorial_uncommented()
    {
        $movetext = "1.e4 c5
            (2.Nf3 (2... Nc6) (2... e6)
                (2... d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3
                    (5...a6)
                    (5...g6)
                    (5...Nc6)
                    (5...e6)
                )
            )";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3',
            'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_open_sicilian_tutorial_commented()
    {
        $movetext = "1.e4 c5 {enters the Sicilian Defense, the most popular and best-scoring response to White's first move.}
            (2.Nf3 {is played in about 80% of Master-level games after which there are three main options for Black.} (2... Nc6) (2... e6)
                (2... d6 {is Black's most common move.} 3.d4 {lines are collectively known as the Open Sicilian.} cxd4 4.Nxd4 Nf6 5.Nc3 {allows Black choose between four major variations: the Najdorf, Dragon, Classical and Scheveningen.}
                    (5...a6 {is played in the Najdorf variation.})
                    (5...g6 {is played in the Dragon variation.})
                    (5...Nc6 {is played in the Classical variation.})
                    (5...e6 {is played in the Scheveningen variation.})
                )
            )";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3',
            'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_01()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = "1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
            (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6 Kb8
            (6...Kd8 7.Ra8#)
            7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#";

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
            '1k6/R7/3K4/8/8/8/8/8 w - -',
            '3k4/R7/3K4/8/8/8/8/8 w - -',
            'R2k4/8/3K4/8/8/8/8/8 b - -',
            '1k6/2R5/3K4/8/8/8/8/8 b - -',
            'k7/2R5/3K4/8/8/8/8/8 w - -',
            'k7/2R5/2K5/8/8/8/8/8 b - -',
            '1k6/2R5/2K5/8/8/8/8/8 w - -',
            '1k6/2R5/1K6/8/8/8/8/8 b - -',
            'k7/2R5/1K6/8/8/8/8/8 w - -',
            'k1R5/8/1K6/8/8/8/8/8 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_01_commented()
    {
        $fen = '7k/8/8/8/8/8/8/R6K w - -';

        $movetext = "{The ending Rook and King against King. The principle is to drive the opposing King to the last line on any side of the board.}
            1.Ra7 {demonstrates the power of the Rook.} Kg8 {is the only possible move because the Black King has been confined to the last rank.} 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3 Ke8 4.Ke4 {keeps the King on the same rank, or, as in this case, file, as the opposing King. This is the general principle for a beginner to follow.} Kd8 5.Kd5 Kc8
              (5...Ke8 {is a continuation that ends in checkmate if the Black King is ultimately forced to move in front of the White King.} 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
            6.Kd6 {is the quickest way to deliver checkmate after 5...Kc8. Once the King is brought to the sixth rank, it is better to place it not on the same file, but on the one next to it towards the center.} Kb8
              (6...Kd8 {is checkmate in one move.} 7.Ra8#)
            7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8# {It has taken exactly ten moves to mate from the original position.}";

        $expected = [
            '7k/8/8/8/8/8/8/R6K w - -',
            '7k/R7/8/8/8/8/8/7K b - -',
            '6k1/R7/8/8/8/8/8/7K w - -',
            '6k1/R7/8/8/8/8/6K1/8 b - -',
            '5k2/R7/8/8/8/8/6K1/8 w - -',
            '5k2/R7/8/8/8/5K2/8/8 b - -',
            '4k3/R7/8/8/8/5K2/8/8 w - -',
            '4k3/R7/8/8/4K3/8/8/8 b - -',
            '3k4/R7/8/8/4K3/8/8/8 w - -',
            '3k4/R7/8/3K4/8/8/8/8 b - -',
            '2k5/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/8/3K4/8/8/8/8 w - -',
            '4k3/R7/3K4/8/8/8/8/8 b - -',
            '5k2/R7/3K4/8/8/8/8/8 w - -',
            '5k2/R7/4K3/8/8/8/8/8 b - -',
            '6k1/R7/4K3/8/8/8/8/8 w - -',
            '6k1/R7/5K2/8/8/8/8/8 b - -',
            '7k/R7/5K2/8/8/8/8/8 w - -',
            '7k/R7/6K1/8/8/8/8/8 b - -',
            '6k1/R7/6K1/8/8/8/8/8 w - -',
            'R5k1/8/6K1/8/8/8/8/8 b - -',
            '2k5/R7/3K4/8/8/8/8/8 b - -',
            '1k6/R7/3K4/8/8/8/8/8 w - -',
            '3k4/R7/3K4/8/8/8/8/8 w - -',
            'R2k4/8/3K4/8/8/8/8/8 b - -',
            '1k6/2R5/3K4/8/8/8/8/8 b - -',
            'k7/2R5/3K4/8/8/8/8/8 w - -',
            'k7/2R5/2K5/8/8/8/8/8 b - -',
            '1k6/2R5/2K5/8/8/8/8/8 w - -',
            '1k6/2R5/1K6/8/8/8/8/8 b - -',
            'k7/2R5/1K6/8/8/8/8/8 w - -',
            'k1R5/8/1K6/8/8/8/8/8 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_02()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - - 0 1';

        $movetext = "1.Ke2 Kd5 2.Ke3 Kc4
            (2...Ke5 Rh5+)
            3.Rh5 Kc3
            (3...Kb4 4.Kd3)
            4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#";

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
            '8/8/8/7R/2k5/4K3/8/8 b - -',
            '8/8/8/7R/8/2k1K3/8/8 w - -',
            '8/8/8/7R/1k6/4K3/8/8 w - -',
            '8/8/8/7R/1k6/3K4/8/8 b - -',
            '8/8/8/8/7R/2k1K3/8/8 b - -',
            '8/8/8/8/7R/4K3/2k5/8 w - -',
            '8/8/8/8/2R5/4K3/2k5/8 b - -',
            '8/8/8/8/2R5/1k2K3/8/8 w - -',
            '8/8/8/8/2R5/1k1K4/8/8 b - -',
            '8/8/8/8/2R5/3K4/1k6/8 w - -',
            '8/8/8/8/1R6/3K4/1k6/8 b - -',
            '8/8/8/8/1R6/k2K4/8/8 w - -',
            '8/8/8/8/1R6/k1K5/8/8 b - -',
            '8/8/8/8/1R6/2K5/k7/8 w - -',
            '8/8/8/8/R7/2K5/k7/8 b - -',
            '8/8/8/8/R7/2K5/8/1k6 w - -',
            '8/8/8/R7/8/2K5/8/1k6 b - -',
            '8/8/8/R7/8/2K5/8/2k5 w - -',
            '8/8/8/8/8/2K5/8/R1k5 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_02_commented()
    {
        $fen = '8/8/8/4k3/8/8/8/4K2R w - - 0 1';

        $movetext = "1.Ke2 {Since the Black King is in the center of the board, the best way to proceed is to advance the White King.} Kd5 2.Ke3 {As the Rook has not yet come into play, it is better to advance the King straight into the center of the board, not in front, but to one side of the other King.} Kc4
            (2...Ke5 Rh5+)
            3.Rh5 Kc3
            (3...Kb4 4.Kd3)
            4.Rh4 {Keeping the King confined to as few squares as possible. Now the ending may continue as follows.} Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 {It should be noticed how often the White King has moved next to the Rook, not only to defend it, but also to reduce the mobility of the opposing King. Now White mates in three moves.} 9.Ra4+ Kb1 10.Ra5 {Or any square of the a-file, forcing the Black King in front of the White.} Kc1 11.Ra1#";

        $expected = [
            '8/8/8/4k3/8/8/8/4K2R w - -',
            '8/8/8/4k3/8/8/4K3/7R b - -',
            '8/8/8/3k4/8/8/4K3/7R w - -',
            '8/8/8/3k4/8/4K3/8/7R b - -',
            '8/8/8/8/2k5/4K3/8/7R w - -',
            '8/8/8/4k3/8/4K3/8/7R w - -',
            '8/8/8/4k2R/8/4K3/8/8 b - -',
            '8/8/8/7R/2k5/4K3/8/8 b - -',
            '8/8/8/7R/8/2k1K3/8/8 w - -',
            '8/8/8/7R/1k6/4K3/8/8 w - -',
            '8/8/8/7R/1k6/3K4/8/8 b - -',
            '8/8/8/8/7R/2k1K3/8/8 b - -',
            '8/8/8/8/7R/4K3/2k5/8 w - -',
            '8/8/8/8/2R5/4K3/2k5/8 b - -',
            '8/8/8/8/2R5/1k2K3/8/8 w - -',
            '8/8/8/8/2R5/1k1K4/8/8 b - -',
            '8/8/8/8/2R5/3K4/1k6/8 w - -',
            '8/8/8/8/1R6/3K4/1k6/8 b - -',
            '8/8/8/8/1R6/k2K4/8/8 w - -',
            '8/8/8/8/1R6/k1K5/8/8 b - -',
            '8/8/8/8/1R6/2K5/k7/8 w - -',
            '8/8/8/8/R7/2K5/k7/8 b - -',
            '8/8/8/8/R7/2K5/8/1k6 w - -',
            '8/8/8/R7/8/2K5/8/1k6 b - -',
            '8/8/8/R7/8/2K5/8/2k5 w - -',
            '8/8/8/8/8/2K5/8/R1k5 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_03()
    {
        $fen = '8/8/5k2/8/5K2/8/4P3/8 w - - 0 1';

        $movetext = "1.Ke4 Ke6 2.e3 Kf6 3.Kd5 Ke7
            (3...Kf5 4.e4+)
          4.Ke5 Kd7 5.Kf6 Ke8 6.e4 Kd7 7.e5
            (7.Kf7 Kd6)
          7...Ke8
            (7...Kd8 8.Kf7)
          8.Ke6
            (8.e6 Kf8)
          8...Kf8 9.Kd7";

        $expected = [
          '8/8/5k2/8/5K2/8/4P3/8 w - -',
          '8/8/5k2/8/4K3/8/4P3/8 b - -',
          '8/8/4k3/8/4K3/8/4P3/8 w - -',
          '8/8/4k3/8/4K3/4P3/8/8 b - -',
          '8/8/5k2/8/4K3/4P3/8/8 w - -',
          '8/8/5k2/3K4/8/4P3/8/8 b - -',
          '8/4k3/8/3K4/8/4P3/8/8 w - -',
          '8/8/8/3K1k2/8/4P3/8/8 w - -',
          '8/8/8/3K1k2/4P3/8/8/8 b - -',
          '8/4k3/8/4K3/8/4P3/8/8 b - -',
          '8/3k4/8/4K3/8/4P3/8/8 w - -',
          '8/3k4/5K2/8/8/4P3/8/8 b - -',
          '4k3/8/5K2/8/8/4P3/8/8 w - -',
          '4k3/8/5K2/8/4P3/8/8/8 b - -',
          '8/3k4/5K2/8/4P3/8/8/8 w - -',
          '8/3k4/5K2/4P3/8/8/8/8 b - -',
          '8/3k1K2/8/8/4P3/8/8/8 b - -',
          '8/5K2/3k4/8/4P3/8/8/8 w - -',
          '4k3/8/5K2/4P3/8/8/8/8 w - -',
          '3k4/8/5K2/4P3/8/8/8/8 w - -',
          '3k4/5K2/8/4P3/8/8/8/8 b - -',
          '4k3/8/4K3/4P3/8/8/8/8 b - -',
          '4k3/8/4PK2/8/8/8/8/8 b - -',
          '5k2/8/4PK2/8/8/8/8/8 w - -',
          '5k2/8/4K3/4P3/8/8/8/8 w - -',
          '5k2/3K4/8/4P3/8/8/8/8 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function chess_fundamentals_tutorial_simple_mates_03_commented()
    {
        $fen = '8/8/5k2/8/5K2/8/4P3/8 w - - 0 1';

        $movetext = "1.Ke4 Ke6 {does not allow White's king to advance.} 2.e3 {advances the pawn forcing Black to move away.} Kf6 3.Kd5 Ke7
            (3...Kf5 {forces White to advance the pawn to e4.} 4.e4+)
          4.Ke5 {is the continuation of this example.} Kd7 5.Kf6 Ke8 6.e4 {brings up the pawn within protection of the king.} Kd7 7.e5 {is the right thing to do after 6...Kd7}
            (7.Kf7 {is not the right move to make.} Kd6 {forces White to bring back its king to protect the pawn.})
          7...Ke8 {is the continuation of this example.}
            (7...Kd8 {is a variation that allows White to advance its pawn more quickly.} 8.Kf7 { and the advance of the pawn to e6, e7 and e8 will follow.})
          8.Ke6 {is therefore the right thing to do after 7...Ke8}
            (8.e6 {is a blunder that allows Black to draw.} Kf8 {draws the game.})
          8...Kf8 9.Kd7";

        $expected = [
          '8/8/5k2/8/5K2/8/4P3/8 w - -',
          '8/8/5k2/8/4K3/8/4P3/8 b - -',
          '8/8/4k3/8/4K3/8/4P3/8 w - -',
          '8/8/4k3/8/4K3/4P3/8/8 b - -',
          '8/8/5k2/8/4K3/4P3/8/8 w - -',
          '8/8/5k2/3K4/8/4P3/8/8 b - -',
          '8/4k3/8/3K4/8/4P3/8/8 w - -',
          '8/8/8/3K1k2/8/4P3/8/8 w - -',
          '8/8/8/3K1k2/4P3/8/8/8 b - -',
          '8/4k3/8/4K3/8/4P3/8/8 b - -',
          '8/3k4/8/4K3/8/4P3/8/8 w - -',
          '8/3k4/5K2/8/8/4P3/8/8 b - -',
          '4k3/8/5K2/8/8/4P3/8/8 w - -',
          '4k3/8/5K2/8/4P3/8/8/8 b - -',
          '8/3k4/5K2/8/4P3/8/8/8 w - -',
          '8/3k4/5K2/4P3/8/8/8/8 b - -',
          '8/3k1K2/8/8/4P3/8/8/8 b - -',
          '8/5K2/3k4/8/4P3/8/8/8 w - -',
          '4k3/8/5K2/4P3/8/8/8/8 w - -',
          '3k4/8/5K2/4P3/8/8/8/8 w - -',
          '3k4/5K2/8/4P3/8/8/8/8 b - -',
          '4k3/8/4K3/4P3/8/8/8/8 b - -',
          '4k3/8/4PK2/8/8/8/8/8 b - -',
          '5k2/8/4PK2/8/8/8/8/8 w - -',
          '5k2/8/4K3/4P3/8/8/8/8 w - -',
          '5k2/3K4/8/4P3/8/8/8/8 b - -',
        ];

        $board = FenToBoard::create($fen);

        $ravPlay = (new RavPlay($movetext, $board))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nf3_commented()
    {
        $movetext = "1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack }";

        $expected = [
          'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nf3_dxe4_commented()
    {
        $movetext = "1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack } 3...dxe4";

        $expected = [
          'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
          'rnbqkbnr/pp2pppp/2p5/8/4p3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nf6_commented()
    {
        $movetext = "1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack } 3... dxe4 4. Nxe4 Nf6";

        $expected = [
          'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
          'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
          'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
          'rnbqkbnr/pp2pppp/2p5/8/4p3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -',
          'rnbqkbnr/pp2pppp/2p5/8/4N3/5N2/PPPP1PPP/R1BQKB1R b KQkq -',
          'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPP1PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nd6_commented()
    {
        $movetext = "1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack }
            3... dxe4 4. Nxe4 Nf6 5. Qe2 Nbd7 { 159.99 }
                (5... Nxe4 6. Qxe4 Qd5 7. Qxd5 cxd5 8. c4 e6 9. cxd5 exd5 10. d4 { 0.26/12 })
            6. Nd6# 1-0";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4p3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4N3/5N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
            'r1bqkb1r/pp1npppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4n3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4Q3/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3q4/4Q3/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3Q4/8/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/8/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R b KQkq c3',
            'rnb1kb1r/pp3ppp/4p3/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/4p3/3P4/8/5N2/PP1P1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/8/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/3P4/5N2/PP3PPP/R1B1KB1R b KQkq d3',
            'r1bqkb1r/pp1npppp/2pN1n2/8/8/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nd6_commented_with_spaces()
    {
        $movetext = "1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack }
            3... dxe4 4. Nxe4 Nf6 5. Qe2 Nbd7 { 159.99 }
                ( 5... Nxe4 6. Qxe4 Qd5 7. Qxd5 cxd5 8. c4 e6 9. cxd5 exd5 10. d4 { 0.26/12 } )
            6. Nd6# 1-0";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4p3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4N3/5N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
            'r1bqkb1r/pp1npppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4n3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4Q3/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3q4/4Q3/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3Q4/8/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/8/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R b KQkq c3',
            'rnb1kb1r/pp3ppp/4p3/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/4p3/3P4/8/5N2/PP1P1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/8/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/3P4/5N2/PP3PPP/R1B1KB1R b KQkq d3',
            'r1bqkb1r/pp1npppp/2pN1n2/8/8/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function breakdown_with_parentheses_in_comments_e4_c6__Nd6()
    {
        $movetext = "{ Sjaak II 1.4.1 (x86_64) } 1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack }
            3... dxe4 4. Nxe4 Nf6 5. Qe2 Nbd7 { 159.99 }
                ( 5... Nxe4 6. Qxe4 Qd5 7. Qxd5 cxd5 8. c4 e6 9. cxd5 exd5 10. d4 { 0.26/12 } )
            6. Nd6# 1-0";

        $expected = [
            '{Sjaak II 1.4.1 (x86_64)} 1.e4 c6 2.Nc3 d5 3.Nf3 {B10 Caro-Kann Defense: Two Knights Attack} 3...dxe4 4.Nxe4 Nf6 5.Qe2 Nbd7 {159.99}',
            '5...Nxe4 6.Qxe4 Qd5 7.Qxd5 cxd5 8.c4 e6 9.cxd5 exd5 10.d4 {0.26/12}',
            '6.Nd6#',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function get_fen_e4_c6__Nd6_with_parentheses_in_comments()
    {
        $movetext = "{ Sjaak II 1.4.1 (x86_64) } 1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack }
            3... dxe4 4. Nxe4 Nf6 5. Qe2 Nbd7 { 159.99 }
                ( 5... Nxe4 6. Qxe4 Qd5 7. Qxd5 cxd5 8. c4 e6 9. cxd5 exd5 10. d4 { 0.26/12 } )
            6. Nd6# 1-0";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pp1ppppp/2p5/8/4P3/2N5/PPPP1PPP/R1BQKBNR b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N5/PPPP1PPP/R1BQKBNR w KQkq d6',
            'rnbqkbnr/pp2pppp/2p5/3p4/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4p3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/2p5/8/4N3/5N2/PPPP1PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPP1PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
            'r1bqkb1r/pp1npppp/2p2n2/8/4N3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4n3/5N2/PPPPQPPP/R1B1KB1R w KQkq -',
            'rnbqkb1r/pp2pppp/2p5/8/4Q3/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3q4/4Q3/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/2p5/3Q4/8/5N2/PPPP1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/8/5N2/PPPP1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp2pppp/8/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R b KQkq c3',
            'rnb1kb1r/pp3ppp/4p3/3p4/2P5/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/4p3/3P4/8/5N2/PP1P1PPP/R1B1KB1R b KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/8/5N2/PP1P1PPP/R1B1KB1R w KQkq -',
            'rnb1kb1r/pp3ppp/8/3p4/3P4/5N2/PP3PPP/R1B1KB1R b KQkq d3',
            'r1bqkb1r/pp1npppp/2pN1n2/8/8/5N2/PPPPQPPP/R1B1KB1R b KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function validate_e4_c5__e6()
    {
        $movetext = '1.e4 $1 c5 (2.Nf3 (2...Nc6) (2...e6) (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6)))';

        $expected = '1.e4 c5 (2.Nf3 (2...Nc6) (2...e6) (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6)))';

        $ravPlay = new RavPlay($movetext);

        $ravPlay->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->filtered($comments = false, $nags = false));
    }

    /**
     * @test
     */
    public function get_fen_e4_c5__e6()
    {
        $movetext = '1.e4 $1 c5 (2.Nf3 (2...Nc6) (2...e6) (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6)))';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6',
            'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3',
            'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -',
            'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -',
            'rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
            'rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function breakdown_e4_e5__Kh8()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 O-O 8. Nc3 d6 9. a3 Be6 10. Bg5
            (10. Be3 d5 (10... Bxb3 11. cxb3 d5 12. exd5 Nxd5 13. Rc1 $14) 11. exd5 Nxd5 12. Nxd5 Bxd5 13. Rc1 Nd4 14. Bxd4 exd4 15. Bxd5 Qxd5 16. Re1 Bf6 17. Qe2 h6)
            10... Kh8";

        $expected = [
            '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 O-O 8.Nc3 d6 9.a3 Be6 10.Bg5',
            '10.Be3 d5',
            '10...Bxb3 11.cxb3 d5 12.exd5 Nxd5 13.Rc1 $14',
            '11.exd5 Nxd5 12.Nxd5 Bxd5 13.Rc1 Nd4 14.Bxd4 exd4 15.Bxd5 Qxd5 16.Re1 Bf6 17.Qe2 h6',
            '10...Kh8',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_e4_e5__Qe2()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 O-O 8. Nc3 d6 9. a3 Be6 10. Bg5
            (10. Be3 d5
            (10... Bxb3 11. cxb3 d5 12. exd5 Nxd5 13. Rc1 $14)
            11. exd5 Nxd5 12. Nxd5 Bxd5 13. Rc1 Nd4 14. Bxd4 exd4 15. Bxd5 Qxd5 16. Re1 Bf6 17. Qe2 h6)
            10... Kh8 $5
            (10... Bg4 $5 11. Be3)
            (10... Rb8 11. h3 h6 12. Bd2 d5 13. exd5 Nxd5 14. Re1 Bf6 15. Bxd5 Bxd5 16. Nxd5 Qxd5 17. Qe2)";

        $expected = [
            '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 O-O 8.Nc3 d6 9.a3 Be6 10.Bg5',
            '10.Be3 d5',
            '10...Bxb3 11.cxb3 d5 12.exd5 Nxd5 13.Rc1 $14',
            '11.exd5 Nxd5 12.Nxd5 Bxd5 13.Rc1 Nd4 14.Bxd4 exd4 15.Bxd5 Qxd5 16.Re1 Bf6 17.Qe2 h6',
            '10...Kh8 $5',
            '10...Bg4 $5 11.Be3',
            '10...Rb8 11.h3 h6 12.Bd2 d5 13.exd5 Nxd5 14.Re1 Bf6 15.Bxd5 Bxd5 16.Nxd5 Qxd5 17.Qe2',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_e4_e5__h3_Ng8()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 O-O 8. Nc3 d6 9. a3 Be6 10. Bg5
            (10. Be3 d5
            (10... Bxb3 11. cxb3 d5 12. exd5 Nxd5 13. Rc1 $14)
            11. exd5 Nxd5 12. Nxd5 Bxd5 13. Rc1 Nd4 14. Bxd4 exd4 15. Bxd5 Qxd5 16. Re1 Bf6 17. Qe2 h6)
            10... Kh8 $5
            (10... Bg4 $5 11. Be3)
            (10... Rb8 11. h3 h6 12. Bd2 d5 13. exd5 Nxd5 14. Re1 Bf6 15. Bxd5 Bxd5 16. Nxd5 Qxd5 17. Qe2)
            11. h3 Ng8";

        $expected = [
            '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 O-O 8.Nc3 d6 9.a3 Be6 10.Bg5',
            '10.Be3 d5',
            '10...Bxb3 11.cxb3 d5 12.exd5 Nxd5 13.Rc1 $14',
            '11.exd5 Nxd5 12.Nxd5 Bxd5 13.Rc1 Nd4 14.Bxd4 exd4 15.Bxd5 Qxd5 16.Re1 Bf6 17.Qe2 h6',
            '10...Kh8 $5',
            '10...Bg4 $5 11.Be3',
            '10...Rb8 11.h3 h6 12.Bd2 d5 13.exd5 Nxd5 14.Re1 Bf6 15.Bxd5 Bxd5 16.Nxd5 Qxd5 17.Qe2',
            '11.h3 Ng8',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getRavMovetext()->getBreakdown());
    }

    /**
     * @test
     */
    public function get_fen_e4_e5__h3()
    {
        $movetext = "1. e4 e5 $1 2. Nf3 $1 Nc6 $1 3. Bb5 $1
            (3. Bc4 Bc5 $1)
            3... a6
            (3... Nf6 4. O-O Nxe4 5. d4 Nd6 6. Bxc6 dxc6 7. dxe5 Nf5 8.Qxd8+ Kxd8 $14)
            4. Ba4
            (4. Bxc6 dxc6 5. O-O f6 6. d4 exd4 7. Nxd4 c5 8. Ne2 Qxd1 9. Rxd1)
            4... Nf6
            (4... d6 5. c3 $1)
            5.O-O
            (5. d3 b5 6. Bb3 Bc5)
            5... Be7
            (5... b5 6. Bb3 Bc5)
            (5... Nxe4 6. d4 b5 7. Bb3 d5 8. dxe5 Be6 9. Nbd2)
            6. d3 $142 $5 (6. Re1 b5 7. Bb3 O-O $1 8. a4 (8. c3 d5 $1) 8... b4 9. d4 d6 10. dxe5 dxe5)
            6... b5
            (6... d6 7. c3 O-O 8. h3 b5 9.Bc2)
            7. Bb3 d6
            (7... O-O 8. a4 b4 9. a5 $1 d6 10. Bg5 $5)
            8. a3
            (8. a4 Bd7)
            8... O-O 9. Nc3
            (9.h3)";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -',
            'r1bqkb1r/pppp1ppp/2n5/1B2p3/4n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/pppp1ppp/2n5/1B2p3/3Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq d3',
            'r1bqkb1r/pppp1ppp/2nn4/1B2p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/pppp1ppp/2Bn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqkb1r/ppp2ppp/2pn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/ppp2ppp/2pn4/4P3/8/5N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bQkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 b kq -',
            'r1bk1b1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 w - -',
            'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkbnr/1ppp1ppp/p1B5/4p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkbnr/1pp2ppp/p1p5/4p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkbnr/1pp2ppp/p1p5/4p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -',
            'r1bqkbnr/1pp3pp/p1p2p2/4p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqkbnr/1pp3pp/p1p2p2/4p3/3PP3/5N2/PPP2PPP/RNBQ1RK1 b kq d3',
            'r1bqkbnr/1pp3pp/p1p2p2/8/3pP3/5N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bqkbnr/1pp3pp/p1p2p2/8/3NP3/8/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqkbnr/1pp3pp/p4p2/2p5/3NP3/8/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bqkbnr/1pp3pp/p4p2/2p5/4P3/8/PPP1NPPP/RNBQ1RK1 b kq -',
            'r1b1kbnr/1pp3pp/p4p2/2p5/4P3/8/PPP1NPPP/RNBq1RK1 w kq -',
            'r1b1kbnr/1pp3pp/p4p2/2p5/4P3/8/PPP1NPPP/RNBR2K1 b kq -',
            'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkbnr/1pp2ppp/p1np4/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkbnr/1pp2ppp/p1np4/4p3/B3P3/2P2N2/PP1P1PPP/RNBQK2R b KQkq -',
            'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -',
            'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/3P1N2/PPP2PPP/RNBQK2R b KQkq -',
            'r1bqkb1r/2pp1ppp/p1n2n2/1p2p3/B3P3/3P1N2/PPP2PPP/RNBQK2R w KQkq b6',
            'r1bqkb1r/2pp1ppp/p1n2n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQK2R b KQkq -',
            'r1bqk2r/2pp1ppp/p1n2n2/1pb1p3/4P3/1B1P1N2/PPP2PPP/RNBQK2R w KQkq -',
            'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/2pp1ppp/p1n2n2/1p2p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 w kq b6',
            'r1bqkb1r/2pp1ppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/2pp1ppp/p1n2n2/1pb1p3/4P3/1B3N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/1ppp1ppp/p1n5/4p3/B3n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqkb1r/1ppp1ppp/p1n5/4p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq d3',
            'r1bqkb1r/2pp1ppp/p1n5/1p2p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 w kq b6',
            'r1bqkb1r/2pp1ppp/p1n5/1p2p3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqkb1r/2p2ppp/p1n5/1p1pp3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 w kq d6',
            'r1bqkb1r/2p2ppp/p1n5/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPPN1PPP/R1BQ1RK1 b kq -',
            'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/3P1N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 b kq -',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 w kq b6',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 b kq -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 w - -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQR1K1 b - a3',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 b - -',
            'r1bq1rk1/2p1bppp/p1n2n2/1p1pp3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 w - d6',
            'r1bq1rk1/2ppbppp/p1n2n2/4p3/Pp2P3/1B3N2/1PPP1PPP/RNBQR1K1 w - -',
            'r1bq1rk1/2ppbppp/p1n2n2/4p3/Pp1PP3/1B3N2/1PP2PPP/RNBQR1K1 b - d3',
            'r1bq1rk1/2p1bppp/p1np1n2/4p3/Pp1PP3/1B3N2/1PP2PPP/RNBQR1K1 w - -',
            'r1bq1rk1/2p1bppp/p1np1n2/4P3/Pp2P3/1B3N2/1PP2PPP/RNBQR1K1 b - -',
            'r1bq1rk1/2p1bppp/p1n2n2/4p3/Pp2P3/1B3N2/1PP2PPP/RNBQR1K1 w - -',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/B3P3/3P1N2/PPP2PPP/RNBQ1RK1 w kq b6',
            'r1bqk2r/1pp1bppp/p1np1n2/4p3/B3P3/3P1N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bqk2r/1pp1bppp/p1np1n2/4p3/B3P3/2PP1N2/PP3PPP/RNBQ1RK1 b kq -',
            'r1bq1rk1/1pp1bppp/p1np1n2/4p3/B3P3/2PP1N2/PP3PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/1pp1bppp/p1np1n2/4p3/B3P3/2PP1N1P/PP3PP1/RNBQ1RK1 b - -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/B3P3/2PP1N1P/PP3PP1/RNBQ1RK1 w - b6',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/2PP1N1P/PPB2PP1/RNBQ1RK1 b - -',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQ1RK1 w kq -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/P3P3/1B1P1N2/1PP2PPP/RNBQ1RK1 b - a3',
            'r1bq1rk1/2ppbppp/p1n2n2/4p3/Pp2P3/1B1P1N2/1PP2PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/2ppbppp/p1n2n2/P3p3/1p2P3/1B1P1N2/1PP2PPP/RNBQ1RK1 b - -',
            'r1bq1rk1/2p1bppp/p1np1n2/P3p3/1p2P3/1B1P1N2/1PP2PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/2p1bppp/p1np1n2/P3p1B1/1p2P3/1B1P1N2/1PP2PPP/RN1Q1RK1 b - -',
            'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/PB1P1N2/1PP2PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/2p1bppp/p1np1n2/1p2p3/P3P3/1B1P1N2/1PP2PPP/RNBQ1RK1 b kq a3',
            'r2qk2r/2pbbppp/p1np1n2/1p2p3/P3P3/1B1P1N2/1PP2PPP/RNBQ1RK1 w kq -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PB1P1N2/1PP2PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PBNP1N2/1PP2PPP/R1BQ1RK1 b - -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PB1P1N1P/1PP2PP1/RNBQ1RK1 b - -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }

    /**
     * @test
     */
    public function get_fen_e4_e5__Rg7()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 O-O 8. Nc3 d6 9. a3 Be6 10. Bg5
            (10. Be3 d5
                (10... Bxb3 11. cxb3 d5 12. exd5 Nxd5 13. Rc1 $14)
            11. exd5 Nxd5 12. Nxd5 Bxd5 13. Rc1 Nd4 14. Bxd4 exd4 15. Bxd5 Qxd5 16. Re1 Bf6 17. Qe2 h6
            )
            10... Kh8 $5
            (10... Bg4 $5 11. Be3)
            (10... Rb8 11. h3 h6 12. Bd2 d5 13. exd5 Nxd5 14. Re1 Bf6 15. Bxd5 Bxd5 16. Nxd5 Qxd5 17. Qe2)
            11. h3 Ng8 12. Be3 Qd7
            (12... Bxb3 $5 13. cxb3 f5 14. Qc2 Qd7)
            13. Bxe6
            (13. Nd5 $142 Rac8
                (13... f5 $140 14. Nxc7)
                14. a4 f5 15. axb5 axb5 16. exf5 Rxf5 17. c4 $1 $14
            )
            13... fxe6 14. Ne2 Nf6 15. Ng3 a5 16. a4 b4 $11 17. c3 d5 18. Qc2 Bd6 19. Rac1
            (19. c4 $5)
            19... Rab8 20. Rfd1 Qe8 21. Qe2 h6 22. Re1 Kg8 23. c4 d4 24. Bxd4 Nxd4
            25. Nxd4 Nd7 26. Nf3 Nc5 27. Ra1 Nb3 28. Ra2 Bc5 29. Rf1 Qg6 30. Kh2 Bd4
            31. Qd1 Rxf3 32. Qxf3 Nd2 33. Qe2 Nxf1+ 34. Nxf1 b3 35. Ra3 Qf6 36. Kg1 Qg5
            37. Ra1 Qe7 38. Nh2 Bc5 39. Nf3 Qd6 40. Qd2 Bb4 41. Qd1 Bc5 42. Qd2 Bb4
            43. c5 Qxc5 44. Qd1 Rb6 45. Rc1 Qd6 46. Rc4 Ba3 47. bxa3 b2 48. Qb1 Rb3
            49. Rc2 Qxd3 50. Nd2 Rb6 51. Kh2 Kh7 52. Rxb2 Qxd2 53. Rxd2 Rxb1 54. Rc2
            Kg6 55. Rxc7 Rd1 56. Rc5 Rd4 57. Rxe5 Kf6 58. Rxa5 Rxe4 59. Ra8 Kf7 60. Kg3 Rc4
            61. Kf3 Rc3+ 62. Ke4 Rxa3 63. a5 Ra2 64. a6 Rxf2 65. Rc8 Ra2 66. Rc7+ Kf6 67.
            a7 Ra4+ 68. Kd3 e5 69. Kc3 e4 70. Kb3 Ra1 71. Kc4 e3 72. Kd3 Ra3+ 73. Ke2 h5
            74. h4 g6 75. Kf3 Ke6 76. Rg7 Kf6 77. Rb7 Ke6 78. Rg7 1/2-1/2";

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 w kq -',
            'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/3P1N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/B3P3/3P1N2/PPP2PPP/RNBQ1RK1 w kq b6',
            'r1bqk2r/2ppbppp/p1n2n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQ1RK1 b kq -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/4P3/1B1P1N2/PPP2PPP/RNBQ1RK1 w - -',
            'r1bq1rk1/2ppbppp/p1n2n2/1p2p3/4P3/1BNP1N2/PPP2PPP/R1BQ1RK1 b - -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/1BNP1N2/PPP2PPP/R1BQ1RK1 w - -',
            'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PBNP1N2/1PP2PPP/R1BQ1RK1 b - -',
            'r2q1rk1/2p1bppp/p1npbn2/1p2p3/4P3/PBNP1N2/1PP2PPP/R1BQ1RK1 w - -',
            'r2q1rk1/2p1bppp/p1npbn2/1p2p1B1/4P3/PBNP1N2/1PP2PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1npbn2/1p2p3/4P3/PBNPBN2/1PP2PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n1bn2/1p1pp3/4P3/PBNPBN2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PbNPBN2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1np1n2/1p2p3/4P3/PPNPBN2/1P3PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n2n2/1p1pp3/4P3/PPNPBN2/1P3PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1n2n2/1p1Pp3/8/PPNPBN2/1P3PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n5/1p1np3/8/PPNPBN2/1P3PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1n5/1p1np3/8/PPNPBN2/1P3PPP/2RQ1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n1bn2/1p1Pp3/8/PBNPBN2/1PP2PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n1b3/1p1np3/8/PBNPBN2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1n1b3/1p1Np3/8/PB1PBN2/1PP2PPP/R2Q1RK1 b - -',
            'r2q1rk1/2p1bppp/p1n5/1p1bp3/8/PB1PBN2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1n5/1p1bp3/8/PB1PBN2/1PP2PPP/2RQ1RK1 b - -',
            'r2q1rk1/2p1bppp/p7/1p1bp3/3n4/PB1PBN2/1PP2PPP/2RQ1RK1 w - -',
            'r2q1rk1/2p1bppp/p7/1p1bp3/3B4/PB1P1N2/1PP2PPP/2RQ1RK1 b - -',
            'r2q1rk1/2p1bppp/p7/1p1b4/3p4/PB1P1N2/1PP2PPP/2RQ1RK1 w - -',
            'r2q1rk1/2p1bppp/p7/1p1B4/3p4/P2P1N2/1PP2PPP/2RQ1RK1 b - -',
            'r4rk1/2p1bppp/p7/1p1q4/3p4/P2P1N2/1PP2PPP/2RQ1RK1 w - -',
            'r4rk1/2p1bppp/p7/1p1q4/3p4/P2P1N2/1PP2PPP/2RQR1K1 b - -',
            'r4rk1/2p2ppp/p4b2/1p1q4/3p4/P2P1N2/1PP2PPP/2RQR1K1 w - -',
            'r4rk1/2p2ppp/p4b2/1p1q4/3p4/P2P1N2/1PP1QPPP/2R1R1K1 b - -',
            'r4rk1/2p2pp1/p4b1p/1p1q4/3p4/P2P1N2/1PP1QPPP/2R1R1K1 w - -',
            'r2q1r1k/2p1bppp/p1npbn2/1p2p1B1/4P3/PBNP1N2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1np1n2/1p2p1B1/4P1b1/PBNP1N2/1PP2PPP/R2Q1RK1 w - -',
            'r2q1rk1/2p1bppp/p1np1n2/1p2p3/4P1b1/PBNPBN2/1PP2PPP/R2Q1RK1 b - -',
            '1r1q1rk1/2p1bppp/p1npbn2/1p2p1B1/4P3/PBNP1N2/1PP2PPP/R2Q1RK1 w - -',
            '1r1q1rk1/2p1bppp/p1npbn2/1p2p1B1/4P3/PBNP1N1P/1PP2PP1/R2Q1RK1 b - -',
            '1r1q1rk1/2p1bpp1/p1npbn1p/1p2p1B1/4P3/PBNP1N1P/1PP2PP1/R2Q1RK1 w - -',
            '1r1q1rk1/2p1bpp1/p1npbn1p/1p2p3/4P3/PBNP1N1P/1PPB1PP1/R2Q1RK1 b - -',
            '1r1q1rk1/2p1bpp1/p1n1bn1p/1p1pp3/4P3/PBNP1N1P/1PPB1PP1/R2Q1RK1 w - -',
            '1r1q1rk1/2p1bpp1/p1n1bn1p/1p1Pp3/8/PBNP1N1P/1PPB1PP1/R2Q1RK1 b - -',
            '1r1q1rk1/2p1bpp1/p1n1b2p/1p1np3/8/PBNP1N1P/1PPB1PP1/R2Q1RK1 w - -',
            '1r1q1rk1/2p1bpp1/p1n1b2p/1p1np3/8/PBNP1N1P/1PPB1PP1/R2QR1K1 b - -',
            '1r1q1rk1/2p2pp1/p1n1bb1p/1p1np3/8/PBNP1N1P/1PPB1PP1/R2QR1K1 w - -',
            '1r1q1rk1/2p2pp1/p1n1bb1p/1p1Bp3/8/P1NP1N1P/1PPB1PP1/R2QR1K1 b - -',
            '1r1q1rk1/2p2pp1/p1n2b1p/1p1bp3/8/P1NP1N1P/1PPB1PP1/R2QR1K1 w - -',
            '1r1q1rk1/2p2pp1/p1n2b1p/1p1Np3/8/P2P1N1P/1PPB1PP1/R2QR1K1 b - -',
            '1r3rk1/2p2pp1/p1n2b1p/1p1qp3/8/P2P1N1P/1PPB1PP1/R2QR1K1 w - -',
            '1r3rk1/2p2pp1/p1n2b1p/1p1qp3/8/P2P1N1P/1PPBQPP1/R3R1K1 b - -',
            'r2q1r1k/2p1bppp/p1npbn2/1p2p1B1/4P3/PBNP1N1P/1PP2PP1/R2Q1RK1 b - -',
            'r2q1rnk/2p1bppp/p1npb3/1p2p1B1/4P3/PBNP1N1P/1PP2PP1/R2Q1RK1 w - -',
            'r2q1rnk/2p1bppp/p1npb3/1p2p3/4P3/PBNPBN1P/1PP2PP1/R2Q1RK1 b - -',
            'r4rnk/2pqbppp/p1npb3/1p2p3/4P3/PBNPBN1P/1PP2PP1/R2Q1RK1 w - -',
            'r2q1rnk/2p1bppp/p1np4/1p2p3/4P3/PbNPBN1P/1PP2PP1/R2Q1RK1 w - -',
            'r2q1rnk/2p1bppp/p1np4/1p2p3/4P3/PPNPBN1P/1P3PP1/R2Q1RK1 b - -',
            'r2q1rnk/2p1b1pp/p1np4/1p2pp2/4P3/PPNPBN1P/1P3PP1/R2Q1RK1 w - f6',
            'r2q1rnk/2p1b1pp/p1np4/1p2pp2/4P3/PPNPBN1P/1PQ2PP1/R4RK1 b - -',
            'r4rnk/2pqb1pp/p1np4/1p2pp2/4P3/PPNPBN1P/1PQ2PP1/R4RK1 w - -',
            'r4rnk/2pqbppp/p1npB3/1p2p3/4P3/P1NPBN1P/1PP2PP1/R2Q1RK1 b - -',
            'r4rnk/2pqbppp/p1npb3/1p1Np3/4P3/PB1PBN1P/1PP2PP1/R2Q1RK1 b - -',
            '2r2rnk/2pqbppp/p1npb3/1p1Np3/4P3/PB1PBN1P/1PP2PP1/R2Q1RK1 w - -',
            'r4rnk/2pqb1pp/p1npb3/1p1Npp2/4P3/PB1PBN1P/1PP2PP1/R2Q1RK1 w - f6',
            'r4rnk/2Nqb1pp/p1npb3/1p2pp2/4P3/PB1PBN1P/1PP2PP1/R2Q1RK1 b - -',
            '2r2rnk/2pqbppp/p1npb3/1p1Np3/P3P3/1B1PBN1P/1PP2PP1/R2Q1RK1 b - -',
            '2r2rnk/2pqb1pp/p1npb3/1p1Npp2/P3P3/1B1PBN1P/1PP2PP1/R2Q1RK1 w - f6',
            '2r2rnk/2pqb1pp/p1npb3/1P1Npp2/4P3/1B1PBN1P/1PP2PP1/R2Q1RK1 b - -',
            '2r2rnk/2pqb1pp/2npb3/1p1Npp2/4P3/1B1PBN1P/1PP2PP1/R2Q1RK1 w - -',
            '2r2rnk/2pqb1pp/2npb3/1p1NpP2/8/1B1PBN1P/1PP2PP1/R2Q1RK1 b - -',
            '2r3nk/2pqb1pp/2npb3/1p1Npr2/8/1B1PBN1P/1PP2PP1/R2Q1RK1 w - -',
            '2r3nk/2pqb1pp/2npb3/1p1Npr2/2P5/1B1PBN1P/1P3PP1/R2Q1RK1 b - c3',
            'r4rnk/2pqb1pp/p1npp3/1p2p3/4P3/P1NPBN1P/1PP2PP1/R2Q1RK1 w - -',
            'r4rnk/2pqb1pp/p1npp3/1p2p3/4P3/P2PBN1P/1PP1NPP1/R2Q1RK1 b - -',
            'r4r1k/2pqb1pp/p1nppn2/1p2p3/4P3/P2PBN1P/1PP1NPP1/R2Q1RK1 w - -',
            'r4r1k/2pqb1pp/p1nppn2/1p2p3/4P3/P2PBNNP/1PP2PP1/R2Q1RK1 b - -',
            'r4r1k/2pqb1pp/2nppn2/pp2p3/4P3/P2PBNNP/1PP2PP1/R2Q1RK1 w - -',
            'r4r1k/2pqb1pp/2nppn2/pp2p3/P3P3/3PBNNP/1PP2PP1/R2Q1RK1 b - -',
            'r4r1k/2pqb1pp/2nppn2/p3p3/Pp2P3/3PBNNP/1PP2PP1/R2Q1RK1 w - -',
            'r4r1k/2pqb1pp/2nppn2/p3p3/Pp2P3/2PPBNNP/1P3PP1/R2Q1RK1 b - -',
            'r4r1k/2pqb1pp/2n1pn2/p2pp3/Pp2P3/2PPBNNP/1P3PP1/R2Q1RK1 w - -',
            'r4r1k/2pqb1pp/2n1pn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/R4RK1 b - -',
            'r4r1k/2pq2pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/R4RK1 w - -',
            'r4r1k/2pq2pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/2R2RK1 b - -',
            'r4r1k/2pq2pp/2nbpn2/p2pp3/PpP1P3/3PBNNP/1PQ2PP1/R4RK1 b - -',
            '1r3r1k/2pq2pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/2R2RK1 w - -',
            '1r3r1k/2pq2pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/2RR2K1 b - -',
            '1r2qr1k/2p3pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1PQ2PP1/2RR2K1 w - -',
            '1r2qr1k/2p3pp/2nbpn2/p2pp3/Pp2P3/2PPBNNP/1P2QPP1/2RR2K1 b - -',
            '1r2qr1k/2p3p1/2nbpn1p/p2pp3/Pp2P3/2PPBNNP/1P2QPP1/2RR2K1 w - -',
            '1r2qr1k/2p3p1/2nbpn1p/p2pp3/Pp2P3/2PPBNNP/1P2QPP1/2R1R1K1 b - -',
            '1r2qrk1/2p3p1/2nbpn1p/p2pp3/Pp2P3/2PPBNNP/1P2QPP1/2R1R1K1 w - -',
            '1r2qrk1/2p3p1/2nbpn1p/p2pp3/PpP1P3/3PBNNP/1P2QPP1/2R1R1K1 b - -',
            '1r2qrk1/2p3p1/2nbpn1p/p3p3/PpPpP3/3PBNNP/1P2QPP1/2R1R1K1 w - -',
            '1r2qrk1/2p3p1/2nbpn1p/p3p3/PpPBP3/3P1NNP/1P2QPP1/2R1R1K1 b - -',
            '1r2qrk1/2p3p1/3bpn1p/p3p3/PpPnP3/3P1NNP/1P2QPP1/2R1R1K1 w - -',
            '1r2qrk1/2p3p1/3bpn1p/p3p3/PpPNP3/3P2NP/1P2QPP1/2R1R1K1 b - -',
            '1r2qrk1/2pn2p1/3bp2p/p3p3/PpPNP3/3P2NP/1P2QPP1/2R1R1K1 w - -',
            '1r2qrk1/2pn2p1/3bp2p/p3p3/PpP1P3/3P1NNP/1P2QPP1/2R1R1K1 b - -',
            '1r2qrk1/2p3p1/3bp2p/p1n1p3/PpP1P3/3P1NNP/1P2QPP1/2R1R1K1 w - -',
            '1r2qrk1/2p3p1/3bp2p/p1n1p3/PpP1P3/3P1NNP/1P2QPP1/R3R1K1 b - -',
            '1r2qrk1/2p3p1/3bp2p/p3p3/PpP1P3/1n1P1NNP/1P2QPP1/R3R1K1 w - -',
            '1r2qrk1/2p3p1/3bp2p/p3p3/PpP1P3/1n1P1NNP/RP2QPP1/4R1K1 b - -',
            '1r2qrk1/2p3p1/4p2p/p1b1p3/PpP1P3/1n1P1NNP/RP2QPP1/4R1K1 w - -',
            '1r2qrk1/2p3p1/4p2p/p1b1p3/PpP1P3/1n1P1NNP/RP2QPP1/5RK1 b - -',
            '1r3rk1/2p3p1/4p1qp/p1b1p3/PpP1P3/1n1P1NNP/RP2QPP1/5RK1 w - -',
            '1r3rk1/2p3p1/4p1qp/p1b1p3/PpP1P3/1n1P1NNP/RP2QPPK/5R2 b - -',
            '1r3rk1/2p3p1/4p1qp/p3p3/PpPbP3/1n1P1NNP/RP2QPPK/5R2 w - -',
            '1r3rk1/2p3p1/4p1qp/p3p3/PpPbP3/1n1P1NNP/RP3PPK/3Q1R2 b - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/1n1P1rNP/RP3PPK/3Q1R2 w - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/1n1P1QNP/RP3PPK/5R2 b - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/3P1QNP/RP1n1PPK/5R2 w - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/3P2NP/RP1nQPPK/5R2 b - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/3P2NP/RP2QPPK/5n2 w - -',
            '1r4k1/2p3p1/4p1qp/p3p3/PpPbP3/3P3P/RP2QPPK/5N2 b - -',
            '1r4k1/2p3p1/4p1qp/p3p3/P1PbP3/1p1P3P/RP2QPPK/5N2 w - -',
            '1r4k1/2p3p1/4p1qp/p3p3/P1PbP3/Rp1P3P/1P2QPPK/5N2 b - -',
            '1r4k1/2p3p1/4pq1p/p3p3/P1PbP3/Rp1P3P/1P2QPPK/5N2 w - -',
            '1r4k1/2p3p1/4pq1p/p3p3/P1PbP3/Rp1P3P/1P2QPP1/5NK1 b - -',
            '1r4k1/2p3p1/4p2p/p3p1q1/P1PbP3/Rp1P3P/1P2QPP1/5NK1 w - -',
            '1r4k1/2p3p1/4p2p/p3p1q1/P1PbP3/1p1P3P/1P2QPP1/R4NK1 b - -',
            '1r4k1/2p1q1p1/4p2p/p3p3/P1PbP3/1p1P3P/1P2QPP1/R4NK1 w - -',
            '1r4k1/2p1q1p1/4p2p/p3p3/P1PbP3/1p1P3P/1P2QPPN/R5K1 b - -',
            '1r4k1/2p1q1p1/4p2p/p1b1p3/P1P1P3/1p1P3P/1P2QPPN/R5K1 w - -',
            '1r4k1/2p1q1p1/4p2p/p1b1p3/P1P1P3/1p1P1N1P/1P2QPP1/R5K1 b - -',
            '1r4k1/2p3p1/3qp2p/p1b1p3/P1P1P3/1p1P1N1P/1P2QPP1/R5K1 w - -',
            '1r4k1/2p3p1/3qp2p/p1b1p3/P1P1P3/1p1P1N1P/1P1Q1PP1/R5K1 b - -',
            '1r4k1/2p3p1/3qp2p/p3p3/PbP1P3/1p1P1N1P/1P1Q1PP1/R5K1 w - -',
            '1r4k1/2p3p1/3qp2p/p3p3/PbP1P3/1p1P1N1P/1P3PP1/R2Q2K1 b - -',
            '1r4k1/2p3p1/3qp2p/p1b1p3/P1P1P3/1p1P1N1P/1P3PP1/R2Q2K1 w - -',
            '1r4k1/2p3p1/3qp2p/p1b1p3/P1P1P3/1p1P1N1P/1P1Q1PP1/R5K1 b - -',
            '1r4k1/2p3p1/3qp2p/p3p3/PbP1P3/1p1P1N1P/1P1Q1PP1/R5K1 w - -',
            '1r4k1/2p3p1/3qp2p/p1P1p3/Pb2P3/1p1P1N1P/1P1Q1PP1/R5K1 b - -',
            '1r4k1/2p3p1/4p2p/p1q1p3/Pb2P3/1p1P1N1P/1P1Q1PP1/R5K1 w - -',
            '1r4k1/2p3p1/4p2p/p1q1p3/Pb2P3/1p1P1N1P/1P3PP1/R2Q2K1 b - -',
            '6k1/2p3p1/1r2p2p/p1q1p3/Pb2P3/1p1P1N1P/1P3PP1/R2Q2K1 w - -',
            '6k1/2p3p1/1r2p2p/p1q1p3/Pb2P3/1p1P1N1P/1P3PP1/2RQ2K1 b - -',
            '6k1/2p3p1/1r1qp2p/p3p3/Pb2P3/1p1P1N1P/1P3PP1/2RQ2K1 w - -',
            '6k1/2p3p1/1r1qp2p/p3p3/PbR1P3/1p1P1N1P/1P3PP1/3Q2K1 b - -',
            '6k1/2p3p1/1r1qp2p/p3p3/P1R1P3/bp1P1N1P/1P3PP1/3Q2K1 w - -',
            '6k1/2p3p1/1r1qp2p/p3p3/P1R1P3/Pp1P1N1P/5PP1/3Q2K1 b - -',
            '6k1/2p3p1/1r1qp2p/p3p3/P1R1P3/P2P1N1P/1p3PP1/3Q2K1 w - -',
            '6k1/2p3p1/1r1qp2p/p3p3/P1R1P3/P2P1N1P/1p3PP1/1Q4K1 b - -',
            '6k1/2p3p1/3qp2p/p3p3/P1R1P3/Pr1P1N1P/1p3PP1/1Q4K1 w - -',
            '6k1/2p3p1/3qp2p/p3p3/P3P3/Pr1P1N1P/1pR2PP1/1Q4K1 b - -',
            '6k1/2p3p1/4p2p/p3p3/P3P3/Pr1q1N1P/1pR2PP1/1Q4K1 w - -',
            '6k1/2p3p1/4p2p/p3p3/P3P3/Pr1q3P/1pRN1PP1/1Q4K1 b - -',
            '6k1/2p3p1/1r2p2p/p3p3/P3P3/P2q3P/1pRN1PP1/1Q4K1 w - -',
            '6k1/2p3p1/1r2p2p/p3p3/P3P3/P2q3P/1pRN1PPK/1Q6 b - -',
            '8/2p3pk/1r2p2p/p3p3/P3P3/P2q3P/1pRN1PPK/1Q6 w - -',
            '8/2p3pk/1r2p2p/p3p3/P3P3/P2q3P/1R1N1PPK/1Q6 b - -',
            '8/2p3pk/1r2p2p/p3p3/P3P3/P6P/1R1q1PPK/1Q6 w - -',
            '8/2p3pk/1r2p2p/p3p3/P3P3/P6P/3R1PPK/1Q6 b - -',
            '8/2p3pk/4p2p/p3p3/P3P3/P6P/3R1PPK/1r6 w - -',
            '8/2p3pk/4p2p/p3p3/P3P3/P6P/2R2PPK/1r6 b - -',
            '8/2p3p1/4p1kp/p3p3/P3P3/P6P/2R2PPK/1r6 w - -',
            '8/2R3p1/4p1kp/p3p3/P3P3/P6P/5PPK/1r6 b - -',
            '8/2R3p1/4p1kp/p3p3/P3P3/P6P/5PPK/3r4 w - -',
            '8/6p1/4p1kp/p1R1p3/P3P3/P6P/5PPK/3r4 b - -',
            '8/6p1/4p1kp/p1R1p3/P2rP3/P6P/5PPK/8 w - -',
            '8/6p1/4p1kp/p3R3/P2rP3/P6P/5PPK/8 b - -',
            '8/6p1/4pk1p/p3R3/P2rP3/P6P/5PPK/8 w - -',
            '8/6p1/4pk1p/R7/P2rP3/P6P/5PPK/8 b - -',
            '8/6p1/4pk1p/R7/P3r3/P6P/5PPK/8 w - -',
            'R7/6p1/4pk1p/8/P3r3/P6P/5PPK/8 b - -',
            'R7/5kp1/4p2p/8/P3r3/P6P/5PPK/8 w - -',
            'R7/5kp1/4p2p/8/P3r3/P5KP/5PP1/8 b - -',
            'R7/5kp1/4p2p/8/P1r5/P5KP/5PP1/8 w - -',
            'R7/5kp1/4p2p/8/P1r5/P4K1P/5PP1/8 b - -',
            'R7/5kp1/4p2p/8/P7/P1r2K1P/5PP1/8 w - -',
            'R7/5kp1/4p2p/8/P3K3/P1r4P/5PP1/8 b - -',
            'R7/5kp1/4p2p/8/P3K3/r6P/5PP1/8 w - -',
            'R7/5kp1/4p2p/P7/4K3/r6P/5PP1/8 b - -',
            'R7/5kp1/4p2p/P7/4K3/7P/r4PP1/8 w - -',
            'R7/5kp1/P3p2p/8/4K3/7P/r4PP1/8 b - -',
            'R7/5kp1/P3p2p/8/4K3/7P/5rP1/8 w - -',
            '2R5/5kp1/P3p2p/8/4K3/7P/5rP1/8 b - -',
            '2R5/5kp1/P3p2p/8/4K3/7P/r5P1/8 w - -',
            '8/2R2kp1/P3p2p/8/4K3/7P/r5P1/8 b - -',
            '8/2R3p1/P3pk1p/8/4K3/7P/r5P1/8 w - -',
            '8/P1R3p1/4pk1p/8/4K3/7P/r5P1/8 b - -',
            '8/P1R3p1/4pk1p/8/r3K3/7P/6P1/8 w - -',
            '8/P1R3p1/4pk1p/8/r7/3K3P/6P1/8 b - -',
            '8/P1R3p1/5k1p/4p3/r7/3K3P/6P1/8 w - -',
            '8/P1R3p1/5k1p/4p3/r7/2K4P/6P1/8 b - -',
            '8/P1R3p1/5k1p/8/r3p3/2K4P/6P1/8 w - -',
            '8/P1R3p1/5k1p/8/r3p3/1K5P/6P1/8 b - -',
            '8/P1R3p1/5k1p/8/4p3/1K5P/6P1/r7 w - -',
            '8/P1R3p1/5k1p/8/2K1p3/7P/6P1/r7 b - -',
            '8/P1R3p1/5k1p/8/2K5/4p2P/6P1/r7 w - -',
            '8/P1R3p1/5k1p/8/8/3Kp2P/6P1/r7 b - -',
            '8/P1R3p1/5k1p/8/8/r2Kp2P/6P1/8 w - -',
            '8/P1R3p1/5k1p/8/8/r3p2P/4K1P1/8 b - -',
            '8/P1R3p1/5k2/7p/8/r3p2P/4K1P1/8 w - -',
            '8/P1R3p1/5k2/7p/7P/r3p3/4K1P1/8 b - -',
            '8/P1R5/5kp1/7p/7P/r3p3/4K1P1/8 w - -',
            '8/P1R5/5kp1/7p/7P/r3pK2/6P1/8 b - -',
            '8/P1R5/4k1p1/7p/7P/r3pK2/6P1/8 w - -',
            '8/P5R1/4k1p1/7p/7P/r3pK2/6P1/8 b - -',
            '8/P5R1/5kp1/7p/7P/r3pK2/6P1/8 w - -',
            '8/PR6/5kp1/7p/7P/r3pK2/6P1/8 b - -',
            '8/PR6/4k1p1/7p/7P/r3pK2/6P1/8 w - -',
            '8/P5R1/4k1p1/7p/7P/r3pK2/6P1/8 b - -',
        ];

        $ravPlay = (new RavPlay($movetext))->validate();

        $this->assertSame($expected, $ravPlay->getFen());
    }
}
