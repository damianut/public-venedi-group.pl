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
 * Class for data removing
 */
class DataRemoving
{
  /**
   * Finally function to remove data
   * 
   * @param  string $type Type of removed data
   * 
   * @return bool   $status True if removing was succeeded, false otherwise
   */
  public function remove_data(string $type): bool
  {
    if ($type === "news") {   
        hub("accordion", '/html/news.html', 'articles/', 'article_');
    } elseif ($type === "photos") {
        hub("albums_get", '/html/photos.html', 'albums/', 'album_');
    } elseif ($type === "video") {   
        hub("films_get", '/html/films.html', 'videos/', 'video_');
    } else {
        $status = false;
    }
    
    return $status ?? true;
  }
    
  /**
   * Find and save in array all directories of files destined to remove
   * 
   * @param string $dir            Directory with files and directories to remove
   * @param array  $files_and_dirs Files and directories to remove
   * 
   * @return array $files_and_dirs Recursively found directories and files are added here
   */
  private function check_files(string $dir, array $files_and_dirs): array
  {
    $scaned = scandir($dir);
    for ($i = 2; $i < count($scaned); $i++) {
      if (is_dir($dir.$scaned[$i])) {   
        $files_and_dirs[1][count($files_and_dirs[1])] = $dir.$scaned[$i];
        $files_and_dirs = check_files($dir.$scaned[$i]."/", $files_and_dirs);
      } elseif (is_file($dir.$scaned[$i])) {
        $files_and_dirs[0][count($files_and_dirs[0])] = $dir.$scaned[$i];
      }
    }
    
    return $files_and_dirs;
  }
/*............................................................................*/
  /**
   * Remove files under found directories
   * 
   * 
   */
  private function rm_data_real($id, $main_dir, $sub_dir){
      $public_dir = '/var/www/venedi-group.pl/public/';
      $scaned = scandir($public_dir . $main_dir);
      $counted = count($scaned);
      for($i = 2; $i < $counted; $i++) {
          if ($scaned[$i] === $sub_dir . $id) {
              $dirs = check_files($public_dir .  $main_dir . $scaned[$i] . '/', [[],[]]);
              $i = $counted;
          }
      }
      if (!isset($dirs)) {
          header('Location: ../?p=upload&m=Błąd serwera. Skontaktuj się z adminem i wyślij mu dane, które chciałeś umieścić na stronie.');
          exit;
      }
      $ftp_connect = log_in();
      for($i = 0; $i < count($dirs[0]); $i++) {

          /**
           * Getting relative path of file by substr function
           */
          $temp_str = substr($dirs[0][$i], 32);
          ftp_delete($ftp_connect, $temp_str);
      }

      for($i = count($dirs[1]) - 1; $i >= 0; $i--) {

          /**
           * Getting relative path of file by substr function
           */
          $temp_str = substr($dirs[1][$i], 32);
          ftp_rmdir($ftp_connect, $temp_str);
      }
      ftp_rmdir($ftp_connect, $main_dir . $sub_dir . $id . '/');
      ftp_close($ftp_connect);
  }

  /**
   * Function with all functions needed to remove chosen data from "news.html" || "photos.html" || "films.html"
   */
  function hub($container, $reduce, $main_dir, $sub_dir){
      $data = get_id($container, $reduce, 'lastChild', true);
      $DOM_main = just_maintenance($data[1]);
      $DOM_main = newsstr_pretty_print($DOM_main);
      save_file_once($reduce, $DOM_main);
      rm_data_real($data[0], $main_dir, $sub_dir);
  }
}
/*............................................................................*/