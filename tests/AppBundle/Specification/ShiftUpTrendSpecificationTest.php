<?php

namespace AppBundle\Tests\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\ShiftUpTrendSpecification;

/**
 * Class ShiftUpTrendSpecificationTest
 */
class ShiftUpTrendSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShiftUpTrendSpecification
     */
    private $specification;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->specification = new ShiftUpTrendSpecification();
    }

    /**
     * @dataProvider assetsProvider
     */
    public function testIsSatisfiedBy(
      Ticker $first,
      Ticker $second,
      Ticker $third,
      Ticker $fourth,
      bool $isSatisfied
      )
    {
      $result = $this->specification->isSatisfiedBy($first, $second, $third, $fourth);
      $this->assertEquals($isSatisfied, $result);
    }

    public function assetsProvider()
    {
      return [
        [
          (new Ticker())->setAsk(2.0),
          (new Ticker())->setAsk(1.0),
          (new Ticker())->setAsk(2.0),
          (new Ticker())->setAsk(3.0),
          true,
        ],
        [
          (new Ticker())->setAsk(1.0),
          (new Ticker())->setAsk(2.0),
          (new Ticker())->setAsk(3.0),
          (new Ticker())->setAsk(4.0),
          false,
        ],
      ];
    }
}
