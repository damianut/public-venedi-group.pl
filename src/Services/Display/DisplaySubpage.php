<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Services\Display;

use App\Entity\{News, PhotosAlbum, VideosAlbum};
use App\Services\CustomResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\{Response, Request};
use Twig\Environment as Twig;

/**
 * Logic from MainController for 'news', 'photos' and 'films' routes
 */
class DisplaySubpage
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
   * Part of Twig for rendering page
   * 
   * @var Twig
   */
  private $twig;
  
  /**
   * @param CustomResponse         $responser
   * @param EntityManagerInterface $entityManager
   * @param Twig                   $twig
   */
  public function __construct(
      CustomResponse $responser,
      EntityManagerInterface $entityManager,
      Twig $twig
  )
  { 
    $this->responser = $responser;
    $this->entityManager = $entityManager;
    $this->twig = $twig;
  }
   
  /**
   * Method to display page with news, photos or videos
   * 
   * @param Request   $request  Current request
   * 
   * @return Response $response Rendered template
   */
  public function display(Request $request): Response
  {
    $routeName = $request->attributes->get('_route');
    $ucRouteName = \ucfirst($routeName);
    $allEntities = $this
        ->entityManager
        ->getRepository('App\\Entity\\'.$ucRouteName)
        ->findAll();
    /**
     * Below plural form of entity name is created.
     */
    $collectionTwigName = strpos($routeName, 's', -1) ? 
        $routeName : $routeName.'s';
    $renderedTemplate =  $this->twig->render($routeName.'/index.html.twig', [
        $collectionTwigName => $allEntities,
    ]);
    
    return $this->responser->prepareResponse($renderedTemplate);
  }
}
/*............................................................................*/