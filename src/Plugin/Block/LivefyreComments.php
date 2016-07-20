<?php

namespace Drupal\livefyre\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block for switching users.
 *
 * @Block(
 *   id = "livefyre_comments",
 *   admin_label = @Translation("Livefyre Comments"),
 * )
 */
class LivefyreComments extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::configFactory()->get('livefyre.settings');
    // @todo reimplement this feature.
  }

}
