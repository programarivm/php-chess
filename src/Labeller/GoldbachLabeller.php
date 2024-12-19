<?php

namespace Chess\Labeller;

class GoldbachLabeller extends AbstractLabeller
{
    /**
     * The first forty prime numbers except the number two.
     *
     * @var array
     */

    public array $numbers = [
         0 =>   3,
         1 =>   5,
         2 =>   7,
         3 =>  11,
         4 =>  13,
         5 =>  17,
         6 =>  19,
         7 =>  23,
         8 =>  29,
         9 =>  31,
        10 =>  37,
        11 =>  41,
        12 =>  43,
        13 =>  47,
        14 =>  53,
        15 =>  59,
        16 =>  61,
        17 =>  67,
        18 =>  71,
        19 =>  73,
        20 =>  79,
        21 =>  83,
        22 =>  89,
        23 =>  97,
        24 => 101,
        25 => 103,
        26 => 107,
        27 => 109,
        28 => 113,
        29 => 127,
        30 => 131,
        31 => 137,
        32 => 139,
        33 => 149,
        34 => 151,
        35 => 157,
        36 => 163,
        37 => 167,
        38 => 173,
    ];

    /**
     * Every even natural number greater than 2 is the sum of two prime numbers.
     *
     * @param array $end
     * @return int
     */
    public function label(array $end): int
    {
        $x = 1;
        $y = 1;
        foreach ($end as $key => $val) {
            if ($val > 0) {
                $x *= $this->numbers[$key];
            } elseif ($val < 0) {
                $y *= $this->numbers[$key];
            }
        }

        return $x - $y;
    }
}
