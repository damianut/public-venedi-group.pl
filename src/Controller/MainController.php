<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Controller;

use App\Services\Display\DisplaySubpage;
use App\Services\Upload\UploadHandling;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
  /**
   * @Route("/", name="news")
   */
  public function news(Request $request, DisplaySubpage $displayer)
  {
    return $displayer->display($request);
  }
  
  /**
   * @Route("/who_we_are", name="who_we_are")
   */
  public function whoWeAre()
  {
    return $this->render('who_we_are/index.html.twig');
  }

  /**
   * @Route("/what_we_do", name="what_we_do")
   */
  public function whatWeDo()
  {
    return $this->render('what_we_do/index.html.twig'); 
  }
  
  /**
   * @Route("/contact", name="contact")
   */
  public function contact()
  {
    return $this->render('contact/index.html.twig');
  }
  
  /**
   * @Route("/films", name="videosAlbum")
   */
  public function videosAlbum(Request $request, DisplaySubpage $displayer)
  {
    return $displayer->display($request);
  }
  
  /**
   * @Route("/partnership", name="partnership")
   */
  public function partnership()
  {
    return $this->render('partnership/index.html.twig');
  }
  
  /**
   * @Route("/photos", name="photosAlbum")
   */
  public function photosAlbum(Request $request, DisplaySubpage $displayer)
  {
    return $displayer->display($request);
  }
  
  /**
   * @Route("/upload", name="upload")
   */
  public function upload(Request $request, UploadHandling $uploadHandling)
  {
    return $uploadHandling->upload($request); 
  }
}
/*............................................................................*/