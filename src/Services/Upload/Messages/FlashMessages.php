<?php
declare(strict_types=1);
/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Services\Upload\Messages;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Create flash from messages defined in 'config/services.yaml'
 */
class FlashMessages
{
  /**
   * Bag from $_SESSION with flash messages
   * 
   * @var FlashBagInterface
   */
  private $flashBag;
  
  /**
   * Parameter bag with defined messages from 'config/services.yaml'
   * 
   * @var ParameterBagInterface
   */
  private $messages;
  
  /**
   * @param FlashBagInterface $flashBag
   */
  public function __construct(
      ParameterBagInterface $messages,
      RequestStack $requestStack
  )
  {
    $this->flashBag = 
        $requestStack->getCurrentRequest()->getSession()->getFlashBag();
    $this->messages = $messages;  
  }
   
  /**
   * Add flash message with text from 'config/services.yaml'
   * 
   * @param  string $name Name of message from 'config/services.yaml'
   * @param  string $type Type of message
   * 
   * @return void
   */
  public function add(string $name, string $type = 'error'): void
  {
    $this->flashBag->add($type, $this->messages->get($name)."\n");   
  }
}

/*............................................................................*/