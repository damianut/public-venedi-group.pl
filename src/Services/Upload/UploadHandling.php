<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Services\Upload;

use App\Entity\{News, Photo, PhotosAlbum, Video, VideosAlbum};
use App\Form\Type\Upload\{NewsUploaderType, PhotosAlbumUploaderType, VideosAlbumUploaderType};
use App\Services\CustomResponse;
use App\Services\Upload\Files\FilesHandling;
use App\Services\Upload\Forms\NewInputs;
use App\Services\Upload\Messages\FlashMessages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Form\{Form, FormFactoryInterface};
use Twig\Environment as Twig;

/**
 * Handling uploading of photos's album, videos's album and news.
 */
class UploadHandling
{
  /**
   * Service for preparing custom response on request
   * 
   * @var CustomResponse
   */
  private $responser;
   
  /**
   * Just Entity Manager.
   * 
   * @var EntityManagerInterface
   */
  private $entityManager;
  
  /**
   * Uploading or removing files on server.
   * 
   * @var FilesHandling
   */
  private $filer;
   
  /**
   * Service for creating flash messages.
   * 
   * @var FlashMessages
   */
  private $flasher;
  
  /**
   * Form Factory.
   * 
   * @var FormFactoryInterface
   */
  private $formFactory;
  
  /**
   * Service for append entities to collection
   * 
   * @var NewInputs
   */
  private $inputer;
  
  /**
   * Logger
   * 
   * @var LoggerInterface
   */
  private $logger;
  
  /**
   * Bag with parameters from 'services.yaml'
   * 
   * @var ParameterBagInterface
   */
  private $messages;
  
  /**
   * Part of Twig for rendering page
   * 
   * @var Twig
   */
  private $twig;
  
  /**
   * @param CustomResponse         $responser
   * @param EntityManagerInterface $entityManager
   * @param FilesHandling          $filer
   * @param FlashMessages          $flasher
   * @param FormFactoryInterface   $formFactory
   * @param NewInputs              $inputer
   * @param LoggerInterface        $logger
   * @param ParameterBagInterface  $messages
   * @param Twig                   $twig
   */
  public function __construct(
    CustomResponse $responser,
    EntityManagerInterface $entityManager,
    FilesHandling $filer,
    FlashMessages $flasher,
    FormFactoryInterface $formFactory,
    NewInputs $inputer,
    LoggerInterface $logger,
    ParameterBagInterface $messages,
    Twig $twig
  )
  {
    $this->responser = $responser;
    $this->entityManager = $entityManager;
    $this->filer = $filer;
    $this->flasher = $flasher;
    $this->formFactory = $formFactory;
    $this->inputer = $inputer;
    $this->logger = $logger;
    $this->messages = $messages;
    $this->twig = $twig;
  }

