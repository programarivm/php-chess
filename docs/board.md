The `Chess\Board` class is essentially a chess board representation that allows to play a game of chess in Portable Game Notation (PGN) format. It is the cornerstone that allows to build multiple features on top of it: FEN string generation, ASCII representation, PNG image creation, position evaluation, and many more cool features.

Let's look at some relevant [`Chess\Board`](https://github.com/chesslablab/php-chess/blob/master/src/Game.php) methods available through the following example:

```php
use Chess\Board;
use Chess\PGN\Convert;

$board = new Board();

$board->play(Convert::toStdObj('w', 'e4'));
$board->play(Convert::toStdObj('b', 'd5'));
$board->play(Convert::toStdObj('w', 'exd5'));
$board->play(Convert::toStdObj('b', 'Qxd5'));
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

#### `play(\stdClass $move): bool`

Plays a chess move.

```php
$board->play(Convert::toStdObj('w', 'Nc3'));

var_export($board->getMovetext());
```

Output:

```text
'1.e4 d5 2.exd5 Qxd5 3.Nc3'
```
