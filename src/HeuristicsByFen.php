<?php

namespace Chess;

use Chess\EvalFunction;
use Chess\Eval\InverseEvalInterface;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\FEN\StrToBoard as CapablancaFenStrToBoard;
use Chess\Variant\CapablancaFischer\Board as CapablancaFischerBoard;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * HeuristicsByFen
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class HeuristicsByFen
{
    protected ClassicalBoard $board;

    protected EvalFunction $evalFunction;

    protected array $result;

    protected array $balance;

    public function __construct(string $fen, string $variant = '')
    {
        if ($variant === Chess960Board::VARIANT) {
            $this->board = (new ClassicalFenStrToBoard($fen))->create();
        } elseif ($variant === CapablancaBoard::VARIANT) {
            $this->board = (new CapablancaFenStrToBoard($fen))->create();
        } elseif ($variant === CapablancaFischerBoard::VARIANT) {
            $this->board = (new CapablancaFenStrToBoard($fen))->create();
        } else {
            $this->board = (new ClassicalFenStrToBoard($fen))->create();
        }

        $this->evalFunction = new EvalFunction();

        $this->calc()->balance();
    }

    /**
     * Returns the current evaluation.
     *
     * @return array
     */
    public function eval(): array
    {
        $result = [
            Color::W => 0,
            Color::B => 0,
        ];

        $weights = $this->evalFunction->weights();

        for ($i = 0; $i < count($this->evalFunction->getEval()); $i++) {
            $result[Color::W] += $weights[$i] * $this->result[Color::W][$i];
            $result[Color::B] += $weights[$i] * $this->result[Color::B][$i];
        }

        $result[Color::W] = round($result[Color::W], 2);
        $result[Color::B] = round($result[Color::B], 2);

        return $result;
    }

    /**
     * Heristics calc.
     *
     * @return HeuristicsByFen
     */
    protected function calc(): HeuristicsByFen
    {
        foreach ($this->evalFunction->getEval() as $key => $val) {
            $heuristic = new $key($this->board);
            $eval = $heuristic->eval();
            if (is_array($eval[Color::W])) {
                if ($heuristic instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => count($eval[Color::B]),
                        Color::B => count($eval[Color::W]),
                    ];
                } else {
                    $item[] = [
                        Color::W => count($eval[Color::W]),
                        Color::B => count($eval[Color::B]),
                    ];
                }
            } else {
                if ($heuristic instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => $eval[Color::B],
                        Color::B => $eval[Color::W],
                    ];
                } else {
                    $item[] = $eval;
                }
            }
        }

        $this->result[Color::W] = array_column($item, Color::W);
        $this->result[Color::B] = array_column($item, Color::B);

        return $this;
    }

    protected function balance(): HeuristicsByFen
    {
        foreach ($this->result[Color::W] as $key => $val) {
            $this->balance[$key] =
                round($this->result[Color::W][$key] - $this->result[Color::B][$key], 2);
        }

        return $this;
    }

    public function getBalance(): array
    {
        return $this->balance;
    }
}
