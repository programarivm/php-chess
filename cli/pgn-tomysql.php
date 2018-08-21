<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Convert as PgnFileConvert;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$sql = (new PgnFileConvert($argv[1]))->toMySqlScript();

echo $sql;
