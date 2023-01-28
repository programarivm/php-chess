<?php

namespace Chess\Tests\Unit\Media;

use Chess\Media\JpgToPgnAn;
use Chess\Tests\AbstractUnitTestCase;

class JpgToPgnAnTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function predict_w_B()
    {
        $filename = self::DATA_FOLDER.'/img/B.jpg';

        $expected = 'B';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_b_B()
    {
        $filename = self::DATA_FOLDER.'/img/b.jpg';

        $expected = 'b';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_w_Q()
    {
        $filename = self::DATA_FOLDER.'/img/Q.jpg';

        $expected = 'Q';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_b_Q()
    {
        $filename = self::DATA_FOLDER.'/img/q.jpg';

        $expected = 'q';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_w_R()
    {
        $filename = self::DATA_FOLDER.'/img/R.jpg';

        $expected = 'R';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_b_R()
    {
        $filename = self::DATA_FOLDER.'/img/r.jpg';

        $expected = 'r';

        $this->assertSame($expected, (new JpgToPgnAn($filename))->predict());
    }
}
