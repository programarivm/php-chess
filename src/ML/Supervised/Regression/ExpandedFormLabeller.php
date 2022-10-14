<?php

namespace Chess\ML\Supervised\Regression;

use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;
use Chess\Exception\MLException;
use Chess\ML\Supervised\AbstractLabeller;
use Chess\Heuristics;

class ExpandedFormLabeller extends AbstractLabeller
{
    /**
     * Returns the label.
     *
     * @param array $end
     * @return string
     */
    public function label(array $end)
    {
        $terms = $this->terms($end);

        return (string) $this->sum($terms);
    }

    /**
     * Returns the terms of the sum as an array of big integers.
     *
     * @param array $end
     * @return array
     */
    protected function terms(array $end): array
    {
        $terms = [];
        foreach ($end as $key => $val) {
            $weight = floor($val * 10);
            $term = BigInteger::of(10)
                ->power($key)
                ->multipliedBy($weight)
                ->toScale(0, RoundingMode::DOWN)
                ->toBigInteger();
            $terms[] = $term;
        }

        return $terms;
    }

    /**
     * Calculates the sum.
     *
     * It'd be nice to sum the terms in a for loop. This workaround is used
     * instead because the BigInteger class is immutable, its value never changes.
     *
     * @link https://github.com/brick/math
     * @param array $terms
     * @return \Brick\Math\BigInteger
     */
    protected function sum(array $terms): BigInteger
    {
        if (count($terms) !== count((new Heuristics())->getDims())) {
            throw new MLException;
        }

        $sum = BigInteger::of(0)
            ->plus($terms[0])
            ->plus($terms[1])
            ->plus($terms[2])
            ->plus($terms[3])
            ->plus($terms[4])
            ->plus($terms[5])
            ->plus($terms[6])
            ->plus($terms[7])
            ->plus($terms[8])
            ->plus($terms[9])
            ->plus($terms[10])
            ->plus($terms[11])
            ->plus($terms[12])
            ->plus($terms[13])
            ->plus($terms[14])
            ->plus($terms[15])
            ->plus($terms[16])
            ->plus($terms[17])
            ->plus($terms[18]);

        return $sum;
    }
}
