<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Seed as PgnFileSeed;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$result = (new PgnFileSeed($argv[1]))->db();

print_r($result);
