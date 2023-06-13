<?php

namespace Chess\Tests\Unit\Variant\Capablanca\FEN\Field;

use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\FEN\Field\PiecePlacement;

class PiecePlacementTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function validate_foobar()
    {
        $this->expectException(UnknownNotationException::class);

        PiecePlacement::validate('foobar');
    }

    /**
     * @test
     */
    public function validate_start_capablanca_80()
    {
        $expected = 'rnabqkbcnr/pppppppppp/10/10/10/10/PPPPPPPPPP/RNABQKBCNR';

        $this->assertSame(
            $expected,
            PiecePlacement::validate('rnabqkbcnr/pppppppppp/10/10/10/10/PPPPPPPPPP/RNABQKBCNR'
        ));
    }
}
