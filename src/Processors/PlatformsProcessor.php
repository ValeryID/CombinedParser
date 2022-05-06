<?php

namespace App\Processors;

use App\Stats;

class PlatformsProcessor
{
    public const CRAWLERS = [
        'Google'       =>  '/.*?(Google)/',
        'Bing'         =>  '/.*?(bing)/i',
        'Baidu'        =>  '/.*?(baidu)/i',
        'Majestic'     =>  '/.*?(mj12bot|majestic)/i',
        'Yahoo'        =>  '/.*?(yahoo)/i',
        'MegaIndex'    =>  '/.*?(megaindex)/i',
        'Yandex'       =>  '/.*?(yandex)/i',
    ];

    public const PLATFORMS = [
        'Windows'   =>  '/Mozilla\/[\d\.]+ \(.*Windows.*\)/',
        'Mac'       =>  '/Mozilla\/[\d\.]+ \(.*Macintosh.*\)/',
        'Linux'     =>  '/Mozilla\/[\d\.]+ \(.*Linux(?!.*Android.*\)).*\)/',
        'Android'   =>  '/Mozilla\/[\d\.]+ \(.*Android.*\)/',
        'iPhone'    =>  '/Mozilla\/[\d\.]+ \(.*iPhone.*\)/',
        'iPad'      =>  '/Mozilla\/[\d\.]+ \(.*iPad.*\)/',
        'iPod'      =>  '/Mozilla\/[\d\.]+ \(.*iPod.*\)/',
    ];

    public static function knownPlatforms(): array
    {
        return array_merge(array_keys(self::CRAWLERS), array_keys(self::PLATFORMS), ['Undefined']);
    }

    public static function parseAgent(string $agent): string
    {
        $patterns = array_merge(self::CRAWLERS, self::PLATFORMS);
        foreach ($patterns as $platform => $pattern) {
            $match = preg_match($pattern, $agent);
            if (in_array($match, [1, true], true)) {
                return $platform;
            }
        }

        return 'Undefined';
    }
}
