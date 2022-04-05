<?php

namespace Chess\FEN;

/**
 * FEN string to PGN.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class StrToPgn extends AbstractStrToPgn
{
    protected function find(array $legal)
    {
        foreach ($legal as $key => $val) {
            if ($this->toFen === current($val)) {
                return key($val);
            }
        }

        return null;
    }
}
