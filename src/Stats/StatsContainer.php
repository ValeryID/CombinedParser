<?php

namespace App\Stats;

/**
 * @property    User[]          $users
 * @property    int[]           $requests
 * @property    int[]           $crawlers
 * @property    int[]           $referers
 * @property    int[]           $statusCodes
 */
class StatsContainer
{
    public array $users = [];
    public array $requests = [];
    public array $referers = [];
    public int $traffic = 0;

    public array $crawlers = [];
    public array $statusCodes = [];
}
