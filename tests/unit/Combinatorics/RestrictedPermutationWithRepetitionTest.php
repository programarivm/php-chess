<?php

namespace Chess\Tests\Unit\Combinatorics;

use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\Tests\AbstractUnitTestCase;

class RestrictedPermutationWithRepetitionTest extends AbstractUnitTestCase
{
    public static $permutation;

    public static function setUpBeforeClass(): void
    {
        self::$permutation = new RestrictedPermutationWithRepetition();
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_count()
    {
        $set = self::$permutation->get([8, 13, 21, 34], 8, 100);
        $count = count($set);

        $expected = 588;

        $this->assertEquals($expected, $count);
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_first()
    {
        $set = self::$permutation->get([8, 13, 21, 34], 8, 100);
        $first = $set[0];

        $expected = [ 34, 13, 13, 8, 8, 8, 8, 8 ];

        $this->assertEquals($expected, $first);
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_last()
    {
        $set = self::$permutation->get([8, 13, 21, 34], 8, 100);
        $last = end($set);

        $expected = [ 8, 8, 8, 8, 8, 13, 13, 34 ];

        $this->assertEquals($expected, $last);
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_start()
    {
        $set = self::$permutation->get([8, 13, 21, 34], 8, 100);
        $start = array_slice($set, 0, 5);

        $expected = [
            [ 34, 13, 13, 8, 8, 8, 8, 8 ],
            [ 13, 34, 13, 8, 8, 8, 8, 8 ],
            [ 13, 13, 34, 8, 8, 8, 8, 8 ],
            [ 34, 13, 8, 13, 8, 8, 8, 8 ],
            [ 13, 34, 8, 13, 8, 8, 8, 8 ],
        ];

        $this->assertEquals($expected, $start);
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_end()
    {
        $set = self::$permutation->get([8, 13, 21, 34], 8, 100);
        $end = array_slice($set, -6);

        $expected = [
            [ 13, 8, 8, 8, 8, 8, 13, 34 ],
            [ 8, 13, 8, 8, 8, 8, 13, 34 ],
            [ 8, 8, 13, 8, 8, 8, 13, 34 ],
            [ 8, 8, 8, 13, 8, 8, 13, 34 ],
            [ 8, 8, 8, 8, 13, 8, 13, 34 ],
            [ 8, 8, 8, 8, 8, 13, 13, 34 ],
        ];

        $this->assertEquals($expected, $end);
    }

    /**
     * @test
     */
    public function permutation_3_5_8_13_21_count()
    {
        $set = self::$permutation->get([3, 5, 8, 13, 21], 9, 100);
        $count = count($set);

        $expected = 56448;

        $this->assertEquals($expected, $count);
    }

    /**
     * @test
     */
    public function permutation_3_5_8_13_21_first()
    {
        $set = self::$permutation->get([3, 5, 8, 13, 21], 9, 100);
        $first = $set[0];

        $expected = [ 21, 21, 21, 13, 8, 5, 5, 3, 3 ];

        $this->assertEquals($expected, $first);
    }

    /**
     * @test
     */
    public function permutation_3_5_8_13_21_last()
    {
        $set = self::$permutation->get([3, 5, 8, 13, 21], 9, 100);
        $last = end($set);

        $expected = [ 3, 3, 5, 5, 8, 13, 21, 21, 21 ];

        $this->assertEquals($expected, $last);
    }
}
