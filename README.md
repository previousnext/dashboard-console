# Dashboard Console

The dashboard console is a command line client for querying the PNX Dashboard API.

## Installation

Install using composer:

`composer install`

Alternatively, you can download the build phar command from the releases page
 on Github https://github.com/previousnext/dashboard-console/releases/

Once downloaded, you will need to make it executable. If you want it to be
globally available, copy it to somewhere in your $PATH (e.g. /usr/local/bin).

For example:

```bash
chmod +x dashboard-console.phar
mv dashboard-console.phar /usr/local/bin/dash
```

You can then run the command from anywhere using:

```
dash snapshot:list
```

_See details below on storing passwords in environment variables._


## Running the commands

The client has two commands, one for viewing a list of all snapshots, and the
other for viewing the detail of an individual site.

### Common parameters

* `--base-url` The base url for the Dashboard API.
* `--username` The username used to connect to the dashboard.
* `--password` The password used to connect to the dashboard.

### View all snapshots

To view all snapshots, run the command:

`dash snapshot:list --password <SECRET PASSWORD>`

### View snapshot details

To view the details of a snapshot, run the command:

`dash snapshot:get <SITE ID> --password <SECRET PASSWORD>`

Where `<SITE ID>` is the unique site ID. This is displayed in the _snapshots_
output.

To filter the snapshot down to only  _error_ alerts. Add the flag:

`--alert-level=error`

### Using environment variables to store credentials

To avoid having to type in the same credentials over and over, you can store
them in environment variables.

For example, you can add the following to `~/.bashrc`:

```bash
export DASHBOARD_USERNAME=<SECRET USERNAME>
export DASHBOARD_PASSWORD=<SECRET PASSWORD>
```

The command becomes simply:

```bash
./dashboard.php snapshot:list
```

Or if you followed the steps above for downloading the phar file:

```bash
dash snapshot:list
```

## Building PHAR

```
$ make phar
```

### Personalisation

Add the list of clients you maintain to your `~/.bashrc`

```bash
export DASHBOARD_CLIENT_ID=bnm,police
```

Now when you run dashboard.php snapshots, you'll only see the clients you maintain.

Most of the time you'll only want to see errors on your production sites, create an alias like so:

```bash
# Presumes dashboard-console is the phar file and in your PATH.
alias prod-errors='dash snapshot:get test_site test_site2 --alert-level=error'
```
