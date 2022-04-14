`Chess\Array\AsciiArray` allows to convert an array of ASCII characters representing a chess position into a [`Chess\Board`](https://php-chess.readthedocs.io/en/latest/board/) object.

Let's look at the methods available through some examples. For further information you may want to check out the tests in [tests/unit/Array/AsciiArrayTest.php](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Array/AsciiArrayTest.php).

---

#### `public function toBoard(string $turn, $castlingAbility = CastlingAbility::NEITHER): Board`

Create a `Chess\Board` object given an ASCII array.

```php
use Chess\Array\AsciiArray;

$array = [
    7 => [ ' r ', ' n ', ' . ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
    6 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' p ', ' . ', ' p ' ],
    5 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
    4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
    3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
    2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' P ', ' . ' ],
    1 => [ ' P ', ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' P ' ],
    0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' K ', ' N ', ' R ' ],
];

$board = (new AsciiArray($array))->toBoard('b', 'kq');
```

#### `public function setElem(string $elem, string $sq): AsciiArray`

Set elements in an ASCII array to then create a `Chess\Board` object with it. Algebraic notation (AN) is used to identify the squares.

```php
use Chess\Array\AsciiArray;

$array = [
    7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
    6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
    5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
    4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
    3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
    2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
    1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
    0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
];

$newArray = (new AsciiArray($array))
    ->setElem(' . ', 'g1')
    ->setElem(' N ', 'f3')
    ->getArray();

$board = (new AsciiArray($newArray))
    ->toBoard('b', 'KQkq');

$board->play('b', 'Nc6');

print_r($board->toAsciiString());
```
```
r  .  b  q  k  b  n  r
p  p  p  p  .  p  p  p
.  .  n  .  .  .  .  .
.  .  .  .  p  .  .  .
.  .  .  .  P  .  .  .
.  .  .  .  .  N  .  .
P  P  P  P  .  P  P  P
R  N  B  Q  K  B  .  R
```
