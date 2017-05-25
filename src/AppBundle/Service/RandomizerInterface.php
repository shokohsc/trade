<?php

namespace AppBundle\Service;

/**
 * RandomizerInterface
 */
interface RandomizerInterface
{
  /**
   * Random float
   * @param  float $min
   * @param  float $max
   * @return float
   */
  public function rand(float $min, float $max) :float;
}
