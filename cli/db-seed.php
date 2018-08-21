<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Seed as PgnFileSeed;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$result = (new PgnFileSeed($argv[1]))->db();

if ($result->valid === 0) {
    echo 'Whoops! It seems as if no games are valid in this file.' . PHP_EOL;
} elseif (!empty($result->errors)) {
    echo "Whoops! It seems as if some games are not valid. {$result->valid} were inserted into the database." . PHP_EOL;
} else {
    echo "Good! This is a valid PGN file. {$result->valid} games were inserted into the database." . PHP_EOL;
}

if (!empty($result->errors)) {
    echo '--------------------------------------------------------' . PHP_EOL;
    foreach ($result->errors as $error) {
        if (!empty($error['tags'])) {
            foreach ($error['tags'] as $key => $val) {
                echo "$key: $val" . PHP_EOL;
            }
        }
        if (!empty($error['movetext'])) {
            echo $error['movetext'] . PHP_EOL;
        }
        echo '--------------------------------------------------------' . PHP_EOL;
    }
    echo 'Please check these games. Do they provide the STR (Seven Tag Roster)? Is the movetext valid?' . PHP_EOL;
}
