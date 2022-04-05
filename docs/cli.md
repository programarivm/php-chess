### Play Chess With an AI

```text
$ php cli/chess.php a1.model
```

This will start a prompt awaiting for your next chess move.

```text
chess > e4
chess > 1.e4 e5
 r  n  b  q  k  b  n  r
 p  p  p  p  .  p  p  p
 .  .  .  .  .  .  .  .
 .  .  .  .  p  .  .  .
 .  .  .  .  P  .  .  .
 .  .  .  .  .  .  .  .
 P  P  P  P  .  P  P  P
 R  N  B  Q  K  B  N  R
chess >
```

The AIs are stored in the [`model`](https://github.com/chesslablab/php-chess/tree/master/model) folder. The default is `a1.model`, if you want to play with a different AI pass it as the first argument to the `cli/chess.php` script.

### Commands Available

#### `fen`

Outputs the FEN string representation of the current chess position.

#### `quit`

Quits the prompt.
