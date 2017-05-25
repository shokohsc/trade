<?php

namespace AppBundle\Tests\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\ShouldSellSpecification;

/**
 * Class ShouldSellSpecificationTest
 */
class ShouldSellSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShouldSellSpecification
     */
    private $specification;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->specification = new ShouldSellSpecification();
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
          (new Ticker())->setBid(2.0),
          (new Ticker())->setBid(3.0),
          (new Ticker())->setBid(1.5),
          (new Ticker())->setBid(1.0),
          true,
        ],
        [
          (new Ticker())->setBid(4.0),
          (new Ticker())->setBid(3.0),
          (new Ticker())->setBid(2.0),
          (new Ticker())->setBid(1.0),
          false,
        ],
      ];
    }
}
