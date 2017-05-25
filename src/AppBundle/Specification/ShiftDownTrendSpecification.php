<?php

namespace AppBundle\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\SpecificationInterface;

/**
 * ShiftDownTrendSpecification
 */
class ShiftDownTrendSpecification implements SpecificationInterface
{
  /**
   * {@inheritdoc}
   */
  public function isSatisfiedBy(
    Ticker $first,
    Ticker $second,
    Ticker $third,
    Ticker $fourth
    ) :bool
  {
    return
      $first->getAsk() <= $second->getAsk()
      &&
      $second->getAsk() >= $third->getAsk()
      &&
      $third->getAsk() >= $fourth->getAsk()
      &&
      $fourth->getAsk() < $first->getAsk()
    ;
  }
}
