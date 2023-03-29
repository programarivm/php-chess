`Chess\Variant\Capablanca80\FEN\StrToBoard` converts a Capablanca FEN string to a chess board object of type `Chess\Variant\Capablanca80\Board`. Let's look at the methods available in this class through the following example. For further information please check out these [tests](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Capablanca80/FEN/BoardToStrTest.php).

---

#### `public function create(): Board`

Returns a `Chess\Variant\Capablanca80\Board` object given a FEN string.

```php
use Chess\Variant\Capablanca80\FEN\StrToBoard;

$board = (new StrToBoard('r1abqkbcnr/ppppp1pppp/2n7/5p4/5P4/7N2/PPPPP1PPPP/RNABQKBC1R w KQkq -'))
    ->create();
```
