<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareOutpostEvaluation;
use Chess\FEN\StringToBoard;
use Chess\Tests\AbstractUnitTestCase;

class SquareOutpostEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wAdvancingData
     * @test
     */
    public function w_advancing($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    /**
     * @dataProvider wAdvancingUnderAttackData
     * @test
     */
    public function w_advancing_under_attack($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    /**
     * @dataProvider wAdvancingCanBeAttackedData
     * @test
     */
    public function w_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    /**
     * @dataProvider bAdvancingData
     * @test
     */
    public function b_advancing($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    /**
     * @dataProvider bAdvancingUnderAttackData
     * @test
     */
    public function b_advancing_under_attack($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    /**
     * @dataProvider bAdvancingCanBeAttackedData
     * @test
     */
    public function b_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StringToBoard($fen))->create();

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }

    public function wAdvancingData()
    {
        return [
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '5k2/7K/8/8/8/8/P7/8 w - -',
            ],
            [
                [
                    'w' => ['b4'],
                    'b' => [],
                ],
                '5k2/7K/8/8/8/P7/8/8 w - -',
            ],
            [
                [
                    'w' => ['b5'],
                    'b' => [],
                ],
                '5k2/7K/8/8/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => ['b6'],
                    'b' => [],
                ],
                '5k2/7K/8/P7/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => ['b7'],
                    'b' => [],
                ],
                '5k2/7K/P7/8/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '5k2/P6K/8/8/8/8/8/8 w - -',
            ],
        ];
    }

    public function wAdvancingUnderAttackData()
    {
        return [
            [
                [
                    'w' => [],
                    'b' => ['d3'],
                ],
                '5k2/7K/8/8/2p5/8/P7/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['d4'],
                ],
                '5k2/7K/8/2p5/8/P7/8/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['d5'],
                ],
                '5k2/7K/2p5/8/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '5k2/2p4K/8/P7/8/8/8/8 w - -',
            ],
        ];
    }

    public function wAdvancingCanBeAttackedData()
    {
        return [
            [
                [
                    'w' => [],
                    'b' => ['d4'],
                ],
                '5k2/7K/8/2p5/8/8/P7/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['d4'],
                ],
                '5k2/7K/8/2p5/8/P7/8/8 w - -',
            ],
            [
                [
                    'w' => ['b5'],
                    'b' => ['b4', 'd4'],
                ],
                '5k2/7K/8/2p5/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => ['b6'],
                    'b' => ['b4', 'd4'],
                ],
                '5k2/7K/8/P1p5/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => ['b7'],
                    'b' => ['b4', 'd4'],
                ],
                '5k2/7K/P7/2p5/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['b4', 'd4'],
                ],
                '5k2/P6K/8/2p5/8/8/8/8 w - -',
            ],
        ];
    }

    public function bAdvancingData()
    {
        return [
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '8/7p/8/8/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['g5'],
                ],
                '8/8/7p/8/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['g4'],
                ],
                '8/8/8/7p/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['g3'],
                ],
                '8/8/8/8/7p/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => ['g2'],
                ],
                '8/8/8/8/8/7p/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '8/8/8/8/8/8/K6p/2k5 w - -',
            ],
        ];
    }

    public function bAdvancingUnderAttackData()
    {
        return [
            [
                [
                    'w' => ['e6'],
                    'b' => [],
                ],
                '8/7p/8/5P2/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5'],
                    'b' => [],
                ],
                '8/8/7p/8/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e4'],
                    'b' => [],
                ],
                '8/8/8/7p/8/5P2/K7/2k5 w - -',
            ],
            [
                [
                    'w' => [],
                    'b' => [],
                ],
                '8/8/8/8/7p/8/K4P2/2k5 w - -',
            ],
        ];
    }

    public function bAdvancingCanBeAttackedData()
    {
        return [
            [
                [
                    'w' => ['e5'],
                    'b' => [],
                ],
                '8/7p/8/8/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5'],
                    'b' => [],
                ],
                '8/8/7p/8/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5', 'g5'],
                    'b' => ['g4'],
                ],
                '8/8/8/7p/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5', 'g5'],
                    'b' => ['g3'],
                ],
                '8/8/8/8/5P1p/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5', 'g5'],
                    'b' => ['g2'],
                ],
                '8/8/8/8/5P2/7p/K7/2k5 w - -',
            ],
            [
                [
                    'w' => ['e5', 'g5'],
                    'b' => [],
                ],
                '8/8/8/8/5P2/8/K6p/2k5 w - -',
            ],
        ];
    }

    /**
     * @test
     */
    public function d17()
    {
        $fen = 'r3kb1r/ppq2ppp/2p2n2/4nb2/P1N5/2N3P1/1P2PP1P/R1BQKB1R w KQkq -';

        $board = (new StringToBoard($fen))->create();

        $expected = [
            'w' => [],
            'b' => [],
        ];

        $sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->assertSame($expected, $sqOutpostEvald);
    }
}
