`Chess\Variant\Classical\Board` is a chessboard representation that allows to play chess in Portable Game Notation (PGN) format. It is the cornerstone to create multiple features such as FEN string processing, ASCII representation, PNG image creation and position evaluation. Let's look at the methods available through the following example. For further information please check out the tests in [tests/unit/Variant/Classical/BoardTest.php](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Classical/BoardTest.php).

```php
use Chess\Variant\Classical\Board;

$board = new Board();

$board->play('w', 'e4');
$board->play('b', 'd5');
$board->play('w', 'exd5');
$board->play('b', 'Qxd5');
```
---

#### `public function getCaptures(): ?array`

Get the pieces captured by both players.

```php
$captures = $board->getCaptures();

var_export($captures);
```
```text
array (
  'w' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'id' => 'P',
         'sq' => 'e4',
      ),
       'captured' =>
      (object) array(
         'id' => 'P',
         'sq' => 'd5',
      ),
    ),
  ),
  'b' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'id' => 'Q',
         'sq' => 'd8',
      ),
       'captured' =>
      (object) array(
         'id' => 'P',
         'sq' => 'd5',
      ),
    ),
  ),
)
```

#### `public function getCastlingAbility(): string`

Get the castling ability in FEN format.

```php
$castlingAbility = $board->getCastlingAbility();

var_export($castlingAbility);
```
```text
'KQkq'
```

#### `public function getHistory(): ?array`

Get the history.

```php
$history = $board->getHistory();

var_export($history);
```
```text
array (
  0 =>
  (object) array(
     'pgn' => 'e4',
     'color' => 'w',
     'id' => 'P',
     'sq' => 'e2',
     'isCapture' => false,
     'isCheck' => false,
  ),
  1 =>
  (object) array(
     'pgn' => 'd5',
     'color' => 'b',
     'id' => 'P',
     'sq' => 'd7',
     'isCapture' => false,
     'isCheck' => false,
  ),
  2 =>
  (object) array(
     'pgn' => 'exd5',
     'color' => 'w',
     'id' => 'P',
     'sq' => 'e4',
     'isCapture' => true,
     'isCheck' => false,
  ),
  3 =>
  (object) array(
     'pgn' => 'Qxd5',
     'color' => 'b',
     'id' => 'Q',
     'sq' => 'd8',
     'isCapture' => true,
     'isCheck' => false,
  ),
)
```

#### `public function getMovetext(): string`

Get the movetext.

```php
$movetext = $board->getMovetext();

var_export($movetext);
```
```text
'1.e4 d5 2.exd5 Qxd5'
```

#### `public function getTurn(): string`

Get the current turn.

```php
$turn = $board->getTurn();

var_export($turn);
```
```text
'w'
```

#### `public function play(string $color, string $pgn): bool`

Make a move in PGN format.

```php
$board->play('w', 'Nc3');

$movetext = $board->getMovetext();

var_export($movetext);
```
```text
'1.e4 d5 2.exd5 Qxd5 3.Nc3'
```

#### `public function playUci(string $color, string $uci): bool`

Make a move in UCI format.

```php
$board->playUci('w', 'b1c3');

$movetext = $board->getMovetext();

var_export($movetext);
```
```text
'1.e4 d5 2.exd5 Qxd5 3.Nc3'
```

#### `public function toAsciiArray(bool $flip = false): array`

Returns an ASCII array.

