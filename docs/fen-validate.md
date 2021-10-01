#### `fen(string $string): string`

Validates a FEN string.

```php
use Chess\FEN\Validate;

$string = Validate::fen('foo');
```

This code snippet will output the following.

```
☠ The FEN string should contain a valid piece placement.
```
