`Chess\Player\LanPlayer` allows to play a movetext in long algebraic notation (LAN) returning a `Chess\Variant\Classical\Board` object as it is described in the following example. For further information you may want to check out the tests in [tests/unit/Player/LanPlayerTest.php](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Player/LanPlayerTest.php).

---

```php
use Chess\Player\LanPlayer;

$movetext = 'e2e4 e7e5 g1f3';

$player = new LanPlayer($movetext);

$board = $player->play()->getBoard();
```

#### `public function play(): LanPlayer`

Plays a chess game in LAN format.

#### `public function getBoard(): Board`

Returns the resulting `Chess\Variant\Classical\Board` object of playing a game.

#### `public function getMoves(): array`

Returns the LAN moves as an array.
