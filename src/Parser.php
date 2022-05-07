<?php

namespace App;

use App\Processors;
use App\Stats;

/**
 * @property    string[]    $argv
 */
class Parser
{
    private array $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    public function parse(): string
    {
        if (count($this->argv) < 2)
            throw new \RuntimeException('Too few arguments');

        $logFile = $this->getLogFile();

        if (is_null($logFile))
            throw new \RuntimeException('Cannot open file');

        $stats = $this->parseLogFile($logFile);

        $platforms = $this->getPlatforms($stats->users);

        $json = $this->getInfoJSON($stats, $platforms);

        if (is_null($json))
            throw new \RuntimeException('Cannot format json output');

        return $json;
    }

    /**
     * param    Stats\User[]   $users
     */
    private function getPlatforms($users)
    {
        $platforms = [];
        $knownPlatforms = Processors\PlatformsProcessor::knownPlatforms();
        foreach ($knownPlatforms as $platform) {
            $platforms[$platform] = 0;
        }

        return array_reduce($users, function ($platforms, Stats\User $u) {
            $platform = $u->platform();
            $platforms[$platform] += $u->viewsCount();
            return $platforms;
        }, $platforms);
    }

    private function getLogFile(): ?\SplFileObject
    {
        $logFilePath = getcwd() . "/{$this->argv[1]}";
        $fileInfo = new \SplFileInfo($logFilePath);

        return $fileInfo->isReadable() ? new \SplFileObject($logFilePath) : null;
    }

    private function parseLogFile(\SplFileObject $logFile): Stats\StatsContainer
    {
        $stats = new Stats\StatsContainer();
        $processor = new Processors\CombinedStatsProcessor($stats);
        while (!$logFile->eof()) {
            $entry = trim($logFile->fgets());
            if (strlen($entry) === 0) continue;
            $processor->process($entry);
        }

        return $stats;
    }

    private function getInfoJSON(Stats\StatsContainer $stats, array $platforms): ?string
    {
        $isCrawler = fn ($p) => in_array($p, array_keys(Processors\PlatformsProcessor::CRAWLERS));

        $json = json_encode([
            'views' => array_reduce($stats->users, fn ($acc, Stats\User $u) => $acc + $u->viewsCount()),
            'urls' => count($stats->requests),
            'traffic' => $stats->traffic,
            'crawlers' => array_filter($platforms, $isCrawler, ARRAY_FILTER_USE_KEY),
            'statusCodes' => $stats->statusCodes,
            'platforms' => array_filter($platforms, fn ($p) => !$isCrawler($p), ARRAY_FILTER_USE_KEY),
        ], JSON_PRETTY_PRINT);

        return $json ? $json : null;
    }
}
