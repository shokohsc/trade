<?php

namespace AppBundle\Tests\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\ShouldSellSpecification;
use AppBundle\Service\PercentageService;
use Prophecy\Argument;

/**
 * Class ShouldSellSpecificationTest
 */
class ShouldSellSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PercentageService
     */
    private $service;

    /**
     * @var ShouldSellSpecification
     */
    private $specification;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->service = $this->prophesize(PercentageService::class);
        $this->specification = new ShouldSellSpecification($this->service->reveal());
    }

    /**
     * @dataProvider assetsProvider
     */
    public function testIsSatisfiedBy(
      Ticker $first,
      Ticker $second,
      Ticker $third,
      Ticker $fourth,
      Ticker $lastTicker,
      bool $isSatisfied
      )
    {
      $this->service->getGainPercentage(Argument::Any(), Argument::Any())->willReturn(0);
      $result = $this->specification->isSatisfiedBy($first, $second, $third, $fourth, $lastTicker);
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
          (new Ticker())->setAsk(1.0),
          true,
        ],
        [
          (new Ticker())->setBid(4.0),
          (new Ticker())->setBid(3.0),
          (new Ticker())->setBid(2.0),
          (new Ticker())->setBid(1.0),
          (new Ticker())->setAsk(1.0),
          false,
        ],
      ];
    }
}
