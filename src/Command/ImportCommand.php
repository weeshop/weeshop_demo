<?php

namespace Drupal\weeshop_demo\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Core\Command\Command;

/**
 * Class ImportCommand.
 *
 * Drupal\Console\Annotations\DrupalCommand (
 *     extension="weeshop_demo",
 *     extensionType="module"
 * )
 */
class ImportCommand extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('weeshop_demo:import')
      ->setDescription($this->trans('Import the WeeShop Demo data.'));
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(InputInterface $input, OutputInterface $output) {
    parent::initialize($input, $output);
    $this->getIo()->info('initialize');
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $this->getIo()->info('interact');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->getIo()->info('execute');
    _weeshop_demo_execute_migrations('import');
    $this->getIo()->info($this->trans('commands.weeshop_demo.import.messages.success'));
  }

}
