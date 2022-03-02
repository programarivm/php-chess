<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\KnightOutpostEvaluation;
use Chess\FEN\StringToBoard;
use Chess\Tests\AbstractUnitTestCase;

class KnightOutpostEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wKnightAdvancingAlongAFileData
     * @test
     */
    public function w_knight_advancing_along_a_file($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    /**
     * @dataProvider wKnightAdvancingAlongAFileAttackedByAPawnData
     * @test
     */
    public function w_knight_advancing_along_a_file_attacked_by_a_pawn($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    /**
     * @dataProvider bKnightAdvancingAlongAFileData
     * @test
     */
    public function b_knight_advancing_along_a_file($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    public function wKnightAdvancingAlongAFileData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/8/8/1N6/P7/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/8/8/1N6/P7/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/8/1N6/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/1N6/P7/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/1N5K/P7/8/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '1N3k2/P6K/8/8/8/8/8/8 w - -',
            ],
        ];
    }

    public function wKnightAdvancingAlongAFileAttackedByAPawnData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/8/2p5/1N6/P7/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/2p5/1N6/P7/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/2p5/1N6/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/2p4K/1N6/P7/8/8/8/8 w - -',
            ],
        ];
    }

    public function bKnightAdvancingAlongAFileData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/7p/6n1/8/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '8/8/7p/6n1/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '8/8/8/7p/6n1/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '8/8/8/8/7p/6n1/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '8/8/8/8/8/7p/K5n1/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/8/8/8/K6p/2k3n1 w - -',
            ],
        ];
    }
}
