<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\ToMySql as PgnFileToMySql;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$sql = (new PgnFileToMySql($argv[1]))->convert();

echo $sql;
