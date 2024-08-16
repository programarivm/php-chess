<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;

class FanMovetext extends SanMovetext
{
    /**
     * Converts a chess move notation to figurine notation
     *
     * @param string $move The chess move in standard notation (e.g., "Nf4").
     * @return string The chess move in figurine notation (e.g., "♘f4").
     */
    private function toFan(string $move): string
    {
        $move = str_replace('R', '♖', $move);
        $move = str_replace('N', '♘', $move);
        $move = str_replace('B', '♗', $move);
        $move = str_replace('Q', '♕', $move);
        $move = str_replace('K', '♔', $move);

        return $move;
    }

    /**
     * Converts a figurine move notation to standard notation
     *
     * @param string $move The chess move in figurine notation (e.g., "♘f4").
     * @return string The chess move in standard notation (e.g., "Nf4").
     */
    private function toSan(string $move): string
    {
        $move = str_replace('♖', 'R', $move);
        $move = str_replace('♘', 'N', $move);
        $move = str_replace('♗', 'B', $move);
        $move = str_replace('♕', 'Q', $move);
        $move = str_replace('♔', 'K', $move);

        return $move;
    }

    protected function insert(): void
    {
        foreach (explode(' ', $this->validated) as $key => $val) {
            $move = $val;
            if (!NagMovetext::glyph($val)) {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(Move::ELLIPSIS, $val);
                    $this->moves[] = Move::ELLIPSIS;
                    $move = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $move = explode('.', $val)[1];
                }

                $fanMove = $this->toFan($move);

                $val = str_replace($move, $fanMove, $val);
                $this->movetext = str_replace($move, $fanMove, $this->movetext);

                $this->moves[] = $fanMove;
                $this->sanMoves[] = $this->toSan($fanMove);
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }

    public function validate(): string
    {
        foreach ($this->sanMoves as $move) {
            if ($move !== Move::ELLIPSIS) {
                $this->move->validate($move);
            }
        }

        return $this->validated;
    }
}
