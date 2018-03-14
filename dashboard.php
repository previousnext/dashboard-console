#!/usr/bin/env php
<?php
/**
 * @file
 * Console application for PNX Dashboard.
 */

const APP_NAME = 'Dashboard Console';
const VERSION = '0.0.10';

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use PNX\Dashboard\Snapshot\DeleteSnapshotCommand;
use PNX\Dashboard\Snapshot\GetSnapshotCommand;
use PNX\Dashboard\Snapshot\ListSnapshotsCommand;
use Symfony\Component\Console\Application;

$client = new Client([
  'headers' => [
    'Content-Type' => 'application/json'
  ]
]);

$application = new Application(APP_NAME, VERSION);
$application->add(new ListSnapshotsCommand($client));
$application->add(new GetSnapshotCommand($client));
$application->add(new DeleteSnapshotCommand($client));
$application->run();
