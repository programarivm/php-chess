<?php

namespace Chess;

use Chess\PGN\Symbol;

/**
 * Ascii.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Ascii
{
    public function toArray(Board $board)
    {
        $array = [
            7 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            6 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            5 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            2 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            1 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            0 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
        ];

        foreach ($board->getPieces() as $piece) {
            $position = $piece->getPosition();
            $rank = $position[0];
            $file = $position[1] - 1;
            Symbol::WHITE === $piece->getColor()
                ? $array[$file][ord($rank)-97] = ' '.$piece->getIdentity().' '
                : $array[$file][ord($rank)-97] = ' '.strtolower($piece->getIdentity()).' ';
        }

        return $array;
    }

    public function print(Board $board): string
    {
        $ascii = '';
        $array = $this->toArray($board);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }
}
