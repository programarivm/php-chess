<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\KnightOutpostEvaluation;
use Chess\FEN\StringToBoard;
use Chess\Tests\AbstractUnitTestCase;

class KnightOutpostEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wKnightAdvancingData
     * @test
     */
    public function w_knight_advancing($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    /**
     * @dataProvider wKnightAdvancingUnderAttackData
     * @test
     */
    public function w_knight_advancing_under_attack($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    /**
     * @dataProvider bKnightAdvancingData
     * @test
     */
    public function b_knight_advancing($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    /**
     * @dataProvider bKnightAdvancingUnderAttackData
     * @test
     */
    public function b_knight_advancing_under_attack($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $knightOutpostEvald = (new KnightOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $knightOutpostEvald);
    }

    public function wKnightAdvancingData()
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

    public function wKnightAdvancingUnderAttackData()
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

    public function bKnightAdvancingData()
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

    public function bKnightAdvancingUnderAttackData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/7p/6n1/5P2/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/7p/6n1/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/7p/6n1/5P2/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/8/7p/6n1/K4P2/2k5 w - -',
            ],
        ];
    }
}
