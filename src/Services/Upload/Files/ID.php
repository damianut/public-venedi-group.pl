<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Services\Upload\Files;

/**
 * Get ID of the last loaded element (article, album of photos or video) from "news.html" || "photos.html" || "videos.html"
 */
class ID
{
  /**
   * Create unique ID
   */
  function create_id($id, $add){
      $id = substr($id, 1);
      return 'a'. ($add + $id);
  }
  
  /**
   * Load the last created ID and invoke function to create newer ID
   */
  function get_id($which, $dir, $get_set, $remove){
      $DOM_file = load_HTML_file('..' . $dir);
      $DOM_file = just_maintenance($DOM_file);
      $id = $DOM_file->getElementById($which)->$get_set->getAttribute("id");
      if ($remove) {
          $node_rm = $DOM_file->getElementById($which)->lastChild;
          $DOM_file->getElementById($which)->removeChild($node_rm);
          return [create_id($id, 0), $DOM_file];
      } else {
          return create_id($id, 1);
      }
  }
}