<?php

namespace AppBundle\Provider;

use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use AppBundle\Provider\ProviderInterface;
use AppBundle\Factory\FactoryInterface;
use AppBundle\Service\RandomizerInterface;

/**
 * RandomTickerProvider
 */
class RandomTickerProvider implements ProviderInterface
{
  const DAY_LONG = 1440;

  const SEED_START = 0;
  const SEED_END = 42000;

  const DELTA_PERCENT = .05;
  const PRECISION = 5;

  /**
   * Ticker factory $tickerFactory
   * @var FactoryInterface
   */
  private $tickerFactory;

  /**
   * Randomizer $randomizer
   * @var RandomizerInterface
   */
  private $randomizer;

/**
 * Pair $pair
 * @var Pair
 */
  private $pair;

  public function __construct(FactoryInterface $factory, RandomizerInterface $randomizer)
  {
    $this->tickerFactory = $factory;
    $this->randomizer = $randomizer;
  }

  /**
   * {@inheritdoc}
   */
  public function get(Pair $pair) :array
  {
    $this->pair = $pair;
    $tickers = [];

    for ($i = 0; $i < self::DAY_LONG; $i++) {
      $data = $this->getTickerData(0 === $i ? null : $tickers[$i - 1]);
      $tickers[] = $this->tickerFactory->build($data);
    }

    return $tickers;
  }

  /**
   * Get random ticker data
   * @param  Ticker|null $ticker
   * @return array
   */
  private function getTickerData(Ticker $ticker = null) :array
  {
    $seed = floatval((mt_rand(self::SEED_START, self::SEED_END) / 1000) . number_format(mt_rand(0, 100), 3));

    $min = floatval(bcsub($seed, ($seed * self::DELTA_PERCENT), self::PRECISION));
    $max = floatval(bcadd($seed, ($seed * self::DELTA_PERCENT), self::PRECISION));

    if (null !== $ticker) {
      $min = floatval(bcsub($ticker->getAsk(), ($ticker->getAsk() * self::DELTA_PERCENT), self::PRECISION));
      $max = floatval(bcadd($ticker->getAsk(), ($ticker->getAsk() * self::DELTA_PERCENT), self::PRECISION));
    }

    $ask = $this->randomizer->rand(($min * 10000), ($max * 10000));
    $bid = $this->randomizer->rand($ask, ($ask + ($ask * self::DELTA_PERCENT)));

    return [
      $this->pair->getId() => [
        'a' => [
          0 => $ask / 10000,
        ],
        'b' => [
          0 => $bid / 10000,
        ],
      ],
    ];
  }
}
