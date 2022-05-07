<?php

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = new App\Parser($argv);
    $json = $app->parse();
    echo "$json\n";
} catch (\RuntimeException $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit();
}
