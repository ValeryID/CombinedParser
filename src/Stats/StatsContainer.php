<?php

namespace App\Stats;

/**
 * @property    uniqueUser[]   $uniqueUsers
 * @property    int[]           $requests
 * @property    int[]           $crawlers
 * @property    int[]           $referers
 * @property    int[]           $statusCodes
 */
class StatsContainer
{
    public array $uniqueUsers = [];
    public array $requests = [];
    public array $referers = [];
    public int $traffic = 0;

    public array $crawlers = [];
    public array $statusCodes = [];
}