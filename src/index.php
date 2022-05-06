<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Processors;
use App\Stats;

try {
    start($argv);
} catch(RuntimeException $e) {
    stop("Error: {$e->getMessage()}");
}

function start($argv)
{
    if (count($argv) < 2) {
        stop('Too few arguments');
    }

    $logFile = getLogFile($argv);

    $stats = parseLogFile($logFile);

    $platforms = getPlatforms($stats->users);

    printInfo($stats, $platforms);
}

/**
 * param    Stats\User[]   $users
 */
function getPlatforms($users)
{
    return array_reduce($users, function($platforms, Stats\User $u) {
        $platform = $u->platform();
        if (!isset($platforms[$platform]))
            $platforms[$platform] = 0;

        $platforms[$platform]++;

        return $platforms;
    }, []);
}

function getLogFile($argv): SplFileObject
{
    $logFilePath = getcwd() . "/{$argv[1]}";
    return new SplFileObject($logFilePath);
}

function parseLogFile(SplFileObject $logFile): Stats\StatsContainer
{
    $stats = new Stats\StatsContainer();
    $processor = new Processors\CombinedStatsProcessor($stats);
    while (!$logFile->eof()) {
        $entry = trim($logFile->fgets());
        if (strlen($entry) > 0) {
            $processor->process($entry);
        }
    }

    return $stats;
}

function printInfo(Stats\StatsContainer $stats, array $platforms)
{
    echo json_encode([
        'views' => array_reduce($stats->users, fn ($acc, Stats\User $u) => $acc + $u->viewsCount()),
        'urls' => count($stats->requests),
        'traffic' => $stats->traffic,
        'crawlers' => array_filter($platforms, fn ($p) => substr($p, 0, 7) === 'Crawler', ARRAY_FILTER_USE_KEY),
        'statusCodes' => $stats->statusCodes,
        'platforms' => array_filter($platforms, fn ($p) => substr($p, 0, 7) !== 'Crawler', ARRAY_FILTER_USE_KEY),
    ]);
}

function stop($message)
{
    fwrite(STDERR, $message);
    die();
}
