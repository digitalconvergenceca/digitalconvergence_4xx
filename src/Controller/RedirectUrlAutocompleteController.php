<?php

namespace Drupal\digitalconvergence_4xx\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\path_alias\AliasManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for autocompleting redirect URL fields.
 */
class RedirectUrlAutocompleteController extends ControllerBase {

  /**
   * The path alias manager.
   *
   * @var \Drupal\path_alias\AliasManager
   */
  protected $pathAliasManager;

  /**
   * Constructs a RedirectUrlAutocompleteController object.
   *
   * @param \Drupal\path_alias\AliasManager $path_alias_manager
   *   The path alias manager object.
   */
  public function __construct(AliasManager $path_alias_manager) {
    $this->pathAliasManager = $path_alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('path_alias.manager')
    );
  }

  /**
   * Handler for redirect URL autocomplete request.
   */
  public function autocomplete(Request $request) {
    $matches = [];
    $input = $request->query->get('q');

    if (!$input) {
      return new JsonResponse($matches);
    }

    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple();

    foreach ($nodes as $node) {
      if (str_contains($node->label(), $input)) {
        $path = $this->pathAliasManager->getAliasByPath('/node/' . $node->id());
        $matches[] = [
          'value' => $path,
          'label' => EntityAutocomplete::getEntityLabels([$node]),
        ];
      }
    }

    return new JsonResponse($matches);
  }

}
