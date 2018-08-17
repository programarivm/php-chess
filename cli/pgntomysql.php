<?php

namespace PGNChess\Cli;

use PGNChess\PGN\File\ToMySql as PgnFileToMySql;

require_once __DIR__ . '/../vendor/autoload.php';

$sql = (new PgnFileToMySql($argv[1]))->convert();

echo $sql;
