<?php

namespace Drupal\livefyre;

use Drupal\Core\Config\ConfigFactory;
use Livefyre\Livefyre;

/**
 * @todo find a better classname.
 */
class LivefyreCommentsHelper {

  /**
   * The config of the livefyre module.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Creates a new LivefyreNodeView instance.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->config = $configFactory->get('livefyre.settings');
  }

  protected function getNetworkAndSite() {
    $network = $this->config->get('network');
    $network_key = $this->config->get('network_key');

    $site_id = $this->config->get('site_id');
    $site_key = $this->config->get('site_key');

    $network = Livefyre::getNetwork($network, strlen($network_key) > 0 ? $network_key : null);
    $site = $network->getSite($site_id, $site_key);

    return [$network, $site];
  }

  /**
   * Calculates the comments checksum.
   *
   * @param string $title
   * @param string $articleId
   * @param string $url
   *
   * @return string
   *   The checksum for this title/article.
   */
  public function buildChecksum($title, $articleId, $url) {
    /** @var \Livefyre\Core\Network $network */
    /** @var \Livefyre\Core\Site $site */
    list($network, $site) = $this->getNetworkAndSite();

    $collection = $site->buildCommentsCollection($title, $articleId, $url);
    $checksum = $collection->buildChecksum();

    return $checksum;
  }


  /**
   * Calculates the comments meta.
   *
   * @param string $title
   * @param string $articleId
   * @param string $url
   *
   * @return string
   *   The collection meta for this title/article.
   */
  public function buildCollectionMeta($title, $articleId, $url) {
    /** @var \Livefyre\Core\Network $network */
    /** @var \Livefyre\Core\Site $site */
    list($network, $site) = $this->getNetworkAndSite();

    $collection = $site->buildCommentsCollection($title, $articleId, $url);

    return $collection->buildCollectionMetaToken();
  }

}
