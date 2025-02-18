<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\SqOutpostEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class SqOutpostEvalTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wAdvancingData
     * @test
     */
    public function w_advancing($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @dataProvider wAdvancingUnderAttackData
     * @test
     */
    public function w_advancing_under_attack($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @dataProvider wAdvancingCanBeAttackedData
     * @test
     */
    public function w_advancing_can_be_attacked($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @dataProvider bAdvancingData
     * @test
     */
    public function b_advancing($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @dataProvider bAdvancingUnderAttackData
     * @test
     */
    public function b_advancing_under_attack($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @dataProvider bAdvancingCanBeAttackedData
     * @test
     */
    public function b_advancing_can_be_attacked($expectedResult, $fen)
    {
        $board = FenToBoardFactory::create($fen);
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @test
     */
    public function b4()
    {
        $expectedResult = [
            'w' => ['b4'],
            'b' => [],
        ];

        $expectedExplanation = [
            "White has more outpost squares than its opponent."
        ];

        $expectedElaboration = [
            "These are outpost squares: b4.",
        ];

        $board = FenToBoardFactory::create('5k2/7K/8/8/8/P7/8/8 w - -');
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
        $this->assertSame($expectedExplanation, $sqOutpostEval->explain());
        $this->assertSame($expectedElaboration, $sqOutpostEval->elaborate());
    }

    /**
     * @test
     */
    public function d4_b4_b5()
    {
        $expectedResult = [
            'w' => [
                'b5',
            ],
            'b' => [
                'b4', 'd4',
            ],
        ];

        $expectedExplanation = [
            "Black has more outpost squares than its opponent."
        ];

        $expectedElaboration = [
            "These are outpost squares: b4, d4, b5.",
        ];

        $board = FenToBoardFactory::create('5k2/7K/8/2p5/P7/8/8/8 w - -');
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
        $this->assertSame($expectedExplanation, $sqOutpostEval->explain());
        $this->assertSame($expectedElaboration, $sqOutpostEval->elaborate());
    }

    public function wAdvancingData()
    {
        return [
            [
                [
                    'w' => ['b3'],
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
                    'b' => ['d6'],
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
                    'b' => ['g6'],
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
                    'w' => ['e3'],
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
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];

        $board = FenToBoardFactory::create('r3kb1r/ppq2ppp/2p2n2/4nb2/P1N5/2N3P1/1P2PP1P/R1BQKB1R w KQkq -');
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
    }

    /**
     * @test
     */
    public function c99_ruy_lopez()
    {
        $expectedResult = [
            'w' => [
                'd5',
            ],
            'b' => [
                'd4',
            ],
        ];

        $expectedExplanation = [];

        $expectedElaboration = [
            "These are outpost squares: d4, d5.",
        ];

        $board = FenToBoardFactory::create('r1b2rk1/2q1bppp/p2p1n2/np2p3/3PP3/5N1P/PPBN1PP1/R1BQR1K1 b - - 0 13');
        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->result);
        $this->assertSame($expectedExplanation, $sqOutpostEval->explain());
        $this->assertSame($expectedElaboration, $sqOutpostEval->elaborate());
    }
}
