<?php

namespace AppBundle\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\SpecificationInterface;
use AppBundle\Service\PercentageService;

/**
 * ShouldSellSpecification
 */
class ShouldSellSpecification implements SpecificationInterface
{
  const TAKER_FEE = 0.16;

  /**
   * Percentage service $percentageService
   * @var PercentageService
   */
  private $percentageService;

  /**
   * Constructor
   * @param PercentageService $service
   */
  public function __construct(PercentageService $service)
  {
    $this->percentageService = $service;
  }

  /**
   * {@inheritdoc}
   */
  public function isSatisfiedBy(
    Ticker $first,
    Ticker $second,
    Ticker $third,
    Ticker $fourth,
    $lastTicker
    ) :bool
  {
    return
      ($first->getBid() < $second->getBid()
      &&
      $second->getBid() > $third->getBid()
      &&
      $third->getBid() > $fourth->getBid()
      &&
      $third->getBid() < $first->getBid())
      &&
      self::TAKER_FEE < $this->percentageService->getGainPercentage($first->getBid(), $second->getBid())
      &&
      (null !== $lastTicker ? $lastTicker->getAsk() < $first->getBid() : true)
    ;
  }
}
