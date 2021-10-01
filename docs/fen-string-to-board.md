#### `create(): Board`

Creates a `Chess\Board` object from a FEN string.

```php
use Chess\Ascii;
use Chess\FEN\StringToBoard;
use Chess\PGN\Convert;

$board = (new StringToBoard('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1'))
    ->create();

$board->play(Convert::toStdObj('b', 'e5'));

$ascii = (new Ascii())->print($board);

print_r($ascii);
```

This code snippet will output the following.

```
 r  n  b  q  k  b  n  r
 p  p  p  p  .  p  p  p
 .  .  .  .  .  .  .  .
 .  .  .  .  p  .  .  .
 .  .  .  .  P  .  .  .
 .  .  .  .  .  .  .  .
 P  P  P  P  .  P  P  P
 R  N  B  Q  K  B  N  R
```
