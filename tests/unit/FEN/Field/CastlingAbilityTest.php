<?php

namespace Chess\Tests\Unit\FEN\Field;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\AN\Piece;
use Chess\Tests\AbstractUnitTestCase;

class CastlingAbilityTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function validate_foobar()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('foobar');
    }

    /**
     * @test
     */
    public function validate_start_b_kqKQ()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('kqKQ');
    }

    /**
     * @test
     */
    public function validate_start_rearrange_KkQq()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('KkQq');
    }

    /**
     * @test
     */
    public function validate_double_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('--');
    }

    /**
     * @test
     */
    public function validate_b_k_hyphen()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('k-');
    }

    /**
     * @test
     */
    public function validate_empty_string()
    {
        $this->expectException(UnknownNotationException::class);

        CastlingAbility::validate('');
    }

    /**
     * @test
     */
    public function validate_start_w_KQkq()
    {
        $this->assertSame('KQkq', CastlingAbility::validate('KQkq'));
    }

    /**
     * @test
     */
    public function validate_w_k()
    {
        $this->assertSame('K', CastlingAbility::validate('K'));
    }

    /**
     * @test
     */
    public function validate_w_q()
    {
        $this->assertSame('Q', CastlingAbility::validate('Q'));
    }

    /**
     * @test
     */
    public function validate_b_k()
    {
        $this->assertSame('k', CastlingAbility::validate('k'));
    }

    /**
     * @test
     */
    public function validate_b_q()
    {
        $this->assertSame('q', CastlingAbility::validate('q'));
    }

    /**
     * @test
     */
    public function validate_w_kq()
    {
        $this->assertSame('KQ', CastlingAbility::validate('KQ'));
    }

    /**
     * @test
     */
    public function validate_b_kq()
    {
        $this->assertSame('kq', CastlingAbility::validate('kq'));
    }

    /**
     * @test
     */
    public function validate_hyphen()
    {
        $this->assertSame('-', CastlingAbility::validate('-'));
    }


    /**
     * @test
     */
    public function remove_w_K_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'w', [Piece::K]);

        $expected = 'Qkq';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function remove_w_Q_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'w', [Piece::Q]);

        $expected = 'Kkq';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function remove_b_k_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'b', [Piece::K]);

        $expected = 'KQq';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function remove_b_q_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'b', [Piece::Q]);

        $expected = 'KQk';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function remove_w_K_Q_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'w', [Piece::K, Piece::Q]);

        $expected = 'kq';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function remove_b_k_q_from_KQkq()
    {
        $castlingAbility = CastlingAbility::remove('KQkq', 'b', [Piece::K, Piece::Q]);

        $expected = 'KQ';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function castle_w_KQkq()
    {
        $castlingAbility = CastlingAbility::castle('KQkq', 'w');

        $expected = 'kq';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function castle_b_KQkq()
    {
        $castlingAbility = CastlingAbility::castle('KQkq', 'b');

        $expected = 'KQ';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function castle_w_b_KQkq()
    {
        $castlingAbility = CastlingAbility::castle('KQkq', 'w');
        $castlingAbility = CastlingAbility::castle($castlingAbility, 'b');

        $expected = '-';

        $this->assertSame($expected, $castlingAbility);
    }

    /**
     * @test
     */
    public function castle_b_w_KQkq()
    {
        $castlingAbility = CastlingAbility::castle('KQkq', 'b');
        $castlingAbility = CastlingAbility::castle($castlingAbility, 'w');

        $expected = '-';

        $this->assertSame($expected, $castlingAbility);
    }
}
