<?php

namespace Chess\ML\Supervised\Regression;

use Chess\ML\Supervised\AbstractLabeller;

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
