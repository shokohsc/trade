<?php

namespace AppBundle\Service;

class PercentageService
{
    public function getGainPercentage(float $start, float $end)
    {
      return round((($start - $end) / $end) * 100, 2);
    }

    public function getPercentageFrom(float $percent, float $number)
    {
      return round((($number * $percent) / 100), 2);
    }
}
