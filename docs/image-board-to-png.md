#### `output(string $filepath)`

Creates a PNG image from a particular `Chess\Board` object.

```php
use Chess\FEN\StringToBoard;
use Chess\Image\BoardToPng;

$board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
    ->create();

(new BoardToPng($board))->output('01_kaufman.png');
```

This will create the `01_kaufman.png` file.

[![Figure 1](https://github.com/chesslablab/php-chess/blob/master/tests/data/img/01_kaufman.png)
Figure 1. Position 1 of the Kaufman test
