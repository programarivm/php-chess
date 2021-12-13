The `Chess\Board` class is essentially a chess board representation that allows to play a game of chess in Portable Game Notation (PGN) format. It is the cornerstone that allows to build multiple features on top of it: FEN string generation, ASCII representation, PNG image creation, position evaluation, and many more cool features.

Let's look at some relevant [`Chess\Board`](https://github.com/chesslablab/php-chess/blob/master/src/Game.php) methods available through the following example:

```php
use Chess\Board;

$board = new Board();

$board->play('w', 'e4');
$board->play('b', 'd5');
$board->play('w', 'exd5');
$board->play('b', 'Qxd5');
```

#### `getCaptures(): array`

Gets the pieces captured by both players as an array of `stdClass` objects.

```php
$captures = $board->getCaptures();

var_export($captures);
```

Output:

```text
array (
  'w' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'identity' => 'P',
         'position' => 'e4',
      ),
       'captured' =>
      (object) array(
         'identity' => 'P',
         'position' => 'd5',
      ),
    ),
  ),
  'b' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'identity' => 'Q',
         'position' => 'd8',
      ),
       'captured' =>
      (object) array(
         'identity' => 'P',
         'position' => 'd5',
      ),
    ),
  ),
)
```

#### `getCastling(): ?array`

Gets the castling status.

```php
$castling = $board->getCastling();

var_export($castling);
```

```text
array (
  'w' =>
  array (
    'castled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
  'b' =>
  array (
    'castled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
)
```

#### `getHistory(): array`

Gets the history as an array of `stdClass` objects.

```php
$history = $board->getHistory();

var_export($history);
```

Output:

```text
array (
  0 =>
  (object) array(
     'pgn' => 'e4',
     'color' => 'w',
     'identity' => 'P',
     'position' => 'e2',
     'isCapture' => false,
     'isCheck' => false,
  ),
  1 =>
  (object) array(
     'pgn' => 'd5',
     'color' => 'b',
     'identity' => 'P',
     'position' => 'd7',
     'isCapture' => false,
     'isCheck' => false,
  ),
  2 =>
  (object) array(
     'pgn' => 'exd5',
     'color' => 'w',
     'identity' => 'P',
     'position' => 'e4',
     'isCapture' => true,
     'isCheck' => false,
  ),
  3 =>
  (object) array(
     'pgn' => 'Qxd5',
     'color' => 'b',
     'identity' => 'Q',
     'position' => 'd8',
     'isCapture' => true,
     'isCheck' => false,
  ),
)
```

#### `getMovetext(): string`

Gets the movetext in text format.

```php
$movetext = $board->getMovetext();

var_export($movetext);
```

Output:

```text
'1.e4 d5 2.exd5 Qxd5'
```

#### `getTurn(): string`

Gets the current turn.

```php
$turn = $board->getTurn();

var_export($turn);
```

Output:

```text
'w'
```

#### `isCheck(): bool`

Finds out if a player is in check.

```php
$isCheck = $board->isCheck();

var_export($isCheck);
```

Output:

```text
false
```

#### `isMate(): bool`

Finds out if a player is checkmated.

```php
$isMate = $board->isMate();

var_export($isMate);
```

Output:

```text
false
```

#### `play(\stdClass $move): bool`

Plays a chess move.

```php
$board->play('w', 'Nc3');

var_export($board->getMovetext());
```

Output:

```text
'1.e4 d5 2.exd5 Qxd5 3.Nc3'
```
