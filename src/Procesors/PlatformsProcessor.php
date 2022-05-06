<?php

namespace App\Processors;

use App\Stats;
use App\Procesors\Platform;

class PlatformsProcessor
{
    private static array $patterns = [
        Platform\ChromeProcessor::class => 
        '/Mozilla\/5.0 \(.+?\) AppleWebKit\/[\d\.]+ \(KHTML, like Gecko\) ((Chrome\/[\d\.]+( Mobile)?)|(CriOS\/[\d\.]+ Mobile\/[\d\w\.]+)) Safari\/[\d\.]+/',

        Platform\ChromeProcessor::class => 
        '/Mozilla\/5.0 \(.+?\) AppleWebKit\/537.36 \(KHTML, like Gecko\) Chrome\/101.0.4951.54 Safari\/537.36/'
    ];
    /**
     * @param    Stats\User[]   $users
     */
    public function __construct(array $users)
    {
        
    }
}