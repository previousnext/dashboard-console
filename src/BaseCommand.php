<?php

namespace PNX\Dashboard;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Base dashboard command.
 */
abstract class BaseCommand extends Command {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * SnapshotsCommand constructor.
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

}
