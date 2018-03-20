<?php

namespace PNX\Dashboard\User;

use PNX\Dashboard\BaseCommand;
use PNX\Dashboard\Utils\Parser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Command for creating a new user.
 */
class CreateUserCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName("user:create")
      ->setDescription("Create a new user.")
      ->addArgument("user-username", InputArgument::REQUIRED, "The new user's username.")
      ->addArgument("user-password", InputArgument::OPTIONAL, "The new user's password.")
      ->addOption("role", 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "The user roles", [])
      ->addOption("client-id", 'c', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "The client IDs", [])
      ->addOption("enabled", 'e', InputOption::VALUE_REQUIRED, "Is the account enabled? y/n", 'y');
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $username = $input->getArgument("user-username");
    $password = $input->getArgument("user-password");
    if (!isset($password)) {
      $helper = $this->getHelper('question');
      $question = new Question('What is the user\'s password?');
      $question->setHidden(TRUE);
      $question->setHiddenFallback(FALSE);
      $password = $helper->ask($input, $output, $question);
    }

    $roles = $input->getOption("role");
    $clientIds = $input->getOption("client-id");
    $enabled = Parser::parseBoolean($input->getOption("enabled"));

    $user = [
      "username" => $username,
      "password" => $password,
      "roles" => $roles,
      "client_ids" => $clientIds,
      "enabled" => $enabled,
    ];

    $json = json_encode($user);
    $options['body'] = $json;

    print_r($options);

    $response = $this->client->post('/users', $options);

    if ($response->getStatusCode() != 204) {
      $output->writeln("Error calling dashboard API: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
      return NULL;
    }
    $output->writeln("User created.");
  }

}
