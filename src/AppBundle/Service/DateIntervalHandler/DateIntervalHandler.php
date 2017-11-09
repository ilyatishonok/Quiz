<?php

declare(strict_types=1);

namespace AppBundle\Service\DateIntervalHandler;

class DateIntervalHandler
{
    const YEAR_SECONDS = 31536000;
    const MONTH_SECONDS = 2592000;
    const DAY_SECONDS = 8640;
    const HOUR_SECONDS = 3600;
    const MINUTE_SECONDS = 60;

    private $dateInterval;
    private $seconds = 0.0;
    public function __construct(\DateInterval $dateInterval)
    {
        $this->dateInterval = $dateInterval;
        $this->convertIntervalToSeconds();
    }

    private function convertIntervalToSeconds()
    {
        $this->seconds += $this->dateInterval->s;
        $this->seconds += $this->dateInterval->y*static::YEAR_SECONDS;
        $this->seconds += $this->dateInterval->m*static::MONTH_SECONDS;
        $this->seconds += $this->dateInterval->d*static::DAY_SECONDS;
        $this->seconds += $this->dateInterval->h*static::HOUR_SECONDS;
        $this->seconds += $this->dateInterval->i*static::MINUTE_SECONDS;
        $this->seconds += $this->dateInterval->f;

    }

    public function getSeconds()
    {
        return $this->seconds;
    }
}
