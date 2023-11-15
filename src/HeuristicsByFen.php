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
    /**
     * Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    protected ClassicalBoard $board;

    /**
     * The evaluation function.
     *
     * @var \Chess\EvalFunction
     */
    protected EvalFunction $evalFunction;

    /**
     * The chess evaluations.
     *
     * @var array
     */
    protected array $result;

    /**
     * The balanced evaluations.
     *
     * @var array
     */
    protected array $balance = [];

    /**
     * Constructor.
     *
     * @param string $fen
     * @param string $variant
     */
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
     * Returns the balance.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Calculates the evaluation.
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

    /**
     * Calculates the balance.
     *
     * @return HeuristicsByFen
     */
    protected function balance(): HeuristicsByFen
    {
        foreach ($this->result[Color::W] as $key => $val) {
            $this->balance[$key] =
                round($this->result[Color::W][$key] - $this->result[Color::B][$key], 2);
        }

        return $this;
    }
}
