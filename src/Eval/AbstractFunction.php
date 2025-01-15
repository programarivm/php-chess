<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;

class AbstractFunction
{
    /**
     * Evaluation features.
     *
     * @var array
     */
    public static array $eval = [];

    /**
     * Returns the names of the evaluation features.
     *
     * @return array
     */
    public static function names(): array
    {   
        $names = [];
        foreach (static::$eval as $val) {
            $names[] = (new \ReflectionClass($val))->getConstant('NAME');
        }

        return $names;
    }

    /**
     * Returns an evaluation by name.
     *
     * @param string $name
     * @param \Chess\Variant\AbstractBoard $board
     * @return \Chess\Eval\AbstractEval
     */
    public static function evaluate(string $name, AbstractBoard $board): AbstractEval
    {
        foreach (static::$eval as $val) {
            $class = new \ReflectionClass($val);
            if ($name === $class->getConstant('NAME')) {
                return $class->newInstanceArgs([$board]);
            }
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Returns an array of normalized evaluations.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return array
     */
    public static function normalization(AbstractBoard $board): array
    {
        $items = [];
        foreach (self::names() as $val) {
            $item = self::add(self::evaluate($val, $board));
            $items[] =  $item[Color::W] - $item[Color::B];
        }

        return self::normalize(-1, 1, $items);
    }

    /**
     * A strong position can be created by accumulating small advantages. The
     * relative value of the position without considering checkmate is obtained
     * by counting the advantages in the evaluation array.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return int
     */
    public static function steinitz(AbstractBoard $board): int
    {
        $count = 0;
        $normd = array_filter(self::normalization($board));
        foreach ($normd as $val) {
            if ($val > 0) {
                $count += 1;
            } elseif ($val < 0) {
                $count -= 1;
            }
        }

        return $count;
    }

    /**
     * Returns the mean of the evaluations.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function mean(AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($board));
        $sum = array_sum($normd);
        $count = count($normd);
        if ($count > 0) {
            return round($sum / $count, 4);
        }

        return 0.0;
    }

    /**
     * Returns the value in the middle of the evaluations array.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function median(AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($board));
        sort($normd);
        $size = sizeof($normd);
        if ($size % 2 == 0) {
            return ($normd[$size / 2] + $normd[$size / 2 - 1]) / 2;
        }

        return $normd[floor($size / 2)];
    }

    /**
     * Returns the most common number in the evaluations array.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return null|float
     */
    public static function mode(AbstractBoard $board): ?float
    {
        $normd = array_filter(self::normalization($board));
        foreach ($normd as &$val) {
            $val = strval($val);
        }
        $values = array_count_values($normd);
        arsort($values);
        if (current($values) > 1) {
            return floatval(array_key_first($values));
        }

        return null;
    }

    /**
     * Returns a measure of how spread out the evaluations array is.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function var(AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($board));
        $mean = self::mean($board);
        $sum = 0;
        foreach ($normd as $val) {
            $diff = $val - $mean;
            $sum += $diff * $diff;
        }

        return round($sum / count($normd), 4);
    }

    /**
     * Returns a measure of how spread out the evaluations array is.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function sd(AbstractBoard $board): float
    {
        $var = self::var($board);

        return round(sqrt($var), 4);
    }

    /**
     * Add an item to the evaluations array.
     *
     * @param \Chess\Eval\AbstractEval $eval
     * @return array
     */
    public static function add(AbstractEval $eval): array
    {
        if (is_array($eval->result[Color::W])) {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => count($eval->result[Color::B]),
                    Color::B => count($eval->result[Color::W]),
                ];
            } else {
                $item = [
                    Color::W => count($eval->result[Color::W]),
                    Color::B => count($eval->result[Color::B]),
                ];
            }
        } else {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => $eval->result[Color::B],
                    Color::B => $eval->result[Color::W],
                ];
            } else {
                $item = $eval->result;
            }
        }

        return $item;
    }

    /**
     * Normalizes the given array.
     *
     * @param int $newMin
     * @param int $newMax
     * @param array $unnormd
     * @return array
     */
    public static function normalize(int $newMin, int $newMax, array $unnormd): array
    {
        $min = min($unnormd);
        $max = max($unnormd);

        foreach ($unnormd as $key => $val) {
            if ($val > 0) {
                $unnormd[$key] = round($unnormd[$key] * $newMax / $max, 4);
            } elseif ($val < 0) {
                $unnormd[$key] = round($unnormd[$key] * $newMin / $min, 4);
            } else {
                $unnormd[$key] = 0;
            }
        }

        return $unnormd;
    }
}
