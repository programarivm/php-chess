<?php

namespace Chess\Tests\Unit\Combinatorics;

use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\Tests\AbstractUnitTestCase;

class RestrictedPermutationWithRepetitionTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function count_8_13_21_34()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);

        $expected = 588;

        $this->assertEquals($expected, count($set));
    }

    /**
     * @test
     */
    public function first_8_13_21_34()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);

        $expected = [ 34, 13, 13, 8, 8, 8, 8, 8 ];

        $this->assertEquals($expected, $set[0]);
    }

    /**
     * @test
     */
    public function last_8_13_21_34()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);

        $expected = [ 8, 8, 8, 8, 8, 13, 13, 34 ];

        $this->assertEquals($expected, end($set));
    }

    /**
     * @test
     */
    public function permutation_8_13_21_34_start()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);
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
        $set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);
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
    public function count_3_5_8_13_21()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([3, 5, 8, 13, 21], 9, 100);

        $expected = 56448;

        $this->assertEquals($expected, count($set));
    }

    /**
     * @test
     */
    public function first_3_5_8_13_21()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([3, 5, 8, 13, 21], 9, 100);

        $expected = [ 21, 21, 21, 13, 8, 5, 5, 3, 3 ];

        $this->assertEquals($expected, $set[0]);
    }

    /**
     * @test
     */
    public function last_3_5_8_13_21()
    {
        $set = (new RestrictedPermutationWithRepetition())->get([3, 5, 8, 13, 21], 9, 100);

        $expected = [ 3, 3, 5, 5, 8, 13, 21, 21, 21 ];

        $this->assertEquals($expected, end($set));
    }
}
