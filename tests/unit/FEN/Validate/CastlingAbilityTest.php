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
    public function K()
    {
        $this->assertTrue(Validate::castling('K'));
    }
}
