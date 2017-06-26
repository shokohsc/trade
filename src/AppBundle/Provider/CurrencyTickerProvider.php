<?php

namespace AppBundle\Provider;

use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use AppBundle\Provider\ProviderInterface;
use AppBundle\Factory\FactoryInterface;
use AndreasGlaser\KPC\KPC;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * CurrencyTickerProvider
 */
class CurrencyTickerProvider implements ProviderInterface
{
  /**
   * OHLC interval in minutes
   * Valid intervals: 1,5,15,30,60,240,1440,10080,21600
   * @var integer
   */
  const OHLC_INTERVAL = 60;

  /**
   * Ticker factory $tickerFactory
   * @var FactoryInterface
   */
  private $tickerFactory;

  /**
   * Transformer $transformer
   * @var DataTransformerInterface
   */
  private $ohlcTickerTransformer;

  /**
   * KPC kraken client $kraken
   * @var KPC
   */
  private $kraken;

  public function __construct(FactoryInterface $factory, DataTransformerInterface $transformer, KPC $kraken)
  {
    $this->tickerFactory = $factory;
    $this->ohlcTickerTransformer = $transformer;
    $this->kraken = $kraken;
  }

  /**
   * {@inheritdoc}
   */
  public function get(Pair $pair): array
  {
    $tickers = [];
    $result = $this->kraken->getOHLC($pair->getId(), self::OHLC_INTERVAL);
    $json = json_decode($result->contents, true);
    $data = $json['result'][array_keys($json['result'])[0]];

    foreach ($data as $ohlc) {
      $ticker = [
        $pair->getId() => $this->ohlcTickerTransformer->transform($ohlc),
      ];
      $tickers[] = $this->tickerFactory->build($ticker);
    }

    return $tickers;
  }
}
