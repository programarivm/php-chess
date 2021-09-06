<?php

namespace Chess\Tests\Unit\FEN\Validate;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class StringTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::fen('foo');
    }

    /**
     * @test
     */
    public function kaufman_01_piece_placement_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::fen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP w - - bm Nf6+');
    }

    /**
     * @test
     */
    public function kaufman_01_with_comment()
    {
        $string = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+; id "position 01";';

        $this->assertEquals($string, Validate::fen($string));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $string = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';

        $this->assertEquals($string, Validate::fen($string));
    }
}
