<?php

global $user;

session_start();

$client = new \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient(['region' => $_SERVER['AWS_REGION'] ?? 'us-east-1', 'version' => 'latest']);

if (!empty($_SESSION['user.accessToken'])) {
    try {
        $user = $client->getUser(['AccessToken' => $_SESSION['user.accessToken']]);
    } catch (\Exception $e) {
        session_destroy();
    }
}

return $client;