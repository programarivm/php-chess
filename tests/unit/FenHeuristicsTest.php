<?php

namespace Chess\Tests\Unit;

use Chess\FenHeuristics;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;

class FenHeuristicsTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function get_balance_start()
    {
        $fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4()
    {
        $fen = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq - 0 1';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 0.0, 12.0, -4.0, 8.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e5()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_e4_e5_Nf3_Nf6()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_A59()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 1.0, 9.0, -1.0, 4.0, -3.0, 0.0, 0.0, 0.0, 0.0, 1.0, 1.0, 0.0, 0.0, 0.0, -5.1, 0.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function get_balance_scholar_checkmate()
    {
        $fen = 'r1bqkb1r/pppp1Qpp/2n2n2/4p3/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 1.0, 5.66, -12.0, 2.0, 3.0, 4.0, -1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $fen = 'rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3';

        $board = FenToBoardFactory::create($fen, new CapablancaBoard());

        $balance = (new FenHeuristics(self::$function, $board))->getBalance();

        $expected = [ 0.0, 12.0, -3.0, 9.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $this->assertEquals($expected, $balance);
    }
}
