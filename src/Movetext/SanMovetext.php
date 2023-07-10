<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Termination;

/**
 * Standard Algebraic Notation.
 *
 * @license GPL
 */
class SanMovetext extends AbstractMovetext
{
    /**
     * Metadata.
     *
     * @var array
     */
    protected object $metadata;

    protected string $firstMove = '';

    protected string $lastMove = '';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $movetext
     */
    public function __construct(Move $move, string $movetext)
    {
        parent::__construct($move, $movetext);

        $this->firstMove();

        $this->lastMove();

        $this->metadata = (object) [
            'number' => (object) [
                'first' => $this->firstNumber($this->validated),
                'last' => $this->lastNumber($this->validated),
                'current' => $this->currentNumber($this->validated),
            ],
            'turn' => (object) [
                'start' => $this->startTurn($this->validated),
                'end' => $this->endTurn($this->validated),
                'current' => $this->currentTurn($this->validated),
            ],
        ];
    }

    /**
     * Returns the metadata.
     *
     * @return object
     */
    public function getMetadata(): object
    {
        return $this->metadata;
    }

    public function getFirstMove(): string
    {
        return $this->firstMove;
    }

    public function getLastMove(): string
    {
        return $this->lastMove;
    }

    /**
     * Before inserting elements into the array of moves.
     *
     * @return \Chess\Movetext\SanMovetext
     */
    protected function beforeInsert(): SanMovetext
    {
        // remove comments
        $str = preg_replace('(\{.*?\})', '', $this->filtered());
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);

        $this->validated = trim($str);

        return $this;
    }

    /**
     * Insert elements into the array of moves for further validation.
     *
     * @see \Chess\Play\SanPlay
     */
    protected function insert(): void
    {
        foreach (explode(' ', $this->validated) as $key => $val) {
            if ($key === 0) {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(Move::ELLIPSIS, $val);
                    $this->moves[] = Move::ELLIPSIS;
                    $this->moves[] = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $this->moves[] = explode('.', $val)[1];
                } else {
                    $this->moves[] = $val;
                }
            } else {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(Move::ELLIPSIS, $val);
                    $this->moves[] = Move::ELLIPSIS;
                    $this->moves[] = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $this->moves[] = explode('.', $val)[1];
                } else {
                    $this->moves[] = $val;
                }
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }

    /**
     * Returns the first move.
     */
    protected function firstNumber(string $str): int
    {
        $exploded = explode(' ', $str);
        $first = $exploded[0];
        $exploded = explode('.', $first);

        return intval($exploded[0]);
    }

    /**
     * Returns the last move.
     */
    protected function lastNumber(string $str): int
    {
        $exploded = explode(' ', $str);
        $last = end($exploded);
        if (str_contains($last, '.')) {
            $exploded = explode('.', $last);
        } else {
            $last = prev($exploded);
            $exploded = explode('.', $last);
        }

        return intval($exploded[0]);
    }

    /**
     * Returns the current move.
     */
    protected function currentNumber(string $str): int
    {
        $exploded = explode(' ', $str);
        $last = end($exploded);
        if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $last)) {
            return $this->lastNumber($str) + 1;
        } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $last)) {
            return $this->lastNumber($str);
        }

        return $this->lastNumber($str) + 1;
    }

    /**
     * Returns the starting turn.
     */
    protected function startTurn(string $str): string
    {
        if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $str)) {
            return Color::B;
        }

        return Color::W;
    }

    /**
     * Returns the ending turn.
     */
    protected function endTurn(string $str): string
    {
        $exploded = explode(' ', $str);
        $last = end($exploded);
        if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $last)) {
            return Color::B;
        } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $last)) {
            return Color::W;
        }

        return Color::B;
    }

    /**
     * Returns the current turn.
     */
    protected function currentTurn(string $str): string
    {
        return Color::opp($this->endTurn($str));
    }

    protected function firstMove()
    {
        $exploded = explode(' ', $this->validated);

        $this->firstMove = $exploded[0];
    }

    protected function lastMove()
    {
        $exploded = explode(' ', $this->validated);
        $last = end($exploded);
        if (!str_contains($last, '.')) {
            $nextToLast = prev($exploded);
            $this->lastMove = "{$nextToLast} {$last}";
        } else {
            $this->lastMove = $last;
        }
    }

    /**
     * Syntactically validated movetext.
     *
     * The syntactically validated movetext does not contain any comments or
     * parentheses.
     *
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                $this->move->validate($move);
            }
        }

        return $this->validated;
    }

    /**
     * Filtered movetext.
     *
     * The filtered movetext contains comments and parentheses.
     *
     * @return string
     */
    public function filtered(): string
    {
        // remove PGN symbols
        $str = str_replace(Termination::values(), '', $this->movetext);
        // remove variations
        $str = preg_replace('/\(([^()]|(?R))*\)/', '', $str);
        // replace FIDE notation with PGN notation
        $str = str_replace('0-0', 'O-O', $str);
        $str = str_replace('0-0-0', 'O-O-O', $str);
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);
        // remove space between dots
        $str = preg_replace('/\s\./', '.', $str);
        // remove space after dots only in the text outside brackets
        preg_match_all('/[^{}]*(?=(?:[^}]*{[^{]*})*[^{}]*$)/', $str, $matches);
        foreach (array_filter($matches[0]) as $match) {
            $replaced = preg_replace('/\.\s/', '.', $match);
            $str = str_replace($match, $replaced, $str);
        }

        return trim($str);
    }

    /**
     * Returns an array representing the movetext as a sequence of moves.
     *
     * e.g. 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
     *
     * Array
     * (
     *     [0] => 1.d4 Nf6
     *     [1] => 1.d4 Nf6 2.Nf3 e6
     *     [2] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
     * )
     *
     * @return array
     */
    public function sequence(): array
    {
        $n = floor(count($this->moves) / 2);
        $sequence = [];
        for ($i = 0; $i < $n; $i++) {
            $j = 2 * $i;
            if (isset($this->moves[$j+1])) {
                $item = end($sequence) . ' ' .  $i + 1 .
                ".{$this->moves[$j]} {$this->moves[$j+1]}";
                $sequence[] = trim($item);
            }
        }

        return $sequence;
    }
}
