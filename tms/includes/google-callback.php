<?php
require_once 'vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('1063662202942-dsok6pt0aq4sgu50ov0u0pse73tgvv1a.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/tms/google-callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $user_info = $google_oauth->userinfo->get();

        $_SESSION['signin'] = $user_info->email;
        $_SESSION['name'] = $user_info->name;

        echo "<script type='text/javascript'> document.location = 'package-list.php'; </script>";
    }
}
?>
