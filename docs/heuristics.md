# Heuristics

✨ If you ask a chess pro why a chess move is good, they'll probably give you a bunch of reasons, many of them intuitive, about why they made that decision.

It is important to develop your pieces in the opening while trying to control the center of the board at the same time. Castling is an excellent move as long as the king gets safe. Then, in the middlegame space becomes an advantage. And if a complex position can be simplified when you have an advantage, then so much the better. The pawn structure could determine the endgame.

The list of reasons goes on and on.

The mathematician Claude Shannon came to the conclusion that there are more chess moves than atoms in the universe. The game is complex and you need to learn how to make decisions to play chess like a pro. Since no human can calculate more than, let's say 30 moves ahead, it's all about thinking in terms of heuristics.

Heuristics are quick, mental shortcuts that we humans use to make decisions and solve problems in our daily lives. While far from being perfect, heuristics are approximations that help manage cognitive load.

Listed below are the chess heuristics implemented in PHP Chess.

| Heuristic | Description | Evaluation |
| --------- | ----------- | ---------- |
| Absolute fork | A tactic in which a piece attacks multiple pieces at the same time. It is a double attack. A fork involving the enemy king is an absolute fork. | [Chess\Eval\AbsoluteForkEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/AbsoluteForkEvalTest.php) |
| Absolute pin | A tactic that occurs when a piece is shielding the king, so it cannot move out of the line of attack because the king would be put in check. | [Chess\Eval\AbsolutePinEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/AbsolutePinEvalTest.php) |
| Absolute skewer | A tactic in which the enemy king is involved. The king is in check, and it has to move out of danger exposing a more valuable piece to capture. Only line pieces (bishops, rooks and queens) can skewer. | [Chess\Eval\AbsoluteSkewerEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/AbsoluteSkewerEvalTest.php) |
| Advanced pawn | A pawn that is on the fifth rank or higher. | [Chess\Eval\AdvancedPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/AdvancedPawnEvalTest.php) |
| Attack | | [Chess\Eval\AttackEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/AttackEvalTest.php) |
| Backward pawn | The last pawn protecting other pawns in its chain. It is considered a weakness because it cannot advance safely. | [Chess\Eval\BackwardPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/BackwardPawnEvalTest.php) |
| Bad bishop | A bishop that is on the same color as most of own pawns. | [Chess\Eval\BadBishopEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/BadBishopEvalTest.php) |
| Bishop outpost | A bishop on an outpost square is considered a positional advantage because it cannot be attacked by enemy pawns, and as a result, it is often exchanged for another piece. | [Chess\Eval\BishopOutpostEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/BishopOutpostEvalTest.php) |
| Bishop pair | The player with both bishops may definitely have an advantage, especially in the endgame. Furthermore, two bishops can deliver checkmate. | [Chess\Eval\BishopPairEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/BishopPairEvalTest.php) |
| Center | It is advantageous to control the central squares as well as to place a piece in the center. | [Chess\Eval\CenterEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/CenterEvalTest.php) |
| Checkability | Having a king that can be checked is usually considered a disadvantage, and vice versa, it is considered advantageous to have a king that cannot be checked. A checkable king is vulnerable to forcing moves. | [Chess\Eval\CheckabilityEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/CheckabilityEvalTest.php) |
| Connectivity | The connectivity of the pieces measures how loosely the pieces are. | [Chess\Eval\ConnectivityEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/ConnectivityEvalTest.php) |
| Defense | | [Chess\Eval\DefenseEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/DefenseEvalTest.php) |
| Diagonal opposition | The same as direct opposition, but the two kings are apart from each other diagonally. | [Chess\Eval\DiagonalOppositionEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/DiagonalOppositionEvalTest.php) |
| Direct opposition | A position in which the kings are facing each other being two squares apart on the same rank or file. In this situation, the player not having to move is said to have the opposition. | [Chess\Eval\DirectOppositionEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/DirectOppositionEvalTest.php) |
| Discovered check | A discovered check occurs when the opponent's king can be checked by moving a piece out of the way of another. | [Chess\Eval\DiscoveredCheckEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/DiscoveredCheckEvalTest.php) |
| Doubled pawn | A pawn is doubled if there are two pawns of the same color on the same file. | [Chess\Eval\DoubledPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/DoubledPawnEvalTest.php) |
| Far-advanced pawn | A pawn that is threatening to promote. | [Chess\Eval\FarAdvancedPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/FarAdvancedPawnEvalTest.php) |
| Isolated pawn | A pawn without friendly pawns on the adjacent files. Since it cannot be defended by other pawns it is considered a weakness. | [Chess\Eval\IsolatedPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/IsolatedPawnEvalTest.php) |
| King safety | An unsafe king leads to uncertainty. The probability of unexpected, forced moves will increase as the opponent's pieces get closer to it. | [Chess\Eval\KingSafetyEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/KingSafetyEvalTest.php) |
| Knight outpost | A knight on an outpost square is considered a positional advantage because it cannot be attacked by enemy pawns, and as a result, it is often exchanged for a bishop. | [Chess\Eval\KnightOutpostEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/KnightOutpostEvalTest.php) |
| Material | The player with the most material points has an advantage. The relative values of the pieces are assigned this way: 1 point to a pawn, 3.2 points to a knight, 3.33 points to a bishop, 5.1 points to a rook and 8.8 points to a queen. | [Chess\Eval\MaterialEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/MaterialEvalTest.php) |
| Overloading | A piece that is overloaded with defensive tasks is vulnerable because it can be deflected, meaning it could be forced to leave the square it occupies, typically resulting in an advantage for the opponent. | [Chess\Eval\OverloadingEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/OverloadingEvalTest.php) |
| Passed pawn | A pawn with no opposing pawns on either the same file or adjacent files to prevent it from being promoted. | [Chess\Eval\PassedPawnEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/PassedPawnEvalTest.php) |
| Pressure | | [Chess\Eval\PressureEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/PressureEvalTest.php) |
| Protection | | [Chess\Eval\ProtectionEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/ProtectionEvalTest.php) |
| Relative fork | A tactic in which a piece attacks multiple pieces at the same time. It is a double attack. A fork not involving the enemy king is a relative fork. | [Chess\Eval\RelativeForkEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/RelativeForkEvalTest.php) |
| Relative pin | A tactic that occurs when a piece is shielding a more valuable piece, so if it moves out of the line of attack the more valuable piece can be captured resulting in a material gain. | [Chess\Eval\RelativePinEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/RelativePinEvalTest.php) |
| Space | | [Chess\Eval\SpaceEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/SpaceEvalTest.php) |
| Square outpost | A square protected by a pawn that cannot be attacked by an opponent's pawn. | [Chess\Eval\SqOutpostEval](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Eval/SqOutpostEvalTest.php) |

