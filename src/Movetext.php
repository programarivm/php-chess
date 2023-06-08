<?php

namespace Chess;

use Chess\Variant\Classical\PGN\AN\Termination;
use Chess\Variant\Classical\PGN\Move;

/**
 * Movetext.
 *
 * @license GPL
 */
class Movetext
{
    const SYMBOL_ELLIPSIS = '...';

    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    private Move $move;

    /**
     * Array of PGN moves.
     *
     * @var array
     */
    private array $moves;

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $text
     */
    public function __construct(Move $move, string $text)
    {
        $this->move = $move;
        $this->moves = [];
        $text = $this->filter($text);

        $this->fill($text);
    }

    /**
     * Returns the moves.
     *
     * @return array
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * Validation.
     *
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->moves as $move) {
            if ($move !== self::SYMBOL_ELLIPSIS) {
                $this->move->validate($move);
            }
        }

        return $this->toString();
    }

    /**
     * Converts the array of PGN moves to a string.
     *
     * @return string
     */
    public function toString(): string
    {
        $text = '';
        $offset = 0;
        if (isset($this->moves[0])) {
            if ($this->moves[0] === self::SYMBOL_ELLIPSIS) {
                $text = '1' . self::SYMBOL_ELLIPSIS . "{$this->moves[1]} ";
                $offset = 2;
            }
        }
        for ($i = $offset; $i < count($this->moves); $i++) {
            if ($i % 2 === 0) {
                $text .= (($i / 2) + 1) . ".{$this->moves[$i]}";
            } else {
                $text .= " {$this->moves[$i]} ";
            }
        }

        return trim($text);
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

    /**
     * Filters the given text for further processing.
     *
     * @param string $text
     */
    protected function filter(string $text): string
    {
        // remove PGN symbols
        $text = str_replace(Termination::values(), '', $text);
        // remove comments
        $text = preg_replace("/\{[^)]+\}/", '', $text);
        $text = preg_replace("/\([^)]+\)/", '', $text);
        // replace FIDE notation with PGN notation
        $text = str_replace('0-0', 'O-O', $text);
        $text = str_replace('0-0-0', 'O-O-O', $text);
        // remove spaces between dots
        $text = preg_replace('/\s+\./', '.', $text);

        return $text;
    }

    /**
     * Fills the array of PGN moves with data.
     *
     * @param string $text
     */
    protected function fill(string $text): void
    {
        $moves = explode(' ', $text);
        foreach ($moves as $key => $val) {
            if ($key === 0) {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(self::SYMBOL_ELLIPSIS, $val);
                    $this->moves[] = self::SYMBOL_ELLIPSIS;
                    $this->moves[] = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $this->moves[] = explode('.', $val)[1];
                } else {
                    $this->moves[] = $val;
                }
            } else {
                if (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $this->moves[] = explode('.', $val)[1];
                } else {
                    $this->moves[] = $val;
                }
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }
}
