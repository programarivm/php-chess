`Chess\Player\PgnPlayer` allows to play a PGN movetext returning a `Chess\Variant\Classical\Board` object as it is described in the following example. For further information you may want to check out the tests in [tests/unit/Player/PgnPlayerTest.php](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Player/PgnPlayerTest.php).

---

```php
use Chess\Player\PgnPlayer;

$movetext = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3';

$player = new PgnPlayer($movetext);

$board = $player->play()->getBoard();
```

#### `public function play(): PgnPlayer`

Plays a chess game in PGN format.

#### `public function getBoard(): Board`

Returns the resulting `Chess\Variant\Classical\Board` object of playing a game.

#### `public function getMoves(): array`

Returns the PGN moves as an array.
