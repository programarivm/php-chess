<?php

namespace Chess\Tests\Unit\Movetext;

use Chess\Movetext\RavMovetext;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Move;

class RavMovetextTest extends AbstractUnitTestCase
{
    static private $move;

    public static function setUpBeforeClass(): void
    {
        self::$move = new Move();
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new RavMovetext(self::$move, 'foo'))->validate();
    }

    /**
     * @test
     */
    public function filtered_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 (11.Nb1 h6 12.h4 (12.Nh4 g5 13.Nf5) 12...a5 13.g4 Nxg4) 11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5 (16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5)';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function main_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->main());
    }

    /**
     * @test
     */
    public function main_Ra7_Kg8__Rc8()
    {
        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8 (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#) 6.Kd6 (6.Kc6 Kd8) 6...Kb8 (6...Kd8 7.Ra8#) 7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#';

        $expected = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8 6.Kd6 Kb8 7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->main());
    }

    /**
     * @test
     */
    public function main_Ke2_Kd5__Kc1_Ra1()
    {
        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4 (2...Ke5 3.Rh5+) (2...Kc4 3.Rh5) 3.Rh5 (3...Kb4 4.Kd3) 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = '1.Ke2 Kd5 2.Ke3 Kc4 3.Rh5 Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->main());
    }

    /**
     * @test
     */
    public function filtered_Ra7_Kg8__Rc8()
    {
        $movetext = '1.  Ke2 Kd5 {this is foo} 2.Ke3   Kc4 (2...Ke5 {this is bar} 3.Rh5+) (2...Kc4 3.Rh5) 3  .  Rh5 (3...Kb4 4.Kd3) {this is foobar} 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = '1.Ke2 Kd5 {this is foo} 2.Ke3 Kc4 (2...Ke5 {this is bar} 3.Rh5+) (2...Kc4 3.Rh5) 3.Rh5 (3...Kb4 4.Kd3) {this is foobar} 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function filtered_comments_Ra7_Kg8__Rc8()
    {
        $movetext = '1.  Ke2 Kd5 {this is foo} 2.Ke3   Kc4 (2...Ke5 {this is bar} 3.Rh5+) (2...Kc4 3.Rh5) 3  .  Rh5 (3...Kb4 4.Kd3) {this is foobar} 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = '1.Ke2 Kd5 2.Ke3 Kc4 (2...Ke5 3.Rh5+) (2...Kc4 3.Rh5) 3.Rh5 (3...Kb4 4.Kd3) 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->filtered($comments = false));
    }

    /**
     * @test
     */
    public function filtered_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . Ra7 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $expected = "1.Ra7 Kg8 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $this->assertEquals($expected, (new RavMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function sicilian_commented()
    {
        $movetext = '1.e4 c5 {foo} (2.Nf3 d6 {foobar}) (2.Nf3 Nc6)';

        $expected = '1.e4 c5 (2.Nf3 d6) (2.Nf3 Nc6)';

        $this->assertSame($expected, (new RavMovetext(self::$move, $movetext))->filtered($comments = false));
    }

    /**
     * @test
     */
    public function max_depth_d4_d5_Nf3_Nc6()
    {
        $movetext = '1.d4 d5 2.Nf3 Nc6';

        $expected = 0;

        $maxDepth = (new RavMovetext(self::$move, $movetext))->maxDepth();

        $this->assertSame($expected, $maxDepth);
    }

    /**
     * @test
     */
    public function max_depth_e4_e5__Bc4()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3 (3.Bc4))';

        $expected = 2;

        $maxDepth = (new RavMovetext(self::$move, $movetext))->maxDepth();

        $this->assertSame($expected, $maxDepth);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_e5__Bc4()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3 (3.Bc4))';

        $expected = [
            [
                '3.Bc4' => '2...Nf6 3.Nc3',
            ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_e5__Nc3()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3 (3.Bc4 d5)) 3.Nc3';

        $expected = [
            [ '3.Bc4 d5' => '2...Nf6 3.Nc3' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_d4_d5_Nf3_Nc6()
    {
        $movetext = '1.d4 d5 2.Nf3 Nc6';

        $expected = [
            '1.d4 d5 2.Nf3 Nc6'
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_e5__d4()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3) (2...f5 3.d4)';

        $expected = [
            [ '2...Nf6 3.Nc3' => '1.e4 e5 2.Nf3 Nc6' ],
            [ '2...f5 3.d4' => '1.e4 e5 2.Nf3 Nc6' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_e5__Bc4_d5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3 (3.Bc4 d5)) (2...Nf6 3.Nc3 (3.Bc4 d5))';

        $expected = [
            [ '3.Bc4 d5' => '2...Nf6 3.Nc3' ],
            [ '3.Bc4 d5' => '2...Nf6 3.Nc3' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_e5__Be2_Be7()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 (2...Nf6 3.Nc3 (3.Bc4 d5) (3.Be2 Be7))';

        $expected = [
            [ '3.Bc4 d5' => '2...Nf6 3.Nc3' ],
            [ '3.Be2 Be7' => '2...Nf6 3.Nc3' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_sicilian_defense_e4_c5_Nf3()
    {
        $movetext = '1. e4 c5
            (2.Nf3 d6)
            (2.Nf3 Nc6)
            (2.Nf3 e6)';

        $expected = [
            [ '2.Nf3 d6' => '1.e4 c5' ],
            [ '2.Nf3 Nc6' => '1.e4 c5' ],
            [ '2.Nf3 e6' => '1.e4 c5' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_sicilian_defense_e4_c5_Nf3_d6()
    {
        $movetext = '1. e4 c5
            (2.Nf3 d6)
            (2.Nf3 Nc6)
            (2.Nf3 e6)
            (2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6))';

        $expected = [
            [ '5...a6' => '2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...g6' => '2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...Nc6' => '2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...e6' => '2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function max_depth_strings_e4_c5__e6()
    {
        $movetext = '1.e4 $1 c5
            (2.Nf3
                (2...Nc6)
                (2...e6)
                (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6))
            )';

        $expected = [
            [ '5...a6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...g6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...Nc6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            [ '5...e6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
        ];

        $maxDepthStrings = (new RavMovetext(self::$move, $movetext))->maxDepthStrings();

        $this->assertSame($expected, $maxDepthStrings);
    }

    /**
     * @test
     */
    public function lines_e4_c5__e6()
    {
        $movetext = '1.e4 $1 c5
            (2.Nf3
                (2...Nc6)
                (2...e6)
                (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6))
            )';

        $expected = [
            0 => [
                [ '1.e4 c5' => null ],
            ],
            1 => [
                [ '2.Nf3' => '1.e4 c5' ],
            ],
            2 => [
                [ '2...Nc6' => '2.Nf3' ],
                [ '2...e6' => '2.Nf3' ],
                [ '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' => '2.Nf3' ],
            ],
            3 => [
                [ '5...a6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
                [ '5...g6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
                [ '5...Nc6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
                [ '5...e6' => '2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3' ],
            ],
        ];


        $lines = (new RavMovetext(self::$move, $movetext))->lines();

        $this->assertSame($expected, $lines);
    }

    /**
     * @test
     */
    public function lines_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5
            (11. Nb1 h6 12. h4
            (12. Nh4 g5 13. Nf5)
            12... a5 13. g4 Nxg4)
            11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5
            (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = [
            0 => [
                [ '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5' => null ],
            ],
            1 => [
                [ '11.Nb1 h6 12.h4 12...a5 13.g4 Nxg4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5' ],
                [ '16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5' ],
            ],
            2 => [
                [ '12.Nh4 g5 13.Nf5' => '11.Nb1 h6 12.h4', ],
            ],
        ];

        $lines = (new RavMovetext(self::$move, $movetext))->lines();

        $this->assertSame($expected, $lines);
    }

    /**
     * @test
     */
    public function lines_e4_e5__Kf2_Bh2()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 d6 8. a3 O-O 9. Nc3 Bb7 10. Bd2
            (10. Re1 Na5 11. Ba2 c5  12. b4 Nc6 13. Nd5)
            10... Qd7
            (10... Nd4 11. Nxd4 exd4 12. Ne2 c5 13. Ng3)
            11. a4 $1 Nd8 $5
            (11... b4 12. Nd5 Nxd5 13. Bxd5 Rab8 14. a5)
            (11... Na5 12. Ba2 c5 13. Nd5 Bxd5 14. exd5 b4)
            12. axb5 axb5 13. Rxa8 Bxa8 14. Ne2
            (14. Qa1)
            14... Ne6 15. Ng3 c5 16. Nf5 Bd8
            (16... c4 $1 17. dxc4 bxc4 18. Bxc4 Bxe4 19. Nxe7+ Qxe7 20. Ng5 Bf5 21. Nxe6 Bxe6)
            17. c4 bxc4 18. Bxc4 Bc7 19. Re1 Re8
            (19... Nf4 $5)
            20. Qc1 $1 Nh5 $6
            (20... Rb8)
            (20... Bc6 21. h3 Bb5)
            21. g3
            (21. b4 $5)
            21... g6 $2 22. Nh6+ Kg7 23. Ng5
            (23. b4 $1 cxb4 24. Nxf7 $1 Bb6 25. N7g5)
            23... Nxg5 24. Bxg5 d5 25. exd5 Bxd5 26. Ng4 Bf3 27. Bf6+ Kg8 28. Nh6+ Kf8 29. Qe3 Bb7 30. Bh4 Qh3 31. f3 Nf4 32. gxf4 Qxh4 33. Nxf7 Bxf3 34. Qf2 Qg4+ 35. Qg3 exf4 36. Rxe8+ Kxe8 37. Qxg4 Bxg4 38. Ng5 h6 39. Nf7 h5 40. Nh6 Bd1 41. Kf2 f3 42. h3 Bf4 43. Nf7 g5 44. Ke1 g4 45. hxg4 hxg4 46. Kxd1 g3 47. Ke1 g2 48. Kf2 Bh2 0-1";

        $expected = [
            0 => [
                [
                    '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8 20.Qc1 Nh5 21.g3 21...g6 22.Nh6+ Kg7 23.Ng5 23...Nxg5 24.Bxg5 d5 25.exd5 Bxd5 26.Ng4 Bf3 27.Bf6+ Kg8 28.Nh6+ Kf8 29.Qe3 Bb7 30.Bh4 Qh3 31.f3 Nf4 32.gxf4 Qxh4 33.Nxf7 Bxf3 34.Qf2 Qg4+ 35.Qg3 exf4 36.Rxe8+ Kxe8 37.Qxg4 Bxg4 38.Ng5 h6 39.Nf7 h5 40.Nh6 Bd1 41.Kf2 f3 42.h3 Bf4 43.Nf7 g5 44.Ke1 g4 45.hxg4 hxg4 46.Kxd1 g3 47.Ke1 g2 48.Kf2 Bh2' => null,
                ],
            ],
            1 => [
                [ '10.Re1 Na5 11.Ba2 c5 12.b4 Nc6 13.Nd5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2' ],
                [ '10...Nd4 11.Nxd4 exd4 12.Ne2 c5 13.Ng3' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7' ],
                [ '11...b4 12.Nd5 Nxd5 13.Bxd5 Rab8 14.a5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8' ],
                [ '11...Na5 12.Ba2 c5 13.Nd5 Bxd5 14.exd5 b4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8' ],
                [ '14.Qa1' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2' ],
                [ '16...c4 17.dxc4 bxc4 18.Bxc4 Bxe4 19.Nxe7+ Qxe7 20.Ng5 Bf5 21.Nxe6 Bxe6' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8' ],
                [ '19...Nf4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8' ],
                [ '20...Rb8' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8 20.Qc1 Nh5' ],
                [ '20...Bc6 21.h3 Bb5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8 20.Qc1 Nh5' ],
                [ '21.b4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8 20.Qc1 Nh5 21.g3' ],
                [ '23.b4 cxb4 24.Nxf7 Bb6 25.N7g5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Bb7 10.Bd2 10...Qd7 11.a4 Nd8 12.axb5 axb5 13.Rxa8 Bxa8 14.Ne2 14...Ne6 15.Ng3 c5 16.Nf5 Bd8 17.c4 bxc4 18.Bxc4 Bc7 19.Re1 Re8 20.Qc1 Nh5 21.g3 21...g6 22.Nh6+ Kg7 23.Ng5' ],
            ],
        ];

        $lines = (new RavMovetext(self::$move, $movetext))->lines();

        $this->assertSame($expected, $lines);
    }

    /**
     * @test
     */
    public function lines_e4_e5__Qc4()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 d6 8. a3 O-O
            (8... Be6 9. Bxe6 fxe6 10. c3 O-O 11. Nbd2 d5
                (11... Qe8 $5 12. Re1 Qg6 13. Nf1 Nh5 14. Ng3 Nf4 15. Bxf4 exf4 16. Ne2 Ne5 17. Ned4 $1 c5 18. Nxe5 dxe5 19. Nf3 $14)
                12. Qe2 Qd6 13. b4 $1 $14 Rfd8 14. Rd1 dxe4 15. Nxe4 Nxe4 16. Qxe4 Qd5 17. Bb2 a5 18. Rac1 Qxe4 19. dxe4 Bd6 20. h4 Rf8 21. c4 $1 axb4 22. axb4
                (22. cxb5 $5 bxa3 23. Bxa3 Rxa3 24. Rxc6 $14)
                22... Ra2
                (22... bxc4 23. Rxc4 Ra2 24. Bc3 Nd4 $1 $132)
                23. c5 Be7 24. Bc3 Bf6 25. Ra1 Rc2 26. Rd3 Kf7 27. Rd7+ Kg6 28. Rxc7 Nd4 29. Bxd4 exd4 30. e5 Bd8 31. Rd7 Rxf3 32. gxf3 Bxh4 33. Rf1 Rc4 34. Kg2 Kf5 35. Rh1 Bg5 36. Rxg7 h6 37. Rf7+ Kg6 38. Rd7 Rxb4 39. Rd1 Rc4 40. R1xd4
            )
            9. Nc3 Be6
            (9... Bg4)
            (9... Na5 10. Ba2 c5 11. Bg5 $5
                (11. b4 Nc6 12. Nd5 Nd4 13. c3 Nxf3+ 14. Qxf3 Nxd5 15. Bxd5 Rb8 16. Be3 Bb7)
                11... Rb8 12. Bxf6 Bxf6 13. Nd5 Be6 14. b4 Nc6 15. c3
            )
            10. Nd5 $142
            (10. Bxe6 fxe6 11. Ne2 $5 d5 $1 12. Ng3 dxe4 13. Nxe4 Nxe4 14. dxe4 Qxd1 15. Rxd1 Nd4 $1)
            10... Bxd5 $6
            (10... Nd4 11. Nxd4 exd4 12. Bd2 c5
                (12... Nxd5 13. exd5 Bd7 14. Ba5 $1)
                13. Nxf6+ Bxf6 14. Bxe6 fxe6 15. f4
            )
            11. exd5 Nd4 12. Nxd4 exd4 13. c3 $1 dxc3 14. bxc3 Nd7 15. d4
            (15. a4 $5 Nb6 16. a5 Nd7 17. d4)
            15... Nb6 16. a4 Bf6 $6
            (16... Nxa4 17. Bxa4 bxa4 18. Qxa4 Bg5)
            17. Qd3
            (17. a5 Nc8 18. Re1 Ne7 19. Ra2 Re8 20. Qf3 Qd7 21. Rae2 $16)
            17... Nxa4 $1 18. Bxa4 bxa4 19. Rxa4 Qc8 20. Be3 Qb7 21. Qc4 1/2-1/2";

        $expected = [
            0 => [
                [
                    '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6 17.Qd3 17...Nxa4 18.Bxa4 bxa4 19.Rxa4 Qc8 20.Be3 Qb7 21.Qc4' => null,
                ],
            ],
            1 => [
                [ '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4 22...Ra2 23.c5 Be7 24.Bc3 Bf6 25.Ra1 Rc2 26.Rd3 Kf7 27.Rd7+ Kg6 28.Rxc7 Nd4 29.Bxd4 exd4 30.e5 Bd8 31.Rd7 Rxf3 32.gxf3 Bxh4 33.Rf1 Rc4 34.Kg2 Kf5 35.Rh1 Bg5 36.Rxg7 h6 37.Rf7+ Kg6 38.Rd7 Rxb4 39.Rd1 Rc4 40.R1xd4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O' ],
                [ '9...Bg4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6' ],
                [ '9...Na5 10.Ba2 c5 11.Bg5 11...Rb8 12.Bxf6 Bxf6 13.Nd5 Be6 14.b4 Nc6 15.c3' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6' ],
                [ '10.Bxe6 fxe6 11.Ne2 d5 12.Ng3 dxe4 13.Nxe4 Nxe4 14.dxe4 Qxd1 15.Rxd1 Nd4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5' ],
                [ '10...Nd4 11.Nxd4 exd4 12.Bd2 c5 13.Nxf6+ Bxf6 14.Bxe6 fxe6 15.f4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5' ],
                [ '15.a4 Nb6 16.a5 Nd7 17.d4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4' ],
                [ '16...Nxa4 17.Bxa4 bxa4 18.Qxa4 Bg5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6' ],
                [ '17.a5 Nc8 18.Re1 Ne7 19.Ra2 Re8 20.Qf3 Qd7 21.Rae2' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6 17.Qd3' ],
            ],
            2 => [
                [ '11...Qe8 12.Re1 Qg6 13.Nf1 Nh5 14.Ng3 Nf4 15.Bxf4 exf4 16.Ne2 Ne5 17.Ned4 c5 18.Nxe5 dxe5 19.Nf3' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5' ],
                [ '22.cxb5 bxa3 23.Bxa3 Rxa3 24.Rxc6' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4' ],
                [ '22...bxc4 23.Rxc4 Ra2 24.Bc3 Nd4' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4 22...Ra2' ],
                [ '11.b4 Nc6 12.Nd5 Nd4 13.c3 Nxf3+ 14.Qxf3 Nxd5 15.Bxd5 Rb8 16.Be3 Bb7' => '9...Na5 10.Ba2 c5 11.Bg5' ],
                [ '12...Nxd5 13.exd5 Bd7 14.Ba5' => '10...Nd4 11.Nxd4 exd4 12.Bd2 c5' ],
            ],
        ];

        $lines = (new RavMovetext(self::$move, $movetext))->lines();

        $this->assertSame($expected, $lines);
    }

    /**
     * @test
     */
    public function lines_e4_e5__Qc4_add_level()
    {
        $movetext = "1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. d3 b5 7. Bb3 d6 8. a3 O-O
            (8... Be6 9. Bxe6 fxe6 10. c3 O-O 11. Nbd2 d5
                (11... Qe8 $5 12. Re1
                    (12. Qe1 Qh5)
                    12 ... Qg6 13. Nf1 Nh5 14. Ng3 Nf4 15. Bxf4 exf4 16. Ne2 Ne5 17. Ned4 $1 c5 18. Nxe5 dxe5 19. Nf3 $14
                )
                12. Qe2 Qd6 13. b4 $1 $14 Rfd8 14. Rd1 dxe4 15. Nxe4 Nxe4 16. Qxe4 Qd5 17. Bb2 a5 18. Rac1 Qxe4 19. dxe4 Bd6 20. h4 Rf8 21. c4 $1 axb4 22. axb4
                (22. cxb5 $5 bxa3 23. Bxa3 Rxa3 24. Rxc6 $14)
                22... Ra2
                (22... bxc4 23. Rxc4 Ra2 24. Bc3 Nd4 $1 $132)
                23. c5 Be7 24. Bc3 Bf6 25. Ra1 Rc2 26. Rd3 Kf7 27. Rd7+ Kg6 28. Rxc7 Nd4 29. Bxd4 exd4 30. e5 Bd8 31. Rd7 Rxf3 32. gxf3 Bxh4 33. Rf1 Rc4 34. Kg2 Kf5 35. Rh1 Bg5 36. Rxg7 h6 37. Rf7+ Kg6 38. Rd7 Rxb4 39. Rd1 Rc4 40. R1xd4
            )
            9. Nc3 Be6
            (9... Bg4)
            (9... Na5 10. Ba2 c5 11. Bg5 $5
                (11. b4 Nc6 12. Nd5 Nd4 13. c3 Nxf3+ 14. Qxf3 Nxd5 15. Bxd5 Rb8 16. Be3 Bb7)
                11... Rb8 12. Bxf6 Bxf6 13. Nd5 Be6 14. b4 Nc6 15. c3
            )
            10. Nd5 $142
            (10. Bxe6 fxe6 11. Ne2 $5 d5 $1 12. Ng3 dxe4 13. Nxe4 Nxe4 14. dxe4 Qxd1 15. Rxd1 Nd4 $1)
            10... Bxd5 $6
            (10... Nd4 11. Nxd4 exd4 12. Bd2 c5
                (12... Nxd5 13. exd5 Bd7 14. Ba5 $1)
                13. Nxf6+ Bxf6 14. Bxe6 fxe6 15. f4
            )
            11. exd5 Nd4 12. Nxd4 exd4 13. c3 $1 dxc3 14. bxc3 Nd7 15. d4
            (15. a4 $5 Nb6 16. a5 Nd7 17. d4)
            15... Nb6 16. a4 Bf6 $6
            (16... Nxa4 17. Bxa4 bxa4 18. Qxa4 Bg5)
            17. Qd3
            (17. a5 Nc8 18. Re1 Ne7 19. Ra2 Re8 20. Qf3 Qd7 21. Rae2 $16)
            17... Nxa4 $1 18. Bxa4 bxa4 19. Rxa4 Qc8 20. Be3 Qb7 21. Qc4 1/2-1/2";

        $expected = [
            0 => [
                [
                    '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6 17.Qd3 17...Nxa4 18.Bxa4 bxa4 19.Rxa4 Qc8 20.Be3 Qb7 21.Qc4' => null,
                ],
            ],
            1 => [
                [ '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4 22...Ra2 23.c5 Be7 24.Bc3 Bf6 25.Ra1 Rc2 26.Rd3 Kf7 27.Rd7+ Kg6 28.Rxc7 Nd4 29.Bxd4 exd4 30.e5 Bd8 31.Rd7 Rxf3 32.gxf3 Bxh4 33.Rf1 Rc4 34.Kg2 Kf5 35.Rh1 Bg5 36.Rxg7 h6 37.Rf7+ Kg6 38.Rd7 Rxb4 39.Rd1 Rc4 40.R1xd4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O' ],
                [ '9...Bg4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6' ],
                [ '9...Na5 10.Ba2 c5 11.Bg5 11...Rb8 12.Bxf6 Bxf6 13.Nd5 Be6 14.b4 Nc6 15.c3' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6' ],
                [ '10.Bxe6 fxe6 11.Ne2 d5 12.Ng3 dxe4 13.Nxe4 Nxe4 14.dxe4 Qxd1 15.Rxd1 Nd4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5' ],
                [ '10...Nd4 11.Nxd4 exd4 12.Bd2 c5 13.Nxf6+ Bxf6 14.Bxe6 fxe6 15.f4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5' ],
                [ '15.a4 Nb6 16.a5 Nd7 17.d4' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4' ],
                [ '16...Nxa4 17.Bxa4 bxa4 18.Qxa4 Bg5' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6' ],
                [ '17.a5 Nc8 18.Re1 Ne7 19.Ra2 Re8 20.Qf3 Qd7 21.Rae2' => '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Ba4 Nf6 5.O-O Be7 6.d3 b5 7.Bb3 d6 8.a3 O-O 9.Nc3 Be6 10.Nd5 10...Bxd5 11.exd5 Nd4 12.Nxd4 exd4 13.c3 dxc3 14.bxc3 Nd7 15.d4 15...Nb6 16.a4 Bf6 17.Qd3' ],
            ],
            2 => [
                [ '11...Qe8 12.Re1 12...Qg6 13.Nf1 Nh5 14.Ng3 Nf4 15.Bxf4 exf4 16.Ne2 Ne5 17.Ned4 c5 18.Nxe5 dxe5 19.Nf3' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5' ],
                [ '22.cxb5 bxa3 23.Bxa3 Rxa3 24.Rxc6' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4' ],
                [ '22...bxc4 23.Rxc4 Ra2 24.Bc3 Nd4' => '8...Be6 9.Bxe6 fxe6 10.c3 O-O 11.Nbd2 d5 12.Qe2 Qd6 13.b4 Rfd8 14.Rd1 dxe4 15.Nxe4 Nxe4 16.Qxe4 Qd5 17.Bb2 a5 18.Rac1 Qxe4 19.dxe4 Bd6 20.h4 Rf8 21.c4 axb4 22.axb4 22...Ra2' ],
                [ '11.b4 Nc6 12.Nd5 Nd4 13.c3 Nxf3+ 14.Qxf3 Nxd5 15.Bxd5 Rb8 16.Be3 Bb7' => '9...Na5 10.Ba2 c5 11.Bg5' ],
                [ '12...Nxd5 13.exd5 Bd7 14.Ba5' => '10...Nd4 11.Nxd4 exd4 12.Bd2 c5' ],
            ],
            3 => [
                [ '12.Qe1 Qh5' => '11...Qe8 12.Re1' ],
            ],
        ];

        $lines = (new RavMovetext(self::$move, $movetext))->lines();

        $this->assertSame($expected, $lines);
    }
}
