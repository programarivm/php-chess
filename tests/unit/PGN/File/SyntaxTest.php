<?php

namespace PGNChess\Tests\Integration\PGN\File;

use Dotenv\Dotenv;
use PGNChess\Db\MySql;
use PGNChess\PGN\File\Syntax as PgnFileSyntax;
use PHPUnit\Framework\TestCase;

class SyntaxTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    public static function setUpBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/../../../../');
        $dotenv->load();
    }

    // TODO ...
}
