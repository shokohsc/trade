<?php

namespace AppBundle\Entity;

/**
 * Ticker
 */
class Ticker
{
  /**
   * Id $id
   * @var string
   */
  private $id;

  /**
   * Ask $ask
   * @var float
   */
  private $ask;

  /**
   * Bid $bid
   * @var float
   */
  private $bid;

  /**
   * Set id $id
   * @param  string $id
   * @return Ticker
   */
  public function setId(string $id) :Ticker
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get id $id
   * @return string
   */
  public function getId() :string
  {
    return $this->id;
  }

  /**
   * Set ask $ask
   * @param  float $ask
   * @return Ticker
   */
  public function setAsk(float $ask) :Ticker
  {
    $this->ask = $ask;

    return $this;
  }

  /**
   * Get ask $ask
   * @return float
   */
  public function getAsk() :float
  {
    return $this->ask;
  }

  /**
   * Set bid $bid
   * @param  float $bid
   * @return Ticker
   */
  public function setBid(float $bid) :Ticker
  {
    $this->bid = $bid;

    return $this;
  }

  /**
   * Get bid $bid
   * @return float
   */
  public function getBid() :float
  {
    return $this->bid;
  }
}
