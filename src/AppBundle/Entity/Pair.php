<?php

namespace AppBundle\Entity;

/**
 * Pair
 */
class Pair
{
  /**
   * Id $id
   * @var string
   */
  private $id;

  /**
   * Alt name $altName
   * @var string
   */
  private $altName;

  /**
   * Base $base
   * @var string
   */
  private $base;

  /**
   * Quote $quote
   * @var string
   */
  private $quote;

  /**
   * Get name $id
   * @return string
   */
  public function getId() :string
  {
    return $this->id;
  }

  /**
   * Set name $id
   * @param  string $id
   * @return Pair
   */
  public function setId(string $id) :Pair
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get altName $altName
   * @return string
   */
  public function getAltName() :string
  {
    return $this->altName;
  }

  /**
   * Set altName $altName
   * @param  string $altName
   * @return Pair
   */
  public function setAltName(string $altName) :Pair
  {
    $this->altName = $altName;

    return $this;
  }

  /**
   * Get base $base
   * @return string
   */
  public function getBase() :string
  {
    return $this->base;
  }

  /**
   * Set base $base
   * @param  string $base
   * @return Pair
   */
  public function setBase(string $base) :Pair
  {
    $this->base = $base;

    return $this;
  }

  /**
   * Get quote $quote
   * @return string
   */
  public function getQuote() :string
  {
    return $this->quote;
  }

  /**
   * Set quote $quote
   * @param  string $quote
   * @return Pair
   */
  public function setQuote(string $quote) :Pair
  {
    $this->quote = $quote;

    return $this;
  }

  /**
   * Get base and quote as array
   * @return array
   */
  public function getPair(): array
  {
     return [
       $this->base,
       $this->quote,
     ];
  }
}
