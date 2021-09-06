<?php

namespace Chess\Tests\Unit\FEN\Validate;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class CastlingAbilityTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foobar()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('foobar');
    }

    /**
     * @test
     */
    public function start_b_kqKQ()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('kqKQ');
    }

    /**
     * @test
     */
    public function start_rearrange_KkQq()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('KkQq');
    }

    /**
     * @test
     */
    public function double_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('--');
    }

    /**
     * @test
     */
    public function b_k_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('k-');
    }

    /**
     * @test
     */
    public function empty_string()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castling('');
    }

    /**
     * @test
     */
    public function start_w_KQkq()
    {
        $this->assertEquals('KQkq', Validate::castling('KQkq'));
    }

    /**
     * @test
     */
    public function w_k()
    {
        $this->assertEquals('K', Validate::castling('K'));
    }

    /**
     * @test
     */
    public function w_q()
    {
        $this->assertEquals('Q', Validate::castling('Q'));
    }

    /**
     * @test
     */
    public function b_k()
    {
        $this->assertEquals('k', Validate::castling('k'));
    }

    /**
     * @test
     */
    public function b_q()
    {
        $this->assertEquals('q', Validate::castling('q'));
    }

    /**
     * @test
     */
    public function w_kq()
    {
        $this->assertEquals('KQ', Validate::castling('KQ'));
    }

    /**
     * @test
     */
    public function b_kq()
    {
        $this->assertEquals('kq', Validate::castling('kq'));
    }

    /**
     * @test
     */
    public function hyphen()
    {
        $this->assertEquals('-', Validate::castling('-'));
    }
}
