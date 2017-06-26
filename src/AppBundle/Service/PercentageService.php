<?php

namespace AppBundle\Service;

class PercentageService
{
    public function getGainPercentage(float $start, float $end)
    {
      return 0 !== $end ? round((($end - $start) / $end) * 100, 2) : -100;
    }

    public function getPercentageFrom(float $percent, float $number)
    {
      return round((($number * $percent) / 100), 2);
    }
}
