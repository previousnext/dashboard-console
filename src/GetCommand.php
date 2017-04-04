<?php

namespace PNX\Dashboard;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides a command for querying a snapshot detail.
 */
class GetCommand extends BaseDashboardCommand {

  /**
   * The maximum length of the description field.
   */
  const MAX_LENGTH = 45;

  /**
   * {@inheritdoc}
   */
  protected function doConfigure() {
    $this->setName('get')
      ->setDescription("Get snapshot data.")
      ->addArgument('site-id', InputArgument::REQUIRED | InputArgument::IS_ARRAY, "The site ID.");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {

    $site_ids = $input->getArgument('site-id');

    foreach ($site_ids as $site_id) {
      try {
        $response = $this->client->get('snapshots/' . $site_id, $options);
      }
      catch (\Exception $e) {
        $output->writeln(sprintf('<error>Unable to retrieve %s</error>', $site_id));
        continue;
      }

      if ($response->getStatusCode() != 200) {
        $output->writeln("Error calling dashboard API");
      }
      else {

        $json = $response->getBody();
        $snapshot = json_decode($json, TRUE);

        $table = new Table($output);
        $table->addRow([
          'Timestamp:',
          $this->formatTimestamp($snapshot['timestamp']),
        ]);
        $table->addRow(['Client ID:', $snapshot['client_id']]);
        $table->addRow(['Site ID:', $snapshot['site_id']]);

        $table->setStyle('compact');
        $table->render();

        $checks = $snapshot['checks'];

        $table = new Table($output);
        $table
          ->setHeaders([
            'Type',
            'Name',
            'Description',
            'Alert Level',
          ]);

        foreach ($checks as $check) {
          $table->addRow([
            $check['type'],
            $check['name'],
            $this->truncate($check['description']),
            $this->formatAlert($check['alert_level']),
          ]);
        }

        $table->setStyle('borderless');
        $table->render();
      }
    }

  }

  /**
   * Truncate a string if it exceeds a the default length.
   *
   * @param string $value
   *   The value to truncate.
   *
   * @return string
   *   The truncated string.
   */
  protected function truncate($value) {
    return strlen($value) > self::MAX_LENGTH ? substr($value, 0, self::MAX_LENGTH) . "…" : $value;
  }

  /**
   * Apply output formatting for alerts.
   *
   * @param string $alert
   *   The alert.
   *
   * @return string
   *   The formatted output for the alert.
   */
  protected function formatAlert($alert) {
    switch ($alert) {
      case 'error':
        return "<error>$alert</error>";

      case 'warning':
        return "<comment>$alert</comment>";

      default:
        return "<info>$alert</info>";
    }
  }

}
