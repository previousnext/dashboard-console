<?php

namespace PNX\Dashboard;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides a delete command.
 */
class DeleteCommand extends BaseDashboardCommand {

  /**
   * {@inheritdoc}
   */
  protected function doConfigure() {
    $this->setName('delete')
      ->setDescription("Delete snapshot data.")
      ->addOption('site-id', 's', InputArgument::OPTIONAL, "The site ID.");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $site_ids = explode(',', $input->getOption('site-id'));

    foreach ($site_ids as $site_id) {
      try {
        $response = $this->client->delete('snapshots/' . $site_id, $options);
      }
      catch (\Exception $e) {
        $output->writeln(sprintf('<error>Unable to delete %s</error>', $site_id));
        continue;
      }

      if ($response->getStatusCode() != 204) {
        $output->writeln(sprintf('<error>Error calling dashboard API. %s</error>', $response->getStatusCode()));
      }
      else {
        $output->writeln(sprintf('<info>Deleted %s</info>', $site_id));
      }
    }
  }

}
