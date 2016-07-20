<?php

namespace Drupal\Tests\livefyre\Functional;

use Drupal\node\Entity\NodeType;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the general livefyre functioanlity.
 *
 * @todo Get a test account from livefyre and leverage a javascript test.
 *
 * @group livefyre
 */
class LivefyreSettingsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['livefyre', 'node', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $config = \Drupal::configFactory()->getEditable('livefyre.settings');
    $config->set('disabled', FALSE);
    $config->set('node_types', ['page' => 1]);
    $config->set('parent_div', 'livefyre');
    $config->set('acct_num', 123);
    $config->set('supplied_js', 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js');
    $config->save();

    $node_type = NodeType::create([
      'type' => 'page',
    ]);
    $node_type->save();
  }

  public function testLiveFyreRendering() {
    $node = $this->createNode([
      'type' => 'page',
    ]);
    $node->save();

    $this->drupalGet($node->toUrl());
    $this->assertSession()->responseContains('<div id="livefyre">');
  }


}
