The `Chess\PGN\Movetext` class provides with functionality to parse and process a PGN movetext.

#### `function validate()`

Validates a movetext returning a string if valid.

```php
use Chess\PGN\Movetext;

$movetext = (new Movetext('1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5'))->validate();

print_r($movetext);
```

Output:

```
1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5
```

#### `function sequence(): array`

Splits a movetext into multiple movetexts representing the chess game move by move for further processing.

```php
use Chess\PGN\Movetext;

$sequence = (new Movetext('1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5'))->sequence();

print_r($sequence);
```

Output:

```
Array
(
    [0] => 1.d4 Nf6
    [1] => 1.d4 Nf6 2.Nf3 e6
    [2] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
    [3] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O
    [4] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7
    [5] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6
    [6] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5
)
```
