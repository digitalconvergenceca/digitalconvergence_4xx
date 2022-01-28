<?php

namespace Drupal\digitalconvergence_4xx\EventSubscriber;

use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a subscriber for HTTP response codes.
 */
class HttpEventSubscriber extends HttpExceptionSubscriberBase {

  /**
   * The current user.
   *
   * @var Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructor for HttpEventSubscriber.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Specifies the request formats this subscriber will respond to.
   */
  protected function getHandledFormats() {
    return ['html'];
  }

  /**
   * For anonymous users, throw a 404 instead of a 403 error.
   */
  public function on403(ExceptionEvent $event) {
    $config = \Drupal::configFactory()->getEditable('system.site');
    if ($config->get('page.403_to_404_anonymous') == 1 && $this->currentUser->isAnonymous()) {
      $event->setThrowable(new NotFoundHttpException());
    }
  }

}
