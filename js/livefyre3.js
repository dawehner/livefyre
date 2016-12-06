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

      Livefyre.require(['fyre.conv#3', 'auth', 'lfep-auth-delegate#0'], function(Conv, auth, LFEPAuthDelegate) {
        if (drupalSettings.livefyre.enterprise.enable && drupalSettings.livefyre.enterprise.fyre_authentication) {
          var authDelegate = new LFEPAuthDelegate({
            engageOpts: {
              app: drupalSettings.livefyre.enterprise.fyre_authentication_url,
            }
          });
          auth.delegate(authDelegate);
        }

        new Conv(networkConfig, [convConfig], function(commentsWidget) {});
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

