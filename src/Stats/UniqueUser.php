<?php

namespace App\Stats;

use DateInterval;
use DateTime;

class UniqueUser
{
    private int $viewsCount = 0;
    private DateTime $lastView;

    private $viewThreshold = new DateInterval('PT5S');

    public function __construct(string $timeFormat)
    {
        $this->view($timeFormat);
    }

    public function view(string $timeFormat)
    {
        $viewTime = new DateTime($timeFormat);
        if(!$this->lastView || $viewTime > $this->lastView->add($this->viewThreshold))
        {
            $this->lastView = $viewTime;
            $this->viewsCount++;
        }
    }
}