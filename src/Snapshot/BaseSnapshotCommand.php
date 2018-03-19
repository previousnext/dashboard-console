<?php

namespace PNX\Dashboard\Snapshot;

use GuzzleHttp\Exception\GuzzleException;
use PNX\Dashboard\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base dashboard command.
 */
abstract class BaseSnapshotCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->addOption('alert-level', 'l', InputArgument::OPTIONAL, "Filter by the alert level.");
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $alert_level = $input->getOption('alert-level');
    if (isset($alert_level)) {
      $options['query']['alert_level'] = $alert_level;
    }
    parent::execute($input, $output);
  }

}
