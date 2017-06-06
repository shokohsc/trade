<?php

namespace AppBundle\Service;

class PercentageService
{
    public function getGainPercentage(float $quote, int $base)
    {
      return round((($quote - floatval($base)) / floatval($base)) * 100, 2);
    }

    public function getPercentageOutOfNumber(float $number, float $percent)
    {
      return round((($number * $percent) / 100), 2);
    }
}
