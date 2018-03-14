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

    $username = getenv('DASHBOARD_USER') ?: $input->getOption('username');
    $password = getenv('DASHBOARD_PASSWORD') ?: $input->getOption('password');

    $options = [
      'query' => [],
      'base_uri' => $input->getOption('base-url'),
      'auth' => [$username, $password],
    ];

    $alert_level = $input->getOption('alert-level');
    if (isset($alert_level)) {
      $options['query']['alert_level'] = $alert_level;
    }

    try {
      $this->doExecute($input, $output, $options);
    }
    catch (GuzzleException $e) {
      $output->writeln("Error communicating with Dashboard API. " . $e->getMessage());
    }

  }

  /**
   * Formats the timestamp string.
   *
   * If the timestamp is over 24 hours in the past, the returned string is
   * formatted as an error.
   *
   * @param string $timestamp
   *   The timestamp.
   *
   * @return string
   *   The formatted timestamp.
   */
  protected function formatTimestamp($timestamp) {
    $yesterday = time() - (24 * 60 * 60);
    $date      = new \DateTime($timestamp);

    if ($date->getTimestamp() > $yesterday) {
      return $timestamp;
    }
    return "<error>$timestamp</error>";
  }

  /**
   * Executes the current command.
   *
   * This method is not abstract because you can use this class
   * as a concrete class. In this case, instead of defining the
   * execute() method, you set the code to execute by passing
   * a Closure to the setCode() method.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *   An InputInterface instance.
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   *   An OutputInterface instance.
   * @param array $options
   *   An array of http client options.
   *
   * @return null|int
   *   null or 0 if everything went fine, or an error code
   *
   * @see \Symfony\Component\Console\Command\Command::execute
   */
  abstract protected function doExecute(InputInterface $input, OutputInterface $output, array $options);

}
