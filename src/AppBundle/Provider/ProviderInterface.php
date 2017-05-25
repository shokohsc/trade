<?php

namespace AppBundle\Provider;

use AppBundle\Entity\Pair;

/**
 * ProviderInterface
 */
interface ProviderInterface
{
  /**
   * Get array of Ticker
   * @param  Pair $pair
   * @return array
   */
  public function get(Pair $pair) :array;
}
