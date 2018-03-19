<?php

namespace PNX\Dashboard;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base dashboard command.
 */
abstract class BaseCommand extends Command {

  /**
   * The Guzzle HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * SnapshotsCommand constructor.
   *
   * @param \GuzzleHttp\Client $client
   *   The Guzzle HTTP client.
   */
  public function __construct(Client $client) {
    parent::__construct();
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->addOption('base-url', 'u', InputArgument::OPTIONAL, "The base url of the Dashboard API", "https://status.previousnext.com.au")
      ->addOption('username', NULL, InputArgument::OPTIONAL, "The Dashboard API username.", "admin")
      ->addOption('password', 'p', InputArgument::OPTIONAL, "The Dashboard API password.");
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

    try {
      $this->doExecute($input, $output, $options);
    }
    catch (GuzzleException $e) {
      $output->writeln("Error communicating with Dashboard API. " . $e->getMessage());
    }

  }

  /**
   * Executes the current command.
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
