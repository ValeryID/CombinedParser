<?php

namespace App\Processors;

use App\Stats;

class PlatformsProcessor
{
    private static array $patterns = [
        'Windows'   =>  '/Mozilla\/[\d\.]+ \(.*Windows.*\)/',
        'Mac'       =>  '/Mozilla\/[\d\.]+ \(.*Macintosh.*\)/',
        'Linux'     =>  '/Mozilla\/[\d\.]+ \(.*Linux(?!.*Android.*\)).*\)/',
        'Android'   =>  '/Mozilla\/[\d\.]+ \(.*Android.*\)/',
        'iPhone'    =>  '/Mozilla\/[\d\.]+ \(.*iPhone.*\)/',
        'iPad'      =>  '/Mozilla\/[\d\.]+ \(.*iPad.*\)/',
        'iPod'      =>  '/Mozilla\/[\d\.]+ \(.*iPod.*\)/',

        'Crawler, Google'       =>  '/.*?(Google)/',
        'Crawler, Bing'         =>  '/.*?(bing)/i',
        'Crawler, Baidu'        =>  '/.*?(baidu)/i',
        'Crawler, Majestic'     =>  '/.*?(mj12bot|majestic)/i',
        'Crawler, Yahoo'        =>  '/.*?(yahoo)/i',
        'Crawler, MegaIndex'    =>  '/.*?(megaindex)/i',
        'Crawler, Yandex'       =>  '/.*?(yandex)/i',
    ];

    public static function parseAgent(string $agent): string
    {
        foreach (self::$patterns as $platform => $pattern) {
            $match = preg_match($pattern, $agent);
            if (in_array($match, [1, true], true)) {
                return $platform;
            }
        }

        return 'Undefined';
    }
}
