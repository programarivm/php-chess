<?php

namespace Chess\ML;

class PrimeLabeller extends AbstractLabeller
{
    /**
     * The first prime numbers.
     *
     * @var array
     */

    public array $numbers = [
         0 =>   2,
         1 =>   3,
         2 =>   5,
         3 =>   7,
         4 =>  11,
         5 =>  13,
         6 =>  17,
         7 =>  19,
         8 =>  23,
         9 =>  29,
        10 =>  31,
        11 =>  37,
        12 =>  41,
        13 =>  43,
        14 =>  47,
        15 =>  53,
        16 =>  59,
        17 =>  61,
        18 =>  67,
        19 =>  71,
        20 =>  73,
        21 =>  79,
        22 =>  83,
        23 =>  89,
        24 =>  97,
        25 => 101,
        26 => 103,
        27 => 107,
        28 => 109,
        29 => 113,
        30 => 127,
        31 => 131,
        32 => 137,
        33 => 139,
        34 => 149,
        35 => 151,
        36 => 157,
        37 => 163,
        38 => 167,
        39 => 173,
    ];

    public function label(array $end)
    {
        $x = $y = 1;
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
