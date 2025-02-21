<?php

namespace Chess\Tests\Unit\Media;

use Chess\Media\ImgToPiecePlacement;
use Chess\Tests\AbstractUnitTestCase;

class ImgToPiecePlacementTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function predict_01_kaufman_jpg()
    {
        $expected = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/01_kaufman.jpg');

        $this->assertSame($expected, (new ImgToPiecePlacement($image))->predict());
    }

    /**
     * @test
     */
    public function predict_01_kaufman_png()
    {
        $expected = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K';

        $image = imagecreatefrompng(self::DATA_FOLDER.'/img/01_kaufman.png');

        $this->assertSame($expected, (new ImgToPiecePlacement($image))->predict());
    }
}
