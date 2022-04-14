`Chess\Array\PieceArray` allows to convert an array of ASCII characters representing a chess position into an array of object-oriented chess pieces for further processing.

Let's look at the methods available through some examples.

---

#### `public function getArray(): array`

Create an array of pieces given an ASCII array to then create a `Chess\Board` object with it.

```php
use Chess\Array\PieceArray;

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

$pieces = (new PieceArray($array))->getArray();

$board = (new Board($pieces))->setTurn('b');
```
