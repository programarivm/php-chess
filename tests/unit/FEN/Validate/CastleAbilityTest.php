<?php

namespace Chess\Tests\Unit\FEN\Validate;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class CastleAbilityTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foobar()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('foobar');
    }

    /**
     * @test
     */
    public function start_b_kqKQ()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('kqKQ');
    }

    /**
     * @test
     */
    public function start_rearrange_KkQq()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('KkQq');
    }

    /**
     * @test
     */
    public function double_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('--');
    }

    /**
     * @test
     */
    public function b_k_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('k-');
    }

    /**
     * @test
     */
    public function empty_string()
    {
        $this->expectException(UnknownNotationException::class);

        Validate::castle('');
    }

    /**
     * @test
     */
    public function start_w_KQkq()
    {
        $this->assertSame('KQkq', Validate::castle('KQkq'));
    }

    /**
     * @test
     */
    public function w_k()
    {
        $this->assertSame('K', Validate::castle('K'));
    }

    /**
     * @test
     */
    public function w_q()
    {
        $this->assertSame('Q', Validate::castle('Q'));
    }

    /**
     * @test
     */
    public function b_k()
    {
        $this->assertSame('k', Validate::castle('k'));
    }

    /**
     * @test
     */
    public function b_q()
    {
        $this->assertSame('q', Validate::castle('q'));
    }

    /**
     * @test
     */
    public function w_kq()
    {
        $this->assertSame('KQ', Validate::castle('KQ'));
    }

    /**
     * @test
     */
    public function b_kq()
    {
        $this->assertSame('kq', Validate::castle('kq'));
    }

    /**
     * @test
     */
    public function hyphen()
    {
        $this->assertSame('-', Validate::castle('-'));
    }
}
