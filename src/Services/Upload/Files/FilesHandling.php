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

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Upload or delete files under '/public/upload' dir and subdirs
 */
class FilesHandling
{
  /**
   * Utilities for managing file system.
   * 
   * @var Filesystem
   */
  private $filesystem;
  
  /**
   * Bag from $_SESSION with flash params
   * 
   * @var FlashBagInterface
   */
  private $flashBag;
  
  /**
   * Logger.
   * 
   * @var LoggerInterface
   */
  private $logger;
  
  /**
   * Parameter Bag with params from `config/services.yaml`.
   * 
   * @var ParameterBagInterface
   */
  private $params;
  
  /**
   * @param Filesystem            $filesystem
   * @param FlashBagInterface     $flashBag
   * @param LoggerInterface       $logger
   * @param ParameterBagInterface $params
   */
  public function __construct(
      Filesystem $filesystem,
      FlashBagInterface $flashBag,
      LoggerInterface $logger,
      ParameterBagInterface $params
  )
  {
    $this->filesystem = $filesystem;
    $this->flashBag = $flashBag;
    $this->logger = $logger;
    $this->params = $params;
  }
  
  /**
   * Try to save file.
   * 
   * @param  UploadedFile $file   File to save
   * 
   * @return string       $status Concatenation of uploading's directory in 'public' folder
   *                              and filename if saving succeeded, failure message otherwise
   */
  public function saveFile(UploadedFile $file): string
  {
    $originalFilename = pathinfo(
        $file->getClientOriginalName(),
        PATHINFO_FILENAME
    );
    $safeFilename = preg_replace("/[^A-Za-z0-9_]/", '', $originalFilename);
    $newFilename =
        $safeFilename.
        '-'.
        uniqid().
        '.'.
        $file->guessExtension();
    $mimeType = $file->getMimeType();
    $uplDir = $this->getUploadDir($mimeType);
    try {
      $file->move($uplDir, $newFilename);
    } catch (FileException $e) {      
      $this->logger->error($e->__toString());
      $message = $this->params->get('app.upl.fail');
      $this->flashBag->add('uplError', $message);
      $status = 'none';
    }
    
    return $status ?? $this->relativePath($uplDir).'/'.$newFilename;
  }
  
  /**
   * Remove PDF File
   * 
   * @param  entity         $entity   Owner of file
   * 
   * @return bool         $status True if saving succeeded, false otherwise
   */
  public function removePDFFile(entity $entity): bool
  {
    $fileFullDir =
        $this->params->get('app.pdf.dir'). 
        '/'. 
        $entity->getCVFilename();
    try {
      $this->filesystem->remove([$fileFullDir]);
      $flash = $this->params->get('app.remove.pdf');
      $this->flashBag->add('notice', $flash);
      $status = true;
    } catch (IOException $e) {
      $message = $this->params->get('app.remove.pdf.fail');
      $this->flashBag->add('error', $message);
      $this->logger->error($e->__toString());
      $status = false;
    }
    if ($status) {
      $entity->setCVFilename(NULL);
    }
    
    return $status; 
  }
  
  /**
   * Determine where to uploading file
   * 
   * @param string  $mimeType Mime type of file
   * 
   * @return string           Dir where file should be uploaded 
   */
  private function getUploadDir(string $mimeType): string
  {
    $kindOfFile = explode('/', $mimeType);
    if ('image' === $kindOfFile[0]) {
      $paramName = 'app.img.dir';
    } elseif ('video' === $kindOfFile[0]) {
      $paramName = 'app.video.dir';       
    } else {
      $paramName = 'app.upl.dir';
    }
    
    return $this->params->get($paramName);
  }
  
  /**
   * Determine relative path to file from 'public' folder.
   * 
   * @param  string $absPath Absolute path
   * 
   * @return string $relPath Relative path
   */
  public function relativePath(string $absPath): string
  {
    $pos = \strrpos($absPath, "public");
    
    return substr($absPath, $pos + 7);
  }
}
/*............................................................................*/