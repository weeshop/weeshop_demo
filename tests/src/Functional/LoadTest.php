<?php

namespace Drupal\Tests\weeshop_demo\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\UserInterface;

/**
 * Simple test to ensure that main page loads with module enabled.
 *
 * @group weeshop_demo
 */
class LoadTest extends BrowserTestBase {

  /**
   * Default theme running test.
   *
   * @var string
   */
  public $defaultTheme = 'stable';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['weeshop_demo'];

  /**
   * A user with permission to administer site configuration.
   *
   * @var \Drupal\user\UserInterface
   */
  protected UserInterface $user;

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function setUp(): void {
    parent::setUp();
    $this->user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($this->user);
  }

  /**
   * Tests that the home page loads with a 200 response.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->statusCodeEquals(200);
  }

}
