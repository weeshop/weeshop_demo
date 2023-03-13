<?php

namespace Drupal\weeshop_demo\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class WeeshopDemoCommands extends DrushCommands {

  /**
   * Import the demo data.
   *
   * @param mixed $arg1
   *   Argument description.
   * @param array $options
   *   An associative array of options
   *   whose values come from cli, aliases, config, etc.
   *
   * @usage weeshop_demo-commandName foo
   *   Usage description
   *
   * @command weeshop_demo:import
   * @aliases wdim
   */
  public function import(mixed $arg1, array $options = []): void {
    _weeshop_demo_execute_migrations('import');
    $this->logger()->success(dt('Demo data imported successfully.'));
  }

  /**
   * Rollback the demo data.
   *
   * @param mixed $arg1
   *   Argument description.
   * @param array $options
   *   An associative array of options
   *   whose values come from cli, aliases, config, etc.
   *
   * @usage weeshop_demo:rollback
   *   Usage description
   *
   * @command weeshop_demo:rollback
   * @aliases wdrb
   */
  public function rollback(mixed $arg1, array $options = []): void {
    _weeshop_demo_execute_migrations('rollback');
    $this->logger()->success(dt('Demo data imported successfully.'));
  }

}
