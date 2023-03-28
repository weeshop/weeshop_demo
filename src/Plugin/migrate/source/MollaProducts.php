<?php

namespace Drupal\weeshop_demo\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * The 'weeshop_demo_molla_products' source plugin.
 *
 * @MigrateSource(
 *   id = "weeshop_demo_molla_products",
 *   source_module = "weeshop_demo"
 * )
 */
class MollaProducts extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Migrating product data from the molla json file.';
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator(): \Iterator {
    $json = json_decode(file_get_contents(__DIR__ . '/../../../../data/demo1.json'), TRUE);
    $records = [];
    foreach ($json['products'] as $product) {
      $main_image = $product['pictures'][0]['url'];
      $records[] = [
        'id' => 'product' . $product['id'],
        'title' => $product['name'],
      ];
    }
    return new \ArrayIterator($records);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'id' => $this->t('The product ID.'),
      'title' => $this->t('Title of the product.'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'id' => [
        'type' => 'string',
        'max_length' => 64,
        'is_ascii' => TRUE,
      ],
    ];
  }

}
