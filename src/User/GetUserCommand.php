<?php

namespace PNX\Dashboard\User;

use PNX\Dashboard\BaseCommand;
use PNX\Dashboard\Utils\Formatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A command for getting a user.
 */
class GetUserCommand extends BaseCommand {

  /**
   * The pad length.
   */
  protected const PAD_LENGTH = 12;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName("user:get")
      ->setDescription("Get user information.")
      ->addArgument("username", InputArgument::REQUIRED, "The username");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $username = $input->getArgument('username');
    $response = $this->client->get('/users/' . $username, $options);

    if ($response->getStatusCode() != 200) {
      $output->writeln("Error calling dashboard API: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
      return NULL;
    }

    $user = json_decode($response->getBody(), TRUE);

    $output->writeln(str_pad("Username:", self::PAD_LENGTH) . $user['username']);
    $output->writeln(str_pad("Roles:", self::PAD_LENGTH) . implode(", ", $user['roles']));
    $output->writeln(str_pad("Client IDs:", self::PAD_LENGTH) . implode(", ", $user['client_ids']));
    $output->writeln(str_pad("Enabled:", self::PAD_LENGTH) . Formatter::formatBoolean($user['enabled']));
  }

}
