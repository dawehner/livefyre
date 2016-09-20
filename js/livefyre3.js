(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {
      fyre.conv.load({}, [{
        el: 'livefyre-comments',
        network: drupalSettings.livefyre.network,
        siteId: drupalSettings.livefyre.siteId,
        articleId: drupalSettings.articleId,
        signed: false,
        collectionMeta: drupalSettings.livefyre.collectionMeta,
        checksum:drupalSettings.livefyre.checksum
      }]);
    }
  };
})(jQuery, Drupal, drupalSettings);

