<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;

/**
 * Syntax class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Syntax extends AbstractFile
{
    private $invalid;

    public function __construct($filepath)
    {
        parent::__construct($filepath);

        $invalid = [];
    }

    /**
     * Checks if the syntax of a PGN file is valid.
     *
     * @return mixed array|bool
     */
    public function check()
    {
        // TODO ...

        return true;
    }
}
