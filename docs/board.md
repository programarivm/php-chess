This is a chess board representation that allows to play chess in Portable Game Notation (PGN) format. It is a cornerstone allowing to build multiple features on top of it: FEN string generation, ASCII representation, PNG image creation, position evaluation, etc.

Let's look at some relevant [`Chess\Board`](https://github.com/chesslablab/php-chess/blob/master/src/Board.php) methods available through the following example:

```php
use Chess\Board;

$board = new Board();

$board->play('w', 'e4');
$board->play('b', 'd5');
$board->play('w', 'exd5');
$board->play('b', 'Qxd5');
```

#### `getCaptures(): ?array`

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

#### `getCastle(): array`

Gets the castle status.

```php
$castle = $board->getCastle();

var_export($castle);
```

```text
array (
  'w' =>
  array (
    'isCastled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
  'b' =>
  array (
    'isCastled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
)
```

#### `getHistory(): ?array`

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

#### `getMovetext(): string`

Gets the movetext.

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

Checks out whether the current player is in check.

```php
$isCheck = $board->isCheck();

var_export($isCheck);
```

Output:

```text
false
```

#### `isMate(): bool`

Checks out whether the current player is checkmated.

```php
$isMate = $board->isMate();

var_export($isMate);
```

Output:

```text
false
```

#### `isStalemate(): bool`

Checks out whether the current player is stalemated.

```php
$isStalemate = $board->isStalemate();

var_export($isStalemate);
```

Output:

```text
false
```

#### `play(string $color, string $pgn): bool`

Makes a move.

```php
$board->play('w', 'Nc3');

var_export($board->getMovetext());
```

Output:

```text
'1.e4 d5 2.exd5 Qxd5 3.Nc3'
```

#### `possibleMoves(): ?array`

Returns all possible moves.
