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
      Livefyre.require(['fyre.conv#uat', 'auth', 'identity#1'], function(Conv, auth, Identity) {
        new Conv(networkConfig, [convConfig], function(commentsWidget) {});
        if (drupalSettings.livefyre.enterprise.enable && drupalSettings.livefyre.enterprise.fyre_authentication) {
          var identity = new Identity({
            app: drupalSettings.livefyre.enterprise.fyre_authentication_url
          });
          auth.delegate(identity);
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

