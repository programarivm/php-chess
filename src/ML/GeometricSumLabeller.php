<?php

namespace Chess\ML;

class GeometricSumLabeller extends AbstractLabeller
{
    public function label(array $end)
    {
        $sum = 0;
        foreach ($end as $key => $val) {
            $sum += pow(2, $key) * $val;
        }

        return round($sum, 2);
    }
}
