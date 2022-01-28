<?php

namespace Drupal\digitalconvergence_4xx\Controller;

use Drupal\system\Controller\Http4xxController as SystemHttp4xxController;

/**
 * Controller for default HTTP 4xx responses.
 */
class Http4xxController extends SystemHttp4xxController {

  /**
   * {@inheritdoc}
   */
  public function on4xx() {
    return [
      '#theme' => 'digitalconvergence_4xx',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function on403() {
    return [
      '#theme' => 'digitalconvergence_403',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function on404() {
    $config = \Drupal::configFactory()->getEditable('system.site');
    return [
      '#theme' => 'digitalconvergence_404',
      '#page_404_title' => [
        '#type' => 'html_tag',
        '#tag' => 'h1',
        '#value' => $config->get('page.404_title'),
      ],
      '#page_404_body' => [
        '#markup' => $config->get('page.404_body'),
      ],
    ];
  }

}
