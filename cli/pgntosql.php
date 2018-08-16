<?php

namespace PGNChess\Cli;

use PGNChess\PGN\Parse;

require_once __DIR__ . '/../vendor/autoload.php';

$sql = (new Parse($argv[1]))->toSql();

echo $sql;
