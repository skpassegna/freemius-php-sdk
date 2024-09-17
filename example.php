<?php

require_once __DIR__ . '/vendor/autoload.php';

use Freemius\SDK\Api\FreemiusApi;


define( 'FS__API_SCOPE', 'developer' );
define( 'FS__API_DEV_ID', 12345 );
define( 'FS__API_PUBLIC_KEY', 'pk_test' );
define( 'FS__API_SECRET_KEY', 'sk_test' );
// https://guardiv.test

// Replace with your actual Freemius API credentials
$scope        = 'developer';
$developerId  = 12345;
$publicKey    = 'pk_your_public_key';
$secretKey    = 'sk_your_secret_key';
$sandbox      = true; // Set to false for production

$api = new FreemiusApi($scope, $developerId, $publicKey, $secretKey, $sandbox);

try {
    // Get a list of plugins for the developer
    $plugins = $api->plugins()->getPlugins();

    // Print the title of each plugin
    foreach ($plugins as $plugin) {
        echo $plugin->title . PHP_EOL;
    }

    // Get a specific plugin
    $plugin = $api->plugins()->getPlugin(123);

    // Print the plugin's slug
    echo $plugin->slug . PHP_EOL;

    // Get a list of installs for a plugin
    $installs = $api->installs()->getInstalls(123);

    // Print the URL of each install
    foreach ($installs as $install) {
        echo $install->url . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}