<?php

namespace AppBundle\Tests\Specification;

use AppBundle\Entity\Ticker;
use AppBundle\Specification\ShouldBuySpecification;
use AppBundle\Service\PercentageService;
use Prophecy\Argument;

/**
 * Class ShouldBuySpecificationTest
 */
class ShouldBuySpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PercentageService
     */
    private $service;

    /**
     * @var ShouldBuySpecification
     */
    private $specification;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->service = $this->prophesize(PercentageService::class);
        $this->specification = new ShouldBuySpecification($this->service->reveal());
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
          (new Ticker())->setAsk(2.0),
          (new Ticker())->setAsk(1.0),
          (new Ticker())->setAsk(2.5),
          (new Ticker())->setAsk(3.0),
          (new Ticker())->setBid(1.0),
          true,
        ],
        [
          (new Ticker())->setAsk(1.0),
          (new Ticker())->setAsk(2.0),
          (new Ticker())->setAsk(3.0),
          (new Ticker())->setAsk(4.0),
          (new Ticker())->setBid(1.0),
          false,
        ],
      ];
    }
}
