<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Play\SanPlay;
use Chess\Tutor\GoodPgnEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;

class GoodPgnEvaluationTest extends AbstractUnitTestCase
{
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
            "The knight on e2 is pinned shielding a piece that is more valuable than the attacking piece.",
            "Overall, 4 heuristic evaluation features are favoring White while 8 are favoring Black.",
        ];

        $limit = (new Limit())->setDepth(12);
        $stockfish = new UciEngine('/usr/games/stockfish');
        $D07 = file_get_contents(self::DATA_FOLDER.'/sample/D07.pgn');
        $board = (new SanPlay($D07))->validate()->getBoard();

        $goodPgnEvaluation = new GoodPgnEvaluation($limit, $stockfish, $board);

        $this->assertSame($expectedPgn, $goodPgnEvaluation->getPgn());
        $this->assertSame($expectedParagraph, $goodPgnEvaluation->getParagraph());
    }
}
