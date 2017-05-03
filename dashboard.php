#!/usr/bin/env php
<?php
/**
 * @file
 * Console application for PNX Dashboard.
 */

const APP_NAME = 'Dashboard Console';
const VERSION = '0.0.8';

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use PNX\Dashboard\DeleteCommand;
use PNX\Dashboard\GetCommand;
use PNX\Dashboard\ListCommand;
use Symfony\Component\Console\Application;

$client = new Client([
  'headers' => [
    'Content-Type' => 'application/json'
  ]
]);

$application = new Application(APP_NAME, VERSION);
$application->add(new ListCommand($client));
$application->add(new GetCommand($client));
$application->add(new DeleteCommand($client));
$application->run();
