<?php

namespace Drupal\livefyre\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\livefyre\LivefyreCommentsHelper;
use Drupal\livefyre\LivefyreNodeView;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block for switching users.
 *
 * @Block(
 *   id = "livefyre_comments",
 *   admin_label = @Translation("Livefyre Comments"),
 *   context = {
 *     "node" = @ContextDefinition("entity:node")
 *   }
 * )
 */
class LivefyreComments extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The comments helper.
   *
   * @var \Drupal\livefyre\LivefyreCommentsHelper
   */
  protected $commentsHelper;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, LivefyreCommentsHelper $comments_helper) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configFactory = $config_factory;
    $this->commentsHelper = $comments_helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('livefyre.comments_helper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (($node = $this->getContextValue('node')) && $node instanceof NodeInterface) {
      $node_view = new LivefyreNodeView($this->configFactory, $this->commentsHelper);
      return  $node_view->view($node, 'full');
    }
  }

}
