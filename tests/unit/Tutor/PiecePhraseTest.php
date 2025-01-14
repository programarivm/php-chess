<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Phrase\PiecePhrase;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\N;

class PiecePhraseTest extends AbstractUnitTestCase
{
    static private Square $square;

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
