<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\CapablancaFischer\StartPieces;
use Chess\Variant\CapablancaFischer\StartPosition;
use Chess\Variant\CapablancaFischer\Rule\CastlingRule;

class StartPiecesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $startPos = (new StartPosition())->create();
        $castlingRule =  (new CastlingRule($startPos))->getRule();

        $pieces = (new StartPieces($startPos, $castlingRule))->create();

        $this->assertSame(40, count($pieces));
    }
}
