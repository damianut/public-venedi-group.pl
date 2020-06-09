<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Services\Upload\Forms;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Execute actions on data from new inputs created by user.
 */
class NewInputs
{
  /**
   * Set reference to owning side in OneToMany Doctrine relationship in entity.
   * 
   * Get class name of owning side without namespace to set reference to
   * owning side.
   * Then use this class name for each element of array.
   * 
   * @param ArrayCollection $entities
   */
  public function setOwningSide(ArrayCollection $entities, $owner): ArrayCollection
  {
    $cname = \get_class($owner);
    $cnameArr = explode('\\', $cname);
    $ucCname = end($cnameArr);
    $entity = $entities->current();
    $entity->{'set'.$ucCname}($owner);
    while ($entity = $entities->next()) {
      $entity->{'set'.$ucCname}($owner);
    }
    
    return $entities;
  }
}
/*............................................................................*/