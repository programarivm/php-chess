<?php

namespace Chess\Tests\Unit\Media\FEN\Field;

use Chess\Media\FEN\Field\JpgToPiecePlacement;
use Chess\Tests\AbstractUnitTestCase;

class JpgToPiecePlacementTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function predict_01_kaufman()
    {
        $filename = self::DATA_FOLDER.'/img/01_kaufman.jpg';

        $expected = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K';

        $this->assertSame($expected, (new JpgToPiecePlacement($filename))->predict());
    }
}
