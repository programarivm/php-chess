The `Chess\Combinatorics\RestrictedPermutationWithRepetition` class allows to generate a set of [restricted permutations](https://rosettacode.org/wiki/Permutations_with_repetitions) with repetition considering the parameters provided, which is especially useful to classify chess position for supervised learning.

For further information, please visit:

- [Using Restricted Permutations to Classify Chess Positions for Further Supervised Learning](https://medium.com/geekculture/using-restricted-permutations-to-classify-chess-positions-for-further-supervised-learning-27eeb3f71d82)

#### `get($values, $size, $sum): array`

Returns a set of permutations considering the parameters provided. For example, the following code snippet will create all possible permutations of 8 elements each with the peculiarity — or restriction — that the sum of all elements equals to 100.

```php
use Chess\Combinatorics\RestrictedPermutationWithRepetition;

$set = (new RestrictedPermutationWithRepetition())->get([8, 13, 21, 34], 8, 100);

print_r($set);
```

Output:

```
Array
(
    [0] => Array
        (
            [0] => 34
            [1] => 13
            [2] => 13
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 8
            [7] => 8
        )

    [1] => Array
        (
            [0] => 13
            [1] => 34
            [2] => 13
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 8
            [7] => 8
        )

    [2] => Array
        (
            [0] => 13
            [1] => 13
            [2] => 34
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 8
            [7] => 8
        )

    [3] => Array
        (
            [0] => 34
            [1] => 13
            [2] => 8
            [3] => 13
            [4] => 8
            [5] => 8
            [6] => 8
            [7] => 8
        )

    [4] => Array
        (
            [0] => 13
            [1] => 34
            [2] => 8
            [3] => 13
            [4] => 8
            [5] => 8
            [6] => 8
            [7] => 8
        )

    ...

    [582] => Array
        (
            [0] => 13
            [1] => 8
            [2] => 8
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 13
            [7] => 34
        )

    [583] => Array
        (
            [0] => 8
            [1] => 13
            [2] => 8
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 13
            [7] => 34
        )

    [584] => Array
        (
            [0] => 8
            [1] => 8
            [2] => 13
            [3] => 8
            [4] => 8
            [5] => 8
            [6] => 13
            [7] => 34
        )

    [585] => Array
        (
            [0] => 8
            [1] => 8
            [2] => 8
            [3] => 13
            [4] => 8
            [5] => 8
            [6] => 13
            [7] => 34
        )

    [586] => Array
        (
            [0] => 8
            [1] => 8
            [2] => 8
            [3] => 8
            [4] => 13
            [5] => 8
            [6] => 13
            [7] => 34
        )

    [587] => Array
        (
            [0] => 8
            [1] => 8
            [2] => 8
            [3] => 8
            [4] => 8
            [5] => 13
            [6] => 13
            [7] => 34
        )

    )
```

For further information on how to use the `RestrictedPermutationWithRepetition` class you can always go look at the [PHP Chess tests](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Combinatorics/RestrictedPermutationWithRepetitionTest.php) and copy code from there.
