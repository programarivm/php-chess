<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Syntax as PgnFileSyntax;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$isValid = (new PgnFileSyntax)->check($argv[1]);

// TODO ...
