#### `create(): string`

Creates a FEN string from a `Chess\Board`.

```php
use Chess\Board;
use Chess\FEN\BoardToString;
use Chess\PGN\Convert;

$board = new Board();
$board->play(Convert::toStdObj('w', 'e4'));
$board->play(Convert::toStdObj('b', 'e5'));

$string = (new BoardToString($board))->create();

print_r($string);
```

This code snippet will output the following.

```
rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6
```
