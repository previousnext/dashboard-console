<?php

namespace PNX\Dashboard\User;

use PNX\Dashboard\BaseCommand;
use PNX\Dashboard\Utils\Parser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for updating a user.
 */
class UpdateUserCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName("user:update")
      ->setDescription("Update user attributes.")
      ->addArgument("user-username", InputArgument::REQUIRED, "The user's username")
      ->addOption("user-password", NULL, InputOption::VALUE_REQUIRED, "The user password.")
      ->addOption("role", "r", InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "The user role.")
      ->addOption("client-id", "c", InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "The user role.")
      ->addOption("enabled", "e", InputOption::VALUE_REQUIRED, "Is the user account enabled? [y/n]");

  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $username = $input->getArgument('user-username');

    $user = [];
    $password = $input->getOption("user-password");
    if ($password) {
      $user['password'] = $password;
    }
    $roles = $input->getOption("role");
    if ($roles) {
      $user['roles'] = $roles;
    }
    $clientIds = $input->getOption("client-id");
    if ($clientIds) {
      $user['client_ids'] = $clientIds;
    }
    $enabled = $input->getOption("enabled");
    if (isset($enabled)) {
      $user['enabled'] = Parser::parseBoolean($input->getOption("enabled"));
    }

    $body = json_encode($user);
    $options['body'] = $body;

    $response = $this->client->patch('/users/' . $username, $options);

    if ($response->getStatusCode() != 204) {
      $output->writeln("Error calling dashboard API: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
      return NULL;
    }

    $output->writeln("User updated.");
  }

}
