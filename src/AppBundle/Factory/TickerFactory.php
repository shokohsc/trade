<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Ticker;
use AppBundle\Factory\FactoryInterface;

/**
 * TickerFactory
 */
class TickerFactory implements FactoryInterface
{
  /**
   * {@inheritdoc}
   */
  public function build(array $data)
  {
    return (new Ticker())
      ->setId(key($data))
      ->setAsk($data[key($data)]['a'][0])
      ->setBid($data[key($data)]['b'][0])
    ;
  }
}
