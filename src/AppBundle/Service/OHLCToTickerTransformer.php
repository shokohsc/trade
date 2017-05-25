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
        $ohlc[1]
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
      4 => $ticker->getBid(),
      1 => $ticker->getAsk(),
    ];
  }
}
