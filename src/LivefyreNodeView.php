<?php

namespace Drupal\livefyre;

use Drupal\Core\Config\Config;
use Drupal\node\NodeInterface;

class LivefyreNodeView {

  protected function isEnabled(Config $config) {
    return !$config->get('disabled') && !empty($config->get('acct_num'));
  }

  protected function version2Output() {
    // @todo Should we still support version 2?
  }

  /**
   *
   * @param \Drupal\node\NodeInterface $node
   * @param $view_mode
   * @param \Drupal\Core\Config\Config $config
   *
   * @return array
   */
  protected function version3Output(NodeInterface $node, $view_mode, Config $config) {
    $livefyre_div = '<div id="livefyre-comments"></div>';
    $livefyre_account_num = $config->get('acct_num');
    $livefyre_parent_div = $config->get('parent_div');
    $livefyre_parent_div_html = '<div id="' . $livefyre_parent_div . '">';
    $livefyre_node_types = $config->get('node_types');

    $build = [];
    if (!empty($livefyre_node_types[$node->bundle()])
      && ($view_mode == 'full')
      && ($config->get('location') == 'content_area')) {

      $build['livefyre'] = [
        '#attached' => [
          'library' => [
            'livefyre/livefyre-3',
          ],
          'drupalSettings' => [
            'livefyre' => [
              'account_num' => $livefyre_account_num,
            ]
          ]
        ],
        '#weight' => $config->get('weight'),
      ];
      if (empty($livefyre_parent_div)) {
        $output = $livefyre_div;
      }
      else {
        $output = $livefyre_parent_div_html . $livefyre_div;
      }
      $build['livefyre']['#markup'] = $output;
    }
    return $build;
  }

  /**
   * Embeds the lifefyre configuration.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The current rendered node.
   * @param string $view_mode
   *   The view mode.
   *
   * @return array
   *   A render array containing the rendered lifefyre.
   */
  public function view(NodeInterface $node, $view_mode) {
    $config = \Drupal::configFactory()->get('livefyre.settings');

    if (!$this->isEnabled($config)) {
      return [];
    }

    $livefyre_supplied_js = $config->get('supplied_js');

    if ($livefyre_supplied_js !== 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js'){
      return $this->version2Output($node, $view_mode, $config);
      //version 3 stuff
    } elseif ($livefyre_supplied_js == 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js') {
      return $this->version3Output($node, $view_mode, $config);
    }
    return [];
  }

}
