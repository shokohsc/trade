<?php

namespace AppBundle\Tests\Factory;

use AppBundle\Entity\Ticker;
use AppBundle\Factory\TickerFactory;

/**
 * Class TickerFactoryTest
 */
class TickerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TickerFactory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new TickerFactory();
    }

    /**
     * @dataProvider tickersProvider
     */
    public function testBuild(array $ticker, Ticker $expected)
    {
      $result = $this->factory->build($ticker);
      $this->assertEquals($expected, $result);
      $this->assertEquals(key($ticker), $result->getId());
      $this->assertEquals($ticker[key($ticker)]['a'][0], $result->getAsk());
      $this->assertEquals($ticker[key($ticker)]['b'][0], $result->getBid());
    }

    public function tickersProvider()
    {
      return [
        [
          [
            'some_pair' => [
              'a' => [
                0 => 42.5394,
              ],
              'b' => [
                0 => 32.5394,
              ],
            ],
          ],
          (new Ticker())
            ->setId('some_pair')
            ->setAsk(42.5394)
            ->setBid(32.5394)
        ],
        [
          [
            'XETHZEUR' => [
              'a' => [
                0 => 139.96724,
              ],
              'b' => [
                0 => 139.56724,
              ],
            ],
          ],
          (new Ticker())
            ->setId('XETHZEUR')
            ->setAsk(139.96724)
            ->setBid(139.56724)
        ],
      ];
    }
}
