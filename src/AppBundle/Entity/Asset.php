<?php

namespace AppBundle\Entity;

/**
 * Asset
 */
class Asset
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
   * Set id $id
   * @param  string $id
   * @return Asset
   */
  public function setId(string $id) :Asset
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
   * Set name $altName
   * @param  string $altName
   * @return Asset
   */
  public function setAltName(string $altName) :Asset
  {
    $this->altName = $altName;

    return $this;
  }

  /**
   * Get name $altName
   * @return string
   */
  public function getAltName() :string
  {
    return $this->altName;
  }
}
