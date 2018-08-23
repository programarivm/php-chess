<?php

namespace PGNChess\Tests\Unit\PGN;

use PGNChess\PGN\Movetext;
use PHPUnit\Framework\TestCase;

class MovetextTest extends TestCase
{
    /**
     * @test
     */
    public function to_array_numbers_1()
    {
        $movetext = Movetext::init('1.d4 f5')->toArray();

        $this->assertEquals([1], $movetext->numbers);
    }

    /**
     * @test
     */
    public function to_array_numbers_1_to_2()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6')->toArray();

        $this->assertEquals([1, 2], $movetext->numbers);
    }

    /**
     * @test
     */
    public function to_array_numbers_1_to_3()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6')->toArray();

        $this->assertEquals([1, 2, 3], $movetext->numbers);
    }

    /**
     * @test
     */
    public function to_array_numbers_1_to_4()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6 4.Bg2 Bg7')->toArray();

        $this->assertEquals([1, 2, 3, 4], $movetext->numbers);
    }

    /**
     * @test
     */
    public function to_array_numbers_1_to_5()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6 4.Bg2 Bg7 5.b4 O-O')->toArray();

        $this->assertEquals([1, 2, 3, 4, 5], $movetext->numbers);
    }

    /**
     * @test
     */
    public function to_array_notations_1_to_2()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6')->toArray();

        $this->assertEquals(['d4', 'f5', 'Nf3', 'Nf6'], $movetext->notations);
    }

    /**
     * @test
     */
    public function to_array_notations_1_to_3()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6')->toArray();

        $this->assertEquals(['d4', 'f5', 'Nf3', 'Nf6', 'g3', 'g6'], $movetext->notations);
    }

    /**
     * @test
     */
    public function to_array_notations_1_to_4()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6 4.Bg2 Bg7')->toArray();

        $this->assertEquals(['d4', 'f5', 'Nf3', 'Nf6', 'g3', 'g6', 'Bg2', 'Bg7'], $movetext->notations);
    }

    /**
     * @test
     */
    public function to_array_notations_1_to_5()
    {
        $movetext = Movetext::init('1.d4 f5 2.Nf3 Nf6 3.g3 g6 4.Bg2 Bg7 5.b4 O-O')->toArray();

        $this->assertEquals(['d4', 'f5', 'Nf3', 'Nf6', 'g3', 'g6', 'Bg2', 'Bg7', 'b4', 'O-O'], $movetext->notations);
    }
}