```php
$array = $board->toAsciiArray();

print_r($array);
```
```
Array
(
    [7] => Array
        (
            [0] =>  r
            [1] =>  n
            [2] =>  b
            [3] =>  .
            [4] =>  k
            [5] =>  b
            [6] =>  n
            [7] =>  r
        )

    [6] => Array
        (
            [0] =>  p
            [1] =>  p
            [2] =>  p
            [3] =>  .
            [4] =>  p
            [5] =>  p
            [6] =>  p
            [7] =>  p
        )

    [5] => Array
        (
            [0] =>  .
            [1] =>  .
            [2] =>  .
            [3] =>  .
            [4] =>  .
            [5] =>  .
            [6] =>  .
            [7] =>  .
        )

    [4] => Array
        (
            [0] =>  .
            [1] =>  .
            [2] =>  .
            [3] =>  q
            [4] =>  .
            [5] =>  .
            [6] =>  .
            [7] =>  .
        )

    [3] => Array
        (
            [0] =>  .
            [1] =>  .
            [2] =>  .
            [3] =>  .
            [4] =>  .
            [5] =>  .
            [6] =>  .
            [7] =>  .
        )

    [2] => Array
        (
            [0] =>  .
            [1] =>  .
            [2] =>  .
            [3] =>  .
            [4] =>  .
            [5] =>  .
            [6] =>  .
            [7] =>  .
        )

    [1] => Array
        (
            [0] =>  P
            [1] =>  P
            [2] =>  P
            [3] =>  P
            [4] =>  .
            [5] =>  P
            [6] =>  P
            [7] =>  P
        )

    [0] => Array
        (
            [0] =>  R
            [1] =>  N
            [2] =>  B
            [3] =>  Q
            [4] =>  K
            [5] =>  B
            [6] =>  N
            [7] =>  R
        )

)
```

#### `public function toAsciiString(bool $flip = false): string`

Returns an ASCII string.

```php
$string = $board->toAsciiString();

print_r($string);
```
```text
r  n  b  .  k  b  n  r
p  p  p  .  p  p  p  p
.  .  .  .  .  .  .  .
.  .  .  q  .  .  .  .
.  .  .  .  .  .  .  .
.  .  .  .  .  .  .  .
P  P  P  P  .  P  P  P
R  N  B  Q  K  B  N  R
```

#### `public function toFen(): string`

Returns a FEN string.

```php
$string = $board->toFen();

print_r($string);
```
```text
rnb1kbnr/ppp1pppp/8/3q4/8/8/PPPP1PPP/RNBQKBNR w KQkq -
```

#### `public function legalSqs(string $sq): ?object`

Returns the legal squares of a piece.

```php
$sqs = $board->legalSqs('d5');

var_export($sqs);
```

`$sqs` is a PHP object with the information described below.

| Property    | Description                          |
|-------------|--------------------------------------|
| `color`     | The piece's color in PGN format      |
| `id`        | The piece's id in PGN format         |
| `sq`        | The piece's position on the board    |
| `moves`     | The piece's possible moves           |
| `enPassant` | The en passant pawn, if any          |

Output:

```text
(object) array(
   'color' => 'b',
   'id' => 'Q',
   'sq' => 'd5',
   'moves' =>
  array (
    0 => 'd6',
    1 => 'd7',
    2 => 'd8',
    3 => 'd4',
    4 => 'd3',
    5 => 'd2',
    6 => 'c5',
    7 => 'b5',
    8 => 'a5',
    9 => 'e5',
    10 => 'f5',
    11 => 'g5',
    12 => 'h5',
    13 => 'c6',
    14 => 'e6',
    15 => 'c4',
    16 => 'b3',
    17 => 'a2',
    18 => 'e4',
    19 => 'f3',
    20 => 'g2',
  ),
)
```

#### `public function isCheck(): bool`

Check out whether the current player is in check.

```php
$isCheck = $board->isCheck();

var_export($isCheck);
```
```text
false
```

#### `public function isMate(): bool`

Check out whether the current player is checkmated.

```php
$isMate = $board->isMate();

var_export($isMate);
```
```text
false
```

#### `public function isStalemate(): bool`

Check out whether the current player is stalemated.

```php
$isStalemate = $board->isStalemate();

var_export($isStalemate);
```
```text
false
```

#### `public function undo(): Board`

Undoes the last move.

```php
$movetext = $board->undo()->getMovetext();

var_export($movetext);
```
```text
'1.e4 d5 2.exd5'
```
