<?php

namespace AppBundle\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\SpecificationInterface;
use AppBundle\Service\PercentageService;

/**
 * ShouldBuySpecification
 */
class ShouldBuySpecification implements SpecificationInterface
{
  const TRADER_FEE = 0.26;

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
      ($first->getAsk() > $second->getAsk()
      &&
      $second->getAsk() < $third->getAsk()
      &&
      $third->getAsk() < $fourth->getAsk()
      &&
      $third->getAsk() > $first->getAsk())
      &&
      self::TRADER_FEE < $this->percentageService->getGainPercentage($first->getAsk(), $second->getAsk())
      &&
      (null !== $lastTicker ? $lastTicker->getBid() > $first->getAsk() : true)
    ;
  }
}
