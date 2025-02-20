<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\SpaceEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SpaceEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $spEval = (new SpaceEval(new Board()))->result;

        $expected = [
            'w' => [
                'a3', 'b3', 'c3', 'd3', 'e3', 'f3', 'g3', 'h3',
            ],
            'b' => [
                'a6', 'b6', 'c6', 'd6', 'e6', 'f6', 'g6', 'h6',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $spEval);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedResult = [
            'w' => [
                'a3', 'a4', 'b1', 'b3', 'b5', 'c4', 'd2', 'd5',
                'e2', 'e3', 'f1', 'f3', 'f4', 'f5', 'g4', 'g5',
                'h3', 'h4', 'h5', 'h6',
            ],
            'b' => [
                'a5', 'a6', 'b4', 'b6', 'b8', 'c7', 'd4', 'd7',
                'e5', 'e6', 'f5', 'f6', 'f8', 'g4', 'h3', 'h5',
                'h6',
            ],
        ];

        $expectedExplanation = [
            "White has a slight space advantage.",
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/opening/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $spEval = new SpaceEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $spEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $spEval->explain());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedResult = [
            'w' => [
                'a3', 'a4', 'a6', 'b1', 'b3', 'b5', 'c4', 'd2',
                'd3', 'd5', 'e2', 'e3', 'e6', 'f3', 'f4', 'f5',
                'g1', 'g3', 'g4', 'g5', 'h3', 'h5', 'h6',
            ],
            'b' => [
                'a5', 'a6', 'b4', 'b6', 'b8', 'c5', 'c7', 'd5',
                'd7', 'e5', 'e6', 'f5', 'g4', 'g6', 'g8', 'h3',
                'h5', 'h6',
            ],
        ];

        $expectedExplanation = [
            "White has a moderate space advantage.",
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/opening/B56.pgn');
        $board = (new SanPlay($B56))->validate()->board;
        $spEval = new SpaceEval($board);

        $this->assertEqualsCanonicalizing($expectedResult, $spEval->result);
        $this->assertEqualsCanonicalizing($expectedExplanation, $spEval->explain());
    }
}
