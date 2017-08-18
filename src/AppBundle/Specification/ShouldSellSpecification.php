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
  const TRADER_FEE = 0.16;

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
      $second->getBid() < $third->getBid()
      &&
      $third->getBid() > $fourth->getBid())
      &&
      (null !== $lastTicker ? self::TRADER_FEE < $this->percentageService->getGainPercentage($lastTicker->getBid(), $fourth->getBid()) : true)
      &&
      (null !== $lastTicker ? $lastTicker->getAsk() < $fourth->getBid() : true)
    ;
  }
}
