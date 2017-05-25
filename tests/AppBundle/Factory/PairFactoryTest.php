<?php

namespace AppBundle\Tests\Factory;

use AppBundle\Entity\Pair;
use AppBundle\Factory\PairFactory;

/**
 * Class PairFactoryTest
 */
class PairFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PairFactory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new PairFactory();
    }

    /**
     * @dataProvider pairsProvider
     */
    public function testBuild(array $pair, Pair $expected)
    {
      $result = $this->factory->build($pair);
      $this->assertEquals($expected, $result);
      $this->assertEquals(key($pair), $result->getId());
      $this->assertEquals($pair[key($pair)]['altname'], $result->getAltName());
      $this->assertEquals($pair[key($pair)]['base'], $result->getBase());
      $this->assertEquals($pair[key($pair)]['quote'], $result->getQuote());
    }

    public function pairsProvider()
    {
      return [
        [
          [
            'some_pair' => [
              'altname' => 'some_altname',
              'base' => 'some_base',
              'quote' => 'some_quote',
            ],
          ],
          (new Pair())
            ->setId('some_pair')
            ->setAltName('some_altname')
            ->setBase('some_base')
            ->setQuote('some_quote')
        ],
        [
          [
            'XETHZEUR' => [
              'altname' => 'ETHEUR',
              'base' => 'XETH',
              'quote' => 'ZEUR',
            ],
          ],
          (new Pair())
            ->setId('XETHZEUR')
            ->setAltName('ETHEUR')
            ->setBase('XETH')
            ->setQuote('ZEUR')
        ],
      ];
    }
}
