<?php

namespace AppBundle\Specification;

use AppBundle\Entity\Ticker;

/**
 * SpecificationInterface
 */
interface SpecificationInterface
{
    /**
     * Is satisfied by
     * @param  Ticker   $first
     * @param  Ticker   $second
     * @param  Ticker   $third
     * @param  Ticker   $fourth
     * @param  Ticker   $lastTicker
     * @return boolean
     */
    public function isSatisfiedBy(
      Ticker $first,
      Ticker $second,
      Ticker $third,
      Ticker $fourth,
      $lastTicker
      ): bool;
}
