<?php

namespace Chess\FEN;

/**
 * Short FEN string to PGN.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class ShortStrToPgn extends AbstractStrToPgn
{
    protected function find(array $legal)
    {
        foreach ($legal as $key => $val) {
            if (str_starts_with(current($val), $this->toFen)) {
                return key($val);
            }
        }

        return null;
    }
}
