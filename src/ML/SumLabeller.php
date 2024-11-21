<?php

namespace Chess\ML;

class SumLabeller extends AbstractLabeller
{
    public function label(array $end)
    {
        $sum = array_sum($end);

        return round($sum, 2);
    }
}
