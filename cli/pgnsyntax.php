<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Validate as PgnFileValidate;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$isValid = (new PgnFileValidate)->syntax($argv[1]);

// TODO ...
