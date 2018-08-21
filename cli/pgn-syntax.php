<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\File\Validate as PgnFileValidate;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$result = (new PgnFileValidate($argv[1]))->syntax();

if ($result->valid === 0 || !empty($result->errors)) {
    echo 'Whoops! Sorry but this is not a valid PGN file.' . PHP_EOL;
} else {
    echo 'Good! This is a valid PGN file.' . PHP_EOL;
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
