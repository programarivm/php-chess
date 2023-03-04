Creates an MP4 video given a set of parameters.

---

#### `public function output(string $filepath): string`

Creates an MP4 video with a randomish filename, for example `620a7d61dcf57.mp4`.

```php
use Chess\Game;
use Chess\Media\BoardToMp4;

$variant = Game::VARIANT_CLASSICAL;
$movetext = '1.e4 g5 2.d4 e6 3.c3 c5 4.dxc5 b6';

$filename = (new BoardToMp4($variant, $movetext))->output(__DIR__.'/../../storage/tmp');
```
