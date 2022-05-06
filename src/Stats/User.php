<?php

namespace App\Stats;

use App\Processors;
use DateInterval;
use DateTime;

class User
{
    private string $_agent;
    private int $_viewsCount = 0;

    public function __construct(string $agent, string $datetimeFormat)
    {
        $this->viewThreshold = new DateInterval('PT0S');
        $this->_agent = $agent;
        $this->view($datetimeFormat);
    }

    public function view(string $datetimeFormat)
    {
        $this->_viewsCount++;
    }

    public function viewsCount()
    {
        return $this->_viewsCount;
    }

    public function agent(): string
    {
        return $this->_agent;
    }

    public function platform(): string
    {
        return Processors\PlatformsProcessor::parseAgent($this->agent());
    }
}
