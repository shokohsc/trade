<?php

namespace AppBundle\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\SpecificationInterface;

/**
 * ShouldSellSpecification
 */
class ShouldSellSpecification implements SpecificationInterface
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
      $first->getBid() <= $second->getBid()
      &&
      $second->getBid() >= $third->getBid()
      &&
      $third->getBid() >= $fourth->getBid()
      &&
      $third->getBid() < $first->getBid()
    ;
  }
}
