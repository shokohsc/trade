<?php

namespace AppBundle\Service;

use AppBundle\Service\RandomizerInterface;

/**
 * Randomizer
 */
class Randomizer implements RandomizerInterface
{
  /**
   * {@inheritdoc}
   */
  public function rand(float $min, float $max) :float
  {
    return mt_rand($min, $max);
  }
}
