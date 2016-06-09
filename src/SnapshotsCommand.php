<?php

/**
 * @file
 * Contains PNX\Dashboard\SnapshotsCommand
 */

namespace PNX\Dashboard;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * A command for getting snapshots.
 */
class SnapshotsCommand extends BaseDashboardCommand {

  /**
   * {@inheritdoc}
   */
  protected function doConfigure() {
    $this->setName('snapshots')
      ->setDescription("Query the PNX Dashboard API for snapshot data.")
      ->addOption('client-id', 'c', InputArgument::OPTIONAL, "Filter by the client ID.")
      ->addOption('check-name', NULL, InputArgument::OPTIONAL, "Filter by the check name.")
      ->addOption('check-type', NULL, InputArgument::OPTIONAL, "Filter by the check type.")
      ->addOption('env', 'e', InputArgument::OPTIONAL, "Filter by the env type.")
      ->addOption('csv', NULL, InputOption::VALUE_NONE, "Output the values in csv format, if not selected output will be in table format.");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, $options) {

    $client_id = $input->getOption('client-id') ?: getenv('DASHBOARD_CLIENT_ID');
    if ($client_id) {
      $options['query']['client_id'] = $client_id;
    }

    $name = $input->getOption('check-name');
    if (isset($name)) {
      $options['query']['name'] = $name;
    }

    $type = $input->getOption('check-type');
    if (isset($type)) {
      $options['query']['type'] = $type;
    }

    $env = $input->getOption('env');
    if (isset($env)) {
      $options['query']['env'] = $env;
    }

    $response = $this->client->get('snapshots', $options);

    if ($response->getStatusCode() != 200) {
      $output->writeln("Error calling dashboard API");
    }
    else {
      $json = $response->getBody();
      $sites = json_decode($json, TRUE);
      $csv = $input->getOption('csv');
      if ($csv) {
        $this->formatCSV($output, $sites);
      }
      else {
        $this->formatTable($output, $sites);
      }
    }
  }

  /**
   * Formats the sites into CSV structure. Ideal for scripting.
   *
   * @param OutputInterface $output
   *   Output interface which will get written to.
   * @param array $sites
   *   A list of sites.
   */
  protected function formatCSV(OutputInterface $output, $sites) {
    foreach ($sites as $site) {
      $line = array(
        $site['client_id'],
        $site['site_id'],
        $site['alert_summary']['notice'],
        $site['alert_summary']['warning'],
        $site['alert_summary']['error'],
      );
      $output->writeln(implode(",", $line));
    }
  }

  /**
   * Formats the sites into Table structure.
   *
   * @param OutputInterface $output
   *   Output interface which will get written to.
   * @param array $sites
   *   A list of sites.
   */
  protected function formatTable(OutputInterface $output, $sites) {
    $table = new Table($output);
    $table
      ->setHeaders([
        'Timestamp',
        'Client ID',
        'Site ID',
        'Env',
        'Notice',
        'Warning',
        'Error'
      ]);

    foreach ($sites as $site) {
      $table->addRow([
        $this->formatTimestamp($site['timestamp']),
        $site['client_id'],
        $site['site_id'],
        $site['env'],
        $this->formatAlert('notice', $site['alert_summary']['notice']),
        $this->formatAlert('warning', $site['alert_summary']['warning']),
        $this->formatAlert('error', $site['alert_summary']['error']),
      ]);
    }

    $table->setStyle('borderless');
    $table->render();
  }

  /**
   * Formats the alert level count.
   *
   * @param string $alert_level
   *   The alert level.
   * @param int $count
   *   The alert level count.
   *
   * @return string
   *   The formatted count.
   */
  protected function formatAlert($alert_level, $count) {
    if ($count > 0) {
      switch ($alert_level) {
        case 'error':
          return "<error>$count</error>";

        case 'warning':
          return "<comment>$count</comment>";
      }
    }
    return $count;
  }
}
