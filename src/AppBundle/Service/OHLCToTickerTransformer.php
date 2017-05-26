<?php

namespace AppBundle\Service;

use Symfony\Component\Form\DataTransformerInterface;

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
        $ohlc[4]
      ],
      'b' => [
        $ohlc[4]
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function reverseTransform($ticker): array
  {
    return [
      4 => $ticker->getAsk(),
      1 => $ticker->getAsk(),
    ];
  }
}
