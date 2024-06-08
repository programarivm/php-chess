<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Piece\N;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\PGN\AN\Square;

class PiecePhraseTest extends AbstractUnitTestCase
{
    static private $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function create_whites_knight()
    {
        $expected = "the knight on d4";

        $piece = new N('w', 'd4', self::$square);

        $explanation = PiecePhrase::create($piece);

        $this->assertSame($expected, $explanation);
    }

    /**
     * @test
     */
    public function create_black_knight()
    {
        $expected = "the knight on e4";

        $piece = new N('b', 'e4', self::$square);

        $explanation = PiecePhrase::create($piece);

        $this->assertSame($expected, $explanation);
    }
}
