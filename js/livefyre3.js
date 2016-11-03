(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {
      var networkConfig = {
        network: drupalSettings.livefyre.network
      };
      var convConfig = {
        siteId: drupalSettings.livefyre.siteId,
        articleId: drupalSettings.articleId,
        el: 'livefyre-comments',
        collectionMeta: drupalSettings.livefyre.collectionMeta,
        checksum: drupalSettings.livefyre.checksum
      };

      Livefyre.require(['fyre.conv#3', 'auth'], function(Conv, auth) {
        new Conv(networkConfig, [convConfig], function(commentsWidget) {});
        auth.delegate({
          login: function (callback) {
            // callback(null,{livefyre:'<userauthtoken>'});
          },
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);