The evaluation features are used in two heuristics classes: [Chess\FenHeuristics](https://github.com/chesslablab/php-chess/blob/main/tests/unit/FenHeuristicsTest.php) and [Chess\SanHeuristic](https://github.com/chesslablab/php-chess/blob/main/tests/unit/SanHeuristicTest.php). The former allows to transform a FEN position to numbers while the latter transforms an entire chess game in SAN format to numbers.

```php
use Chess\FenHeuristics;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;

$function = new CompleteFunction();

$fen = 'rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -';

$board = FenToBoardFactory::create($fen);

$result = [
    'names' => $function->names(),
    'balance' => (new FenHeuristics($function, $board))->getBalance(),
];

print_r($result);
```

```text
Array
(
    [names] => Array
        (
            [0] => Material
            [1] => Center
            [2] => Connectivity
            [3] => Space
            [4] => Pressure
            [5] => King safety
            [6] => Protection
            [7] => Discovered check
            [8] => Doubled pawn
            [9] => Passed pawn
            [10] => Advanced pawn
            [11] => Far-advanced pawn
            [12] => Isolated pawn
            [13] => Backward pawn
            [14] => Defense
            [15] => Absolute skewer
            [16] => Absolute pin
            [17] => Relative pin
            [18] => Absolute fork
            [19] => Relative fork
            [20] => Outpost square
            [21] => Knight outpost
            [22] => Bishop outpost
            [23] => Bishop pair
            [24] => Bad bishop
            [25] => Diagonal opposition
            [26] => Direct opposition
            [27] => Attack
            [28] => Overloading
        )

    [balance] => Array
        (
            [0] => 0
            [1] => 12.4
            [2] => 0
            [3] => 3
            [4] => 0
            [5] => 0
            [6] => 0
            [7] => 0
            [8] => 0
            [9] => 0
            [10] => 0
            [11] => 0
            [12] => 0
            [13] => 0
            [14] => 0
            [15] => 0
            [16] => 0
            [17] => 0
            [18] => 0
            [19] => 0
            [20] => 0
            [21] => 0
            [22] => 0
            [23] => 0
            [24] => 0
            [25] => 0
            [26] => 0
            [27] => 0
            [28] => 0
        )

)
```

A chess game can be plotted in terms of balance. +1 is the best possible evaluation for White and -1 the best possible evaluation for Black. Both forces being set to 0 means they're balanced.

```php
use Chess\SanHeuristic;
use Chess\Function\CompleteFunction;

$function = new CompleteFunction();

$name = 'Space';

$movetext = '1.e4 d5 2.exd5 Qxd5';

$balance = (new SanHeuristic($function, $name, $movetext))->getBalance();

print_r($balance);
```

```txt
Array
(
    [0] => 0
    [1] => 1
    [2] => 0.25
    [3] => 0.5
    [4] => -1
)
```

🎉 Chess positions and games can now be plotted on charts and processed with machine learning techniques.
