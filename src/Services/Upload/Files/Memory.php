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
 * Checking memory size of Client directory
 */
class Memory
{
  /**
   * Function for recursively summing the sizes of all files in Client directory
   * 
   * @param  string $dir Client directory
   * 
   * @return int    $mem Memory used by Client
   */
  private function check_memory(string $dir): int
  {
    $scaned = scandir($dir);
    $mem = 0;
    for ($i = 2; $i < count($scaned); $i++) {
      if (is_dir($dir.$scaned[$i])) {
        $mem = check_memory($dir.$scaned[$i]."\/", $mem);
      } elseif (is_file($dir.$scaned[$i])) {
        $mem +=filesize($dir.$scaned[$i]);
      }
    }
    
    return $mem;
  }

  /**
   * Invoke above function and check that new data from Client will be exceed memory limit if loaded
   * 
   * @param  int $clientDataSize Size of data, that Client want to upload
   * 
   * @return int Size of free space
   */
  public function is_free_space(int $clientDataSize): int
  {
    $usedMem = $this->check_memory($_ENV['PUBLIC_DIR']) + $clientDataSize;
    
    return $_ENV['TOTAL_MEM'] - $usedMem;
  }
}
/*............................................................................*/