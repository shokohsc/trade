<?php

namespace AppBundle\Service;

use Symfony\Component\Form\DataTransformerInterface;
use DateTime;

/**
 * OHLCTToickerTransformer
 */
class OHLCToTickerTransformer implements DataTransformerInterface
{
  /**
   * {@inheritdoc}
   */
  public function transform($ohlc) :array
  {
    return [
      'a' => [
        $ohlc[4],
      ],
      'b' => [
        $ohlc[4],
      ],
      'd' => [
        DateTime::createFromFormat('U', $ohlc[0]),
      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function reverseTransform($ticker): array
  {
    return [
      0 => $ticker->getDate()->getTimestamp(),
      1 => $ticker->getAsk(),
      4 => $ticker->getAsk(),
    ];
  }
}
