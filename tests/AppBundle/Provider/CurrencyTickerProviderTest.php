<?php

namespace AppBundle\Tests\Provider;

use AppBundle\Provider\CurrencyTickerProvider;

/**
 * Class ProviderTest
 */
class CurrencyTickerProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurrencyTickerProvider
     */
    private $provider;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->provider = new CurrencyTickerProvider();
    }
}
