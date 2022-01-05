A chess game can be thought of in terms of snapshots describing what's going on the board as reported by a number of evaluation features. Chess positions can be evaluated by taking heuristic pictures of the game.

Let's look at the `Chess\HeuristicPicture` methods available through the following example:

```php
use Chess\Game;
use Chess\HeuristicPicture;

$game = new Game();

$game->play('w', 'e4');
$game->play('b', 'e6');
$game->play('w', 'd4');
$game->play('b', 'd5');
```

#### `function getDimensions(): array`

Returns the evaluation features also known as dimensions.

```php
$dimensions = (new HeuristicPicture($game->movetext()))
    ->getDimensions();

print_r($dimensions);
```

Output:

```text
Array
(
    [Chess\Evaluation\MaterialEvaluation] => 21
    [Chess\Evaluation\CenterEvaluation] => 21
    [Chess\Evaluation\ConnectivityEvaluation] => 13
    [Chess\Evaluation\SpaceEvaluation] => 5
    [Chess\Evaluation\PressureEvaluation] => 5
    [Chess\Evaluation\KingSafetyEvaluation] => 5
    [Chess\Evaluation\TacticsEvaluation] => 5
    [Chess\Evaluation\AttackEvaluation] => 5
    [Chess\Evaluation\DoubledPawnEvaluation] => 5
    [Chess\Evaluation\PassedPawnEvaluation] => 5
    [Chess\Evaluation\IsolatedPawnEvaluation] => 5
    [Chess\Evaluation\BackwardPawnEvaluation] => 5
)
```

Weights are Fibonacci numbers (5, 13, 21) the sum of which equals to 100 as per a multiple-criteria decision analysis (MCDA) based on the point allocation method. This allows to label input vectors for further machine learning purposes.

The order in which the different chess evaluation features are arranged as a dimension really doesn't matter. The first Fibonacci permutation e.g. [ 21, 21, 13, 5, 5, 5, 5, 5, 5, 5, 5, 5 ] is used to somehow highlight that a particular dimension is a restricted permutation actually. Let the grandmasters label the chess positions. Once a particular position is successfully transformed into an input vector of numbers, then it can be labeled on the assumption that the best possible move that could be made was made — by a chess grandmaster.

#### `function getPicture(): array`

Takes a heuristic picture of the game.

```php
$pic = (new HeuristicPicture($game->movetext()))
    ->take()
    ->getPicture();

print_r($pic);
```

Output:

```text
Array
(
    [w] => Array
        (
            [0] => Array
                (
                    [0] => 0
                    [1] => 0.5
                    [2] => 0.6
                    [3] => 0.17
                    [4] => 0
                    [5] => 0
                    [6] => 0
                    [7] => 0
                    [8] => 0
                    [9] => 0
                    [10] => 0
                    [11] => 0
                )

            [1] => Array
                (
                    [0] => 0
                    [1] => 1
                    [2] => 0
                    [3] => 1
                    [4] => 1
                    [5] => 0
                    [6] => 0
                    [7] => 0
                    [8] => 0
                    [9] => 0
                    [10] => 0
                    [11] => 1
                )

        )

    [b] => Array
        (
            [0] => Array
                (
                    [0] => 0
                    [1] => 0
                    [2] => 1
                    [3] => 0
                    [4] => 0
                    [5] => 0
                    [6] => 0
                    [7] => 0
                    [8] => 0
                    [9] => 0
                    [10] => 0
                    [11] => 0
                )

            [1] => Array
                (
                    [0] => 0
                    [1] => 0
                    [2] => 0.6
                    [3] => 0.17
                    [4] => 1
                    [5] => 0
                    [6] => 1
                    [7] => 0
                    [8] => 0
                    [9] => 0
                    [10] => 0
                    [11] => 0
                )

        )

)
```

#### `function getBalance(): array`

Returns the balanced, heuristic picture of the game.

A chess game can be plotted in terms of balance. +1 is the best possible evaluation for White and -1 the best possible evaluation for Black. Both forces being set to 0 means they're actually offset and, therefore, balanced.

```php
$balance = (new HeuristicPicture($game->movetext()))
    ->take()
    ->getBalance();

print_r($balance);
```

Output:

```text
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 0.5
            [2] => -0.4
            [3] => 0.17
            [4] => 0
            [5] => 0
            [6] => 0
            [7] => 0
            [8] => 0
            [9] => 0
            [10] => 0
            [11] => 0
        )

    [1] => Array
        (
            [0] => 0
            [1] => 1
            [2] => -0.6
            [3] => 0.83
            [4] => 0
            [5] => 0
            [6] => -1
            [7] => 0
            [8] => 0
            [9] => 0
            [10] => 0
            [11] => 1
        )

)
```

#### `function evaluate(): array`

Returns the evaluation of the chess position in a human readable format. The result obtained suggests which player is probably better.

```php
$eval = (new HeuristicPicture($game->movetext()))
    ->evaluate();

print_r($eval);
```

Output:

```text
Array
(
    [w] => 36
    [b] => 18.65
)
```
