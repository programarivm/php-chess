<?php

namespace Chess\Combinatorics;

/**
 * Permutations with repetitions.
 *
 * @link https://rosettacode.org/wiki/Permutations_with_repetitions#PHP
 */
abstract class AbstractPermutation
{
    protected function permutate($values, $size, $offset): array
    {
        $count = count($values);
        $array = [];

        for ($i = 0; $i < $size; $i++) {
            $selector = ((int) ($offset / ($count ** $i)) % $count);
            $array[$i] = $values[$selector];
        }

        return $array;
    }
}
