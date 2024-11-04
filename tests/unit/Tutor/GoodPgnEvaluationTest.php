<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Function\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\GoodPgnEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;

class GoodPgnEvaluationTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function D07()
    {
        $expectedPgn = 'Bg4';

        $expectedParagraph = [
            "The black player is pressuring a little bit more squares than its opponent.",
            "The black pieces are timidly approaching the other side's king.",
            "Black has a total relative pin advantage.",
            "These pieces are hanging: Black's queen on d5, the rook on a8, the rook on h8, the pawn on b7, the pawn on c7, the pawn on g7, the bishop on g4, the rook on h1.",
            "The knight on e2 is pinned shielding a piece that is more valuable than the attacking piece.",
            "Overall, 3 heuristic evaluation features are favoring White while 9 are favoring Black.",
        ];

        $limit = new Limit();
        $limit->depth = 12;
        $stockfish = new UciEngine('/usr/games/stockfish');
        $D07 = file_get_contents(self::DATA_FOLDER.'/sample/D07.pgn');
        $board = (new SanPlay($D07))->validate()->board;

        $goodPgnEvaluation = new GoodPgnEvaluation($limit, $stockfish, self::$function, $board);

        $this->assertSame($expectedPgn, $goodPgnEvaluation->pgn);
        $this->assertSame($expectedParagraph, $goodPgnEvaluation->paragraph);
    }
}
