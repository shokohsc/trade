<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Pair;
use AppBundle\Factory\FactoryInterface;

/**
 * PairFactory
 */
class PairFactory implements FactoryInterface
{
  /**
   * {@inheritdoc}
   */
  public function build(array $data)
  {
    return (new Pair())
      ->setId(key($data))
      ->setAltName($data[key($data)]['altname'])
      ->setBase($data[key($data)]['base'])
      ->setQuote($data[key($data)]['quote'])
    ;
  }
}
