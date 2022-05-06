<?php

namespace App\Stats;

/**
 * @property    UniqueEntry[]   $uniqueUsers
 * @property    int[]           $crawlers
 * @property    int[]           $statusCodes
 */
class StatsContainer
{
    public array $uniqueUsers = [];
    public array $urls = [];
    public int $traffic = 0;

    public array $crawlers = [];
    public array $statusCodes = [];
}