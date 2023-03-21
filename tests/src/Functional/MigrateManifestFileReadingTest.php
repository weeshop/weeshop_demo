<?php

namespace Drupal\Tests\weeshop_demo\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test description.
 *
 * @group weeshop_demo
 */
class MigrateManifestFileReadingTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'weeshop_demo',
  ];

  /**
   * {@inheritdoc}
   */
  protected $profile = 'weeshop';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Tests something.
   */
  public function testSomething() {
    $migrations = _weeshop_demo_get_migration_list_from_manifest_file();
    $this->assertNotEmpty($migrations);
  }

}
