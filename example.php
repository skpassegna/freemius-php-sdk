<?php

require_once __DIR__ . '/vendor/autoload.php';

use Freemius\SDK\Api\FreemiusApi;
use Freemius\SDK\Http\CurlHttpClient;
use Freemius\SDK\Authentication\SignatureAuthenticator;
use Freemius\SDK\Exceptions\ApiException;

// https://guardiv.test

// Freemius API credentials
$scope        = 'developer';
$developerId  = 17789;
$publicKey    = 'pk_e9f68da8dc036c0085723313b9e2d';
$secretKey    = 'sk_6SWIE]0xiZ6RHc]QaQ;)A(hpf1-*x';
$sandbox      = false; // Set to false for production

// Load configuration
$config = require __DIR__ . '/config.php';
$baseUrl = $sandbox ? $config['API_SANDBOX_BASE_URL'] : $config['API_BASE_URL'];

// Initialize the HTTP client
$httpClient = new CurlHttpClient($baseUrl);

// Initialize the authenticator
$authenticator = new SignatureAuthenticator($scope, $developerId, $publicKey, $secretKey, $httpClient);

// Initialize the Freemius API client
$api = new FreemiusApi($scope, $developerId, $publicKey, $secretKey, $sandbox, $httpClient, $authenticator);

try {
    // Get a list of plugins for the developer
    $plugins = $api->plugins()->getPlugins();

    dd($plugins);

    /* // Print the title of each plugin
    foreach ($plugins as $plugin) {
        echo 'Plugin Title: ' . $plugin->title . PHP_EOL;
    } */

    /* // Get a specific plugin
    $pluginId = 123; // Replace with an actual plugin ID
    $plugin = $api->plugins()->getPlugin($pluginId);

    // Print the plugin's slug
    echo 'Plugin Slug: ' . $plugin->slug . PHP_EOL;

    // Get a list of installs for a plugin
    $installs = $api->installs()->getInstalls($pluginId);

    // Print the URL of each install
    foreach ($installs as $install) {
        echo 'Install URL: ' . $install->url . PHP_EOL;
    }

    // Get a list of users for a plugin
    $users = $api->users()->getUsers($pluginId);

    // Print the email of each user
    foreach ($users as $user) {
        echo 'User Email: ' . $user->email . PHP_EOL;
    } */
} catch (ApiException $e) {
    echo 'Freemius API Error: ' . $e->getMessage() . PHP_EOL;
    echo 'Status Code: ' . $e->getStatusCode() . PHP_EOL;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}