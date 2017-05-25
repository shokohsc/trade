<?php

namespace AppBundle\Tests\Provider;

use AppBundle\Provider\RandomTickerProvider;

/**
 * Class ProviderTest
 */
class RandomTickerProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RandomTickerProvider
     */
    private $provider;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->provider = new RandomTickerProvider();
    }
}
