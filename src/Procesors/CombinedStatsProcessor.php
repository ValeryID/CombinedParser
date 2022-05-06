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
        $this->processAgent($entryData['agent']);
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

        [$ip, $datetime, $request, $status, $bytes, $referer, $agent] = array_splice($matches, 1);

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

    }

    private function processAgent(string $agent)
    {
        
    }

    private function processRequest(string $request)
    {

    }

    private function processStatus(string $stats)
    {

    }

    private function processBytes(string $bytes)
    {

    }

    private function processReferer(string $referer)
    {

    }

    // private function
}