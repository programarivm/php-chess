<?php

namespace Chess\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;

class Movetext
{
    private $text;

    private $order;

    public function __construct(string $text)
    {
        $this->text = $text;

        $this->order = (object) [
            'n' => [],
            'move' => [],
        ];

        $this->build();
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function validate()
    {
        if (!$this->areOrdered()) {
            return false;
        }
        foreach ($this->order->move as $move) {
            if ($move !== Symbol::RESULT_WHITE_WINS &&
                $move !== Symbol::RESULT_BLACK_WINS &&
                $move !== Symbol::RESULT_DRAW &&
                $move !== Symbol::RESULT_UNKNOWN
               ) {
                try {
                    Validate::move($move);
                } catch (UnknownNotationException $e) {
                    return false;
                }
            }
        }

        return $this->filter();
    }

    public function sequence(): array
    {
        $sequence = [];
        for ($i = 0; $i < count($this->order->n); $i++) {
            $j = 2 * $i;
            $item = end($sequence) . " {$this->order->n[$i]}.{$this->order->move[$j]} {$this->order->move[$j+1]}";
            $sequence[] = trim($item);
        }

        return $sequence;
    }

    /**
     * Filters the movetext.
     *
     *      Example:
     *
     *          1.e4  e5 2.  f4 exf4 3. Bc4 d5 4.Bxd5 Qh4+
     *
     *      is filtered this way:
     *
     *          1.e4 e5 2.f4 exf4 3.Bc4 d5 4.Bxd5 Qh4+
     *
     * @return mixed bool|string true if the movetext is valid; otherwise the filtered movetext
     */
    protected function filter()
    {
        $filtered = '';
        for ($i = 0; $i < count($this->order->n); $i++) {
            $filtered .= $this->order->n[$i] . '.' . $this->order->move[$i*2] . ' ';
            if (isset($this->order->move[$i*2+1])) {
                $filtered .= $this->order->move[$i*2+1] . ' ';
            }
        }

        return trim($filtered);
    }

    protected function build()
    {
        // remove curly braces
        $text = preg_replace("/\{[^)]+\}/", '', $this->text);
        // remove parentheses
        $text = preg_replace("/\([^)]+\)/", '', $text);
        // replace fide long castling
        $text = preg_replace("/0-0/", 'O-O', $text);
        // replace fide short castling
        $text = preg_replace("/0-0-0/", 'O-O-O', $text);
        // remove spaces between dots
        $text = preg_replace('/\s+\./', '.', $text);
        $moves = array_filter(explode(' ', $text));
        foreach ($moves as $move) {
            if (preg_match('/^[1-9][0-9]*\.(.*)$/', $move)) {
                $exploded = explode('.', $move);
                $this->order->n[] = $exploded[0];
                $this->order->move[] = $exploded[1];
            } else {
                $this->order->move[] = $move;
            }
        }

        $this->order->move = array_values(array_filter($this->order->move));
    }

    protected function areOrdered(): bool
    {
        $areOrdered = 1;
        for ($i = 0; $i < count($this->order->n); $i++) {
            $areOrdered *= (int) $this->order->n[$i] == $i + 1;
        }

        return (bool) $areOrdered;
    }
}
