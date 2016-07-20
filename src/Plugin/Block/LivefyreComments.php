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

    $livefyre_disabled = variable_get('livefyre_disabled', FALSE);
    $livefyre_location = variable_get('livefyre_location', 'content_area');
    if (!$livefyre_disabled && $livefyre_location == 'block') {
      $livefyre_account_num = variable_get('livefyre_acct_num', '');
      $livefyre_supplied_js = variable_get('livefyre_supplied_js', '');
      $livefyre_custom_script_snippet = variable_get('livefyre_custom_script_snippet', '');
      $livefyre_div = "<div id='livefyre'></div>";
      $livefyre_custom_instantiate_lf = variable_get('livefyre_custom_instantiate_lf', FALSE);
      $livefyre_parent_div = variable_get('livefyre_parent_div', '');
      $livefyre_parent_div_html = '<div id="' . $livefyre_parent_div . '">';
      drupal_add_js(array('livefyre' => array('account_num' => $livefyre_account_num)), array('type' => 'setting', 'scope' => JS_DEFAULT));
      if ($livefyre_supplied_js !== 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js'){
        if (!$livefyre_custom_instantiate_lf) {
          drupal_add_js("var fyre = LF({
          site_id: Drupal.settings.livefyre.account_num
      })", ['type' => 'inline', 'scope' => 'footer']);
        }
        if (empty($livefyre_parent_div)) {
          return [
            'content' => $livefyre_div . '<script type="text/javascript" src="' . $livefyre_supplied_js . '">' . '</script>' .  $livefyre_custom_script_snippet,
          ];
        }
        else {
          return [
            'content' => $livefyre_parent_div_html . $livefyre_div . '</div>' . '<script type="text/javascript" src="' . $livefyre_supplied_js . '">' . '</script>' .  $livefyre_custom_script_snippet,
          ];
        }
      }
      elseif ($livefyre_supplied_js == 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js'){
        $livefyre_div = '<div id="livefyre-comments"></div>';
        if (!$livefyre_custom_instantiate_lf) {
          drupal_add_js("(function () {
              var articleId = fyre.conv.load.makeArticleId(null);
              fyre.conv.load({}, [{
              el: 'livefyre-comments',
              network: 'livefyre.com',
              siteId: Drupal.settings.livefyre.account_num,
              articleId: articleId,
              signed: false,
              collectionMeta: {
                  articleId: articleId,
                  url: fyre.conv.load.makeCollectionUrl(),
                }
              }], function() {});
            }());", ['type' => 'inline', 'scope' => 'footer']);
        }
        if (empty($livefyre_parent_div)) {
          return [
            'content' => $livefyre_div . '<script type="text/javascript" src="' . $livefyre_supplied_js . '">' . '</script>' .  $livefyre_custom_script_snippet,
          ];
        }
        else {
          return [
            'content' => $livefyre_parent_div_html . $livefyre_div . '</div>' . '<script type="text/javascript" src="' . $livefyre_supplied_js . '">' . '</script>' .  $livefyre_custom_script_snippet,
          ];
        }
      }
    }
  }

}
