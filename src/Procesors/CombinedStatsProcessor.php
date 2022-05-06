<?php

namespace App\Procesors;

use App\Stats;
use Exception;

class CombinedStatsProcessor
{
    private Stats\StatsContainer $stats;

    public function __construct(Stats\StatsContainer $stats)
    {
        $this->stats = $stats;
    }

    public function process(string $entry)
    {
        $entryData = $this->parse($entry);

        if(is_null($entryData)) 
            throw new Exception("Cannot parse entry format ($entry)");
            
        $this->processUser($entryData['ip'], $entryData['agent'], $entryData['datetime']);
        $this->processRequest($entryData['request']);
        $this->processStatus($entryData['status']);
        $this->processBytes($entryData['bytes']);
        $this->processReferer($entryData['referer']);
    }

    /**
     * @return ?array [
     *      'ip'        => string
     *      'datetime'  => string
     *      'request'   => string
     *      'status'    => string
     *      'bytes'     => int
     *      'referer'   => int
     *      'agent'     => int
     *    ]
     */
    private function parse(string $entry): ?array
    {
        $ipPattern = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';
        $datetimePattern = '\[(.+?)\]';
        $requestPattern = '"(.*?)"';
        $refererPattern = $requestPattern;
        $agentPattern = $requestPattern;

        $matches = [];
        $patternMatchStatus = preg_match(
            "/^$ipPattern - - $datetimePattern $requestPattern (\d+) (\d+) $refererPattern $agentPattern/",
            $entry,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if(!in_array($patternMatchStatus, [1, true])) return null;

        [$ip, $datetime, $request, $status, $bytes, $referer, $agent] = 
            array_map(fn($a) => $a[0], array_splice($matches, 1));

        return [
            'ip' => $ip, 
            'datetime' => $datetime,
            'request' => $request,
            'status' => $status,
            'bytes' => $bytes,
            'referer' => $referer,
            'agent' => $agent
        ];
    }

    private function processUser(string $ip, string $agent, string $datetime)
    {
        $hash = "$ip~$agent";
        
        $user = null;
        if(array_key_exists($hash, $this->stats->uniqueUsers))
            $user = $this->stats->uniqueUsers[$hash];

        if(is_null($user))
            $this->stats->uniqueUsers[$hash] = new Stats\User($agent, $datetime);
        else
            $user->view($datetime);
    }

    private function processRequest(string $request)
    {
        $request = preg_split('/(\?)|( HTTP)/', $request)[0];

        if(isset($this->stats->requests[$request]))
            $this->stats->requests[$request]++;
        else
            $this->stats->requests[$request] = 1;
    }

    private function processStatus(string $status)
    {
        if(isset($this->stats->statusCodes[$status]))
            $this->stats->statusCodes[$status]++;
        else
            $this->stats->statusCodes[$status] = 1;
    }

    private function processBytes(string $bytes)
    {
        $this->stats->traffic += intval($bytes);
    }

    private function processReferer(string $referer)
    {
        if(isset($this->stats->referers[$referer]))
            $this->stats->referers[$referer]++;
        else
            $this->stats->referers[$referer] = 1;
    }
}