<?php

namespace AppBundle\Tests\Factory;

use AppBundle\Entity\Asset;
use AppBundle\Factory\AssetFactory;

/**
 * Class AssetFactoryTest
 */
class AssetFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AssetFactory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new AssetFactory();
    }

    /**
     * @dataProvider assetsProvider
     */
    public function testBuild(array $asset, Asset $expected)
    {
      $result = $this->factory->build($asset);
      $this->assertEquals($expected, $result);
      $this->assertEquals(key($asset), $result->getId());
      $this->assertEquals($asset[key($asset)]['altname'], $result->getAltName());
    }

    public function assetsProvider()
    {
      return [
        [
          [
            'some_asset' => [
              'altname' => 'some_altname',
            ],
          ],
          (new Asset())->setId('some_asset')->setAltName('some_altname')
        ],
        [
          [
            'XETH' => [
              'altname' => 'ETH',
            ],
          ],
          (new Asset())->setId('XETH')->setAltName('ETH')
        ],
      ];
    }
}
