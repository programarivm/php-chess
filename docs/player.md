The `Chess\Player` class allows to play a [PGN movetext](https://en.wikipedia.org/wiki/Portable_Game_Notation#Movetext) returning the corresponding [`Chess\Board`](https://php-chess.readthedocs.io/en/latest/board/) object as it is described in the following example.

```php
use Chess\Player;

$movetext = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3';

$player = new Player($movetext);

$board = $player->play()->getBoard();
```

#### `function getBoard(): Board`

Returns a `Chess\Board` object after a sequence of chess moves has been played.

#### `function play(): Player`

Plays a chess game in PGN movetext format.
