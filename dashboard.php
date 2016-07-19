#!/usr/bin/env php
<?php
/**
 * @file
 * Console application for PNX Dashboard.
 */

const APP_NAME = 'Dashboard Console';
const VERSION = '0.0.4';

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use PNX\Dashboard\GetCommand;
use PNX\Dashboard\SnapshotsCommand;
use Symfony\Component\Console\Application;


$client = new Client([
  'headers' => [
    'Content-Type' => 'application/json'
  ]
]);

$application = new Application(APP_NAME, VERSION);
$application->add(new SnapshotsCommand($client));
$application->add(new GetCommand($client));
$application->run();
