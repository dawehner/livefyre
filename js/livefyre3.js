(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {
      var articleId = fyre.conv.load.makeArticleId(null);
      fyre.conv.load({}, [{
        el: 'livefyre-comments',
        network: 'livefyre.com',
        siteId: drupalSettings.livefyre.account_num,
        articleId: articleId,
        signed: false,
        collectionMeta: {
          articleId: articleId,
          url: fyre.conv.load.makeCollectionUrl()
        }}]
      );
    }
  };
})(jQuery, Drupal, drupalSettings);

