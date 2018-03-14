<?php

namespace PNX\Dashboard\Snapshot;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides a delete command.
 */
class DeleteSnapshotCommand extends BaseSnapshotCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('snapshot:delete')
      ->setDescription("Delete snapshot data.")
      ->addArgument('site-id', InputArgument::REQUIRED | InputArgument::IS_ARRAY, "The site ID.");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $site_ids = $input->getArgument('site-id');

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
