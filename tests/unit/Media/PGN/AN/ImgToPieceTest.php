<?php

namespace Chess\Tests\Unit\Media\PGN\AN;

use Chess\Media\PGN\AN\ImgToPiece;
use Chess\Tests\AbstractUnitTestCase;

class ImgToPieceTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function predict_a1_52_jpg()
    {
        $expected = '1';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/a1_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_a1_52_png()
    {
        $expected = '1';

        $image = imagecreatefrompng(self::DATA_FOLDER.'/img/a1_52.png');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_w_B_52_jpg()
    {
        $expected = 'B';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/B_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_w_B_52_png()
    {
        $expected = 'B';

        $image = imagecreatefrompng(self::DATA_FOLDER.'/img/B_52.png');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_B_52()
    {
        $expected = 'b';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/b_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_N_60()
    {
        $expected = 'n';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/n_60.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_P_48()
    {
        $expected = 'P';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/P_48.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_P_60()
    {
        $expected = 'p';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/p_60.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_w_Q_48()
    {
        $expected = 'Q';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/Q_48.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_w_Q_52()
    {
        $expected = 'Q';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/Q_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_Q_52()
    {
        $expected = 'q';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/q_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_w_R_52()
    {
        $expected = 'R';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/R_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }

    /**
     * @test
     */
    public function predict_b_R_52()
    {
        $expected = 'r';

        $image = imagecreatefromjpeg(self::DATA_FOLDER.'/img/r_52.jpg');

        $this->assertSame($expected, (new ImgToPiece($image))->predict());
    }
}
