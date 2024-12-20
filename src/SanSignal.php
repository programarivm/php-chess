<?php

namespace Chess;

use Chess\EvalFactory;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\FastFunction;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Signal
 *
 * A signal encoding the continuous oscillations of a chessboard.
 */
class SanSignal
{
    /**
     * Maximum number of moves.
     *
     * @var int
     */
    const MAX_MOVES = 300;

    public array $result = [];

    public array $balance = [];

    /**
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(string $movetext, AbstractBoard $board)
    {
        $sanMovetext = new SanMovetext($board->move, $movetext);

        if (!$sanMovetext->validate()) {
            throw new \InvalidArgumentException();
        }

        if (self::MAX_MOVES < count($sanMovetext->moves)) {
            throw new \InvalidArgumentException();
        }

        $function = new FastFunction();
        $this->result[] = array_fill(0, count($function->names()), 0);
        $component = [];

        foreach ($sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$board->play($board->turn, $val)) {
                    throw new MediaException();
                }
                foreach ($function->names() as $val) {
                    $eval = EvalFactory::create($function, $val, $board);
                    $item = $eval->result;
                    if (is_array($item[Color::W])) {
                        if ($eval instanceof InverseEvalInterface) {
                            $item = [
                                Color::W => count($item[Color::B]),
                                Color::B => count($item[Color::W]),
                            ];
                        } else {
                            $item = [
                                Color::W => count($item[Color::W]),
                                Color::B => count($item[Color::B]),
                            ];
                        }
                    } else {
                        if ($eval instanceof InverseEvalInterface) {
                            $item = [
                                Color::W => $item[Color::B],
                                Color::B => $item[Color::W],
                            ];
                        }
                    }
                    $diff = $item[Color::W] - $item[Color::B];
                    $component[] =  $diff;
                }
                $this->result[] = $component;
                $component = [];
            }
        }

        for ($i = 0; $i < count($this->result[0]); $i++) {
            $this->balance[$i] = $this->normalize(-1, 1, array_column($this->result, $i));
        }
    }

    /**
     * Normalizes the balance.
     *
     * @param int $newMin
     * @param int $newMax
     * @param array $values
     * @return array
     */
    protected function normalize(int $newMin, int $newMax, array $values): array
    {
        $min = min($values);
        $max = max($values);

        foreach ($values as $key => $val) {
            if ($val > 0) {
                $values[$key] = round($values[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $values[$key] = round($values[$key] * $newMin / $min, 2);
            } else {
                $values[$key] = 0;
            }
        }

        return $values;
    }
}
