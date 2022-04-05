`Chess\Board` object to FEN string.

#### `create(): string`

Creates a FEN string.

```php
use Chess\Board;
use Chess\FEN\BoardToStr;

$board = new Board();
$board->play('w', 'e4');
$board->play('b', 'e5');

$string = (new BoardToStr($board))->create();

print_r($string);
```

This code snippet will output the following.

```
rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6
```
