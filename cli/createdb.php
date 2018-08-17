<?php

namespace PGNChess\Cli;

use Dotenv\Dotenv;
use PGNChess\Db\MySql;
use PGNChess\PGN\Tag;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

$mysqli = new \MySQLI(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASSWORD')
);

echo 'This will remove the current PGN Chess database and the data will be lost.' . PHP_EOL;
echo 'Do you want to proceed? (Y/N): ';

$handle = fopen ('php://stdin','r');
$line = fgets($handle);
if (trim($line) != 'Y' && trim($line) != 'y') {
    exit;
}
fclose($handle);

$mysqli->begin_transaction();

$sql = 'DROP DATABASE IF EXISTS ' . getenv('DB_NAME');
$mysqli->query($sql);

$sql = 'CREATE DATABASE ' . getenv('DB_NAME');
$mysqli->query($sql);

$mysqli->select_db(getenv('DB_NAME'));

$sql = 'CREATE TABLE games (' .
    Tag::EVENT          . ' VARCHAR(64) NULL, ' . // STR (Seven Tag Roster)
    Tag::SITE           . ' VARCHAR(64) NULL, ' .
    Tag::DATE           . ' VARCHAR(64) NULL, ' .
    Tag::ROUND          . ' VARCHAR(8) NULL, ' .
    Tag::WHITE          . ' VARCHAR(64) NULL, ' .
    Tag::BLACK          . ' VARCHAR(64) NULL, ' .
    Tag::RESULT         . ' VARCHAR(8) NULL, ' .
    Tag::WHITE_TITLE    . ' VARCHAR(16) NULL, ' . // player related information
    Tag::BLACK_TITLE    . ' VARCHAR(16) NULL, ' .
    Tag::WHITE_ELO      . ' VARCHAR(8) NULL, ' .
    Tag::BLACK_ELO      . ' VARCHAR(8) NULL, ' .
    Tag::WHITE_USCF     . ' VARCHAR(8) NULL, ' .
    Tag::BLACK_USCF     . ' VARCHAR(8) NULL, ' .
    Tag::WHITE_NA       . ' VARCHAR(8) NULL, ' .
    Tag::BLACK_NA       . ' VARCHAR(8) NULL, ' .
    Tag::WHITE_TYPE     . ' VARCHAR(8) NULL, ' .
    Tag::BLACK_TYPE     . ' VARCHAR(8) NULL, ' .
    Tag::EVENT_DATE     . ' VARCHAR(32) NULL, ' . // event related information
    Tag::EVENT_SPONSOR  . ' VARCHAR(64) NULL, ' .
    Tag::SECTION        . ' VARCHAR(32) NULL, ' .
    Tag::STAGE          . ' VARCHAR(32) NULL, ' .
    Tag::BOARD          . ' VARCHAR(32) NULL, ' .
    Tag::OPENING        . ' VARCHAR(32) NULL, ' . // opening information
    Tag::VARIATION      . ' VARCHAR(32) NULL, ' .
    Tag::SUB_VARIATION  . ' VARCHAR(32) NULL, ' .
    Tag::ECO            . ' VARCHAR(32) NULL, ' .
    Tag::NIC            . ' VARCHAR(32) NULL, ' .
    Tag::TIME           . ' VARCHAR(32) NULL, ' . // time and date related information
    Tag::UTC_TIME       . ' VARCHAR(32) NULL, ' .
    Tag::UTC_DATE       . ' VARCHAR(32) NULL, ' .
    Tag::TIME_CONTROL   . ' VARCHAR(32) NULL, ' . // time control
    Tag::SET_UP         . ' VARCHAR(32) NULL, ' . // alternative starting positions
    Tag::FEN            . ' VARCHAR(32) NULL, ' .
    Tag::TERMINATION    . ' VARCHAR(32) NULL, ' . // game conclusion
    Tag::ANNOTATOR      . ' VARCHAR(32) NULL, ' . // miscellaneous
    Tag::MODE           . ' VARCHAR(32) NULL, ' .
    Tag::PLY_COUNT      . ' VARCHAR(32) NULL, ' .
    'movetext  TEXT NOT NULL
)';
$mysqli->query($sql);

$mysqli->commit();
$mysqli->close();
