<?php

namespace PGNChess\Cli;

use PGNChess\Db\MySql;

require_once __DIR__ . '/../vendor/autoload.php';

$result = MySql::getInstance()->query('select * from games');
