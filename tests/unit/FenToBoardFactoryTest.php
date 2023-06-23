<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board as ClassicalBoard;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $fen = 'foo';
        $board = new ClassicalBoard();

        FenToBoardFactory::create($fen, $board);
    }
}
