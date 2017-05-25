<?php

namespace AppBundle\Factory;

/**
 * FactoryInterface
 */
interface FactoryInterface
{
  /**
   * Build
   * @param  array  $data
   * @return mixed
   */
  public function build(array $data);
}
