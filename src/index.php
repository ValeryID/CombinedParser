<?php

use App\Procesors;
use App\Stats\StatsContainer;

require __DIR__ . '/../vendor/autoload.php';

try {
    start($argv);
} catch(RuntimeException $e) {
    stop("Error: {$e->getMessage()}");
}

function start($argv)
{
    if(count($argv) < 2) stop('Too few arguments');

    $dataFilePath = getcwd() . "/{$argv[1]}";
    
    $dataFile = new SplFileObject($dataFilePath);
    
    $stats = new StatsContainer();
    $processor = new Procesors\CombinedStatsProcessor($stats);
    while(!$dataFile->eof()) { 
        $entry = trim($dataFile->fgets());
        if(strlen($entry) > 0) $processor->process($entry);
    }

    var_dump($stats);
}

function stop($message)
{
    die($message);
}