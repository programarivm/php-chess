<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoard;
use Chess\Eval\SqOutpostEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SqOutpostEvalTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wAdvancingData
     * @test
     */
    public function w_advancing($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider wAdvancingUnderAttackData
     * @test
     */
    public function w_advancing_under_attack($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider wAdvancingCanBeAttackedData
     * @test
     */
    public function w_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider bAdvancingData
     * @test
     */
    public function b_advancing($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider bAdvancingUnderAttackData
     * @test
     */
    public function b_advancing_under_attack($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider bAdvancingCanBeAttackedData
     * @test
     */
    public function b_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
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

        $expectedPhrase = [
            "b4 is an outpost square.",
        ];

        $board = FenToBoard::create('5k2/7K/8/8/8/P7/8/8 w - -');

        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->getResult());
        $this->assertSame($expectedPhrase, $sqOutpostEval->getPhrases());
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

        $expectedPhrase = [
            "d4, b4 and b5 are outpost squares.",
        ];

        $board = FenToBoard::create('5k2/7K/8/2p5/P7/8/8/8 w - -');

        $sqOutpostEval = new SqOutpostEval($board);

        $this->assertSame($expectedResult, $sqOutpostEval->getResult());
        $this->assertSame($expectedPhrase, $sqOutpostEval->getPhrases());
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
        $expected = [
            'w' => [],
            'b' => [],
        ];

        $fen = 'r3kb1r/ppq2ppp/2p2n2/4nb2/P1N5/2N3P1/1P2PP1P/R1BQKB1R w KQkq -';
        $board = (new StrToBoard($fen))->create();
        $result = (new SqOutpostEval($board))->getResult();

        $this->assertSame($expected, $result);
    }
}