  /**
   * Handling request for uploading.
   * 
   *  Glossary:
   *    Client    person, who bought my code and use "upload" route
   *              for sending news, photos and video to his site
   *    User      person, who browsing "upload" route
   *    Admin     Damian Orzeszek
   * 
   * Create new instances of following forms: News, PhotosAlbum and VideosAlbum.
   * Prepare forms to handle request (done by submitting forms).
   * If form is submitted and valid:
   * -check that Client pass valid password
   * -set publishing datetime and publishing status in form 
   * -optionally save photos and videos na server
   * -save entities in database
   * -prepare message about successful action
   * 
   * At the end send view of form to Twig template.
   *
   * @param  Request  $request Current request
   * 
   * @return Response          Response with rendered template
   */
  public function upload(Request $request): Response
  {
    do {
      /**
       * Creating PhotosAlbum and VideosAlbum.
       */
      $photosAlbum = new PhotosAlbum();
      $photo1 = new Photo();
      $photosAlbum->addPhoto($photo1);
      
      $videosAlbum = new VideosAlbum();
      $video1 = new Video();
      $videosAlbum->addVideo($video1);

      /**
       * Creating News.
       */
      $news = new News();
      $photosAlbum2 = new PhotosAlbum();
      $photo2 = new Photo();
      $photosAlbum2->addPhoto($photo2);
      $videosAlbum2 = new VideosAlbum();
      $video2 = new Video();
      $videosAlbum2->addVideo($video2);

      $news->setPhotosAlbum($photosAlbum2);
      $news->setVideosAlbum($videosAlbum2);
      
      /**
       * Creating forms.
       */
      $formNews = $this->formFactory->create(NewsUploaderType::class, $news);
      $formPhotosAlbum = $this->formFactory->create(
          PhotosAlbumUploaderType::class,
          $photosAlbum
      );
      
      $formVideosAlbum = $this->formFactory->create(
          VideosAlbumUploaderType::class,
          $videosAlbum
      );
      
      $formNews->handleRequest($request);
      $formPhotosAlbum->handleRequest($request);
      $formVideosAlbum->handleRequest($request);
      
      /**
       * Handling upload request
       */
      if ($formNews->isSubmitted() && $formNews->isValid()) {
        if ($_ENV['CLIENT_PASS'] != $formNews['password']->getData()) {
          $this->flasher->add('app.pwd.incorrect');
          break;
        }
        $uplNews = $formNews->getData();
        $uplNews->setUploaded(new \DateTime());
        $uplNews->setPublished(true);
        $this->albumSaving($formNews['photosAlbum']);
        $this->albumSaving($formNews['videosAlbum']);
        $this->entityManager->persist($uplNews);
        $this->entityManager->flush();
        $this->flasher->add('app.news.saved');
      }
      elseif ($formPhotosAlbum->isSubmitted() && $formPhotosAlbum->isValid()) {
        if ($_ENV['CLIENT_PASS'] != $formPhotosAlbum['password']->getData()) {
          $this->flasher->add('app.pwd.incorrect');
          break;
        }
        $this->albumSaving($formPhotosAlbum);
        $this->entityManager->flush();
        $this->flasher->add('app.album.saved');
      }
      elseif ($formVideosAlbum->isSubmitted() && $formVideosAlbum->isValid()) {
        if ($_ENV['CLIENT_PASS'] != $formVideosAlbum['password']->getData()) {
          $this->flasher->add('app.pwd.incorrect');
          break;
        }
        $this->albumSaving($formVideosAlbum);
        $this->entityManager->flush();
        $this->flasher->add('app.album.saved');
      }
    } while (false);
    
    /**
     * Render template
     */
    $renderedTemplate = $this->twig->render('upload/index.html.twig', [
        'formNews' => $formNews->createView(),
        'formPhotosAlbum' => $formPhotosAlbum->createView(),
        'formVideosAlbum' => $formVideosAlbum->createView(),
    ]);
    
    return $this->responser->prepareResponse($renderedTemplate);
  }
  
  /**
   * Handling of album's saving.
   * 
   * @param  Form $form   Submitted form
   */
  private function albumSaving(Form $form): void
  {
    $album = $form->getData();
    $albumCname = \get_class($album);
    $albumCnameArr = explode("\\", $albumCname);
    $ucAlbumCname = end($albumCnameArr);
    /**
     * This method (i.e. albumSaving()) is done for forms to creating following
     * entities:  'PhotosAlbum' or 'VideosAlbum'.
     * Below substring contains name of one from these two entities
     * without word 'Album' (which has 5 letters).
     */
    $ucUnitsName = substr($ucAlbumCname, 0, -5);
    $lcUnitsName = lcfirst($ucUnitsName);
    $ucUnitName = substr($ucUnitsName, 0, -1);
    $album->setUploaded(new \DateTime());
    $album->setPublished(true);
    $units = $album->{'get'.$ucUnitsName}();
    foreach ($units as $unit) {
      if (!$unit->getDir()) {
        $album->{'remove'.$ucUnitName}($unit);
      }
    }
    $units = $album->{'get'.$ucUnitsName}();
    foreach ($units as $key => $unit) {
      /**
       * Save photo or video on server.
       */
      $uplFile = $form[$lcUnitsName][$key]['dir']->getData();
      $uplDir = $this->filer->saveFile($uplFile);
      if ('none' === $uplDir) {
        $album->{'remove'.$ucUnitName}($unit);
      } else {
        $unit->setDir($uplDir);
        $unit->{'set'.$ucAlbumCname}($album);
        $this->entityManager->persist($unit);
      }
    }
    $this->entityManager->persist($album); 
  }
}
/*............................................................................*/