<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Asset;
use AppBundle\Factory\FactoryInterface;

/**
 * AssetFactory
 */
class AssetFactory implements FactoryInterface
{
  /**
   * {@inheritdoc}
   */
  public function build(array $data)
  {
    return (new Asset())
      ->setId(key($data))
      ->setAltName($data[key($data)]['altname'])
    ;
  }
}
