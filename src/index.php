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
    
    // while(!$dataFile->eof()) var_dump(trim($dataFile->fgets()));
    $processor = new Procesors\CombinedStatsProcessor(new StatsContainer());
    $processor->process(trim($dataFile->fgets()));
    // var_dump();
}

function stop($message)
{
    die($message);
}