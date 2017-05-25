<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Pair;

/**
 * Class PairTest
 */
class PairTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider pairsProvider
   */
  public function testGetPair(Pair $pair, array $expected)
  {
    $result = $pair->getPair();
    $this->assertEquals($expected, $result);
  }

  public function pairsProvider()
  {
    return [
      [
        (new Pair())
          ->setId('some_pair')
          ->setAltName('some_altname')
          ->setBase('some_base')
          ->setQuote('some_quote'),
        [
            'some_base',
            'some_quote',
        ],
      ],
      [
        (new Pair())
          ->setId('XETHZEUR')
          ->setAltName('ETHEUR')
          ->setBase('XETH')
          ->setQuote('ZEUR'),
        [
            'XETH',
            'ZEUR',
        ],
      ],
    ];
  }
}
