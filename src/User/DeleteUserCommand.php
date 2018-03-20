<?php

namespace PNX\Dashboard\User;

use PNX\Dashboard\BaseCommand;
use PNX\Dashboard\Utils\Parser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Command for deleting users.
 */
class DeleteUserCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName("user:delete")
      ->setDescription("Delete a user.")
      ->addArgument("username", InputArgument::REQUIRED, "The username");
  }

  /**
   * {@inheritdoc}
   */
  protected function doExecute(InputInterface $input, OutputInterface $output, array $options) {
    $username = $input->getArgument('username');

    $helper = $this->getHelper('question');
    $question = new Question('Are you sure? [y/n]');
    $confirm = $helper->ask($input, $output, $question);
    if (!Parser::parseBoolean($confirm)) {
      $output->writeln("Cancelled.");
      return NULL;
    }

    $response = $this->client->delete('/users/' . $username, $options);
    if ($response->getStatusCode() != 204) {
      $output->writeln("Error calling dashboard API: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
      return NULL;
    }

    $output->writeln("User deleted.");
  }

}
