(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {
      var networkConfig = {
        network: drupalSettings.livefyre.network,
      };
      var convConfig = {
        siteId: drupalSettings.livefyre.siteId,
        articleId: drupalSettings.livefyre.articleId,
        el: 'livefyre-comments',
        collectionMeta: drupalSettings.livefyre.collectionMeta,
      };

      Livefyre.require(['fyre.conv#3'], function(Conv) {
        new Conv(networkConfig, [convConfig], function(commentsWidget) {
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

