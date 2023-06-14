# Convert FEN

📌 Almost everything in PHP Chess can be done with a chessboard object.

FEN stands for Forsyth-Edwards Notation and is the standard way for describing chess positions using text strings. At some point you'll definitely want to convert a FEN string into a chessboard object, and this can be done according to the variants supported.

| Variant | FEN Converter | Chessboard |
| ------- | ------------- | ---------- |
| Capablanca | `Chess\Variant\Capablanca\FEN\StrToBoard` | `Chess\Variant\Capablanca\Board` |
| Chess960 | `Chess\Variant\Chess960\FEN\StrToBoard` | `Chess\Variant\Chess960\Board` |
| Classical | `Chess\Variant\Classical\FEN\StrToBoard` | `Chess\Variant\Classical\Board` |
