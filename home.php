<?php

$time = time();
//preparing query for authorized request token
$querystring =
        'oauth_consumer_key=' . urlencode('kopecki1.projects.cs.illinois.edu') .
        '&oauth_nonce=' . urlencode('4e18ada0d45784e18ada0d495c') .
        '&oauth_signature_method=HMAC-SHA1' .
        '&oauth_timestamp=' . $time .
        '&oauth_token=' . urlencode($_GET["oauth_token"]) .
        '&oauth_verifier=' . urlencode($_GET["oauth_verifier"]) .
        '&oauth_version=' . urlencode('1.0');
//url to obtain authorized request token
$url = 'https://www.google.com/accounts/OAuthGetAccessToken';
//base string for generating signature
$basestring = 'GET&' . urlencode($url) . '&' . urlencode($querystring);
$consumersecret = 'R4Uj20k75MHXB-6IWfnODhtb';
//token secret from unauthorized request token
$token = $_COOKIE["token_secret"];
$key = $consumersecret . '&' . $token;
$signature = base64_encode(hash_hmac('SHA1', $basestring, $key, true));
$url = 'https://www.google.com/accounts/OAuthGetAccessToken?' . $querystring . '&oauth_signature=' . urlencode($signature);
//getting authorized request token
$response = file_get_contents($url);
$ltrimarray = explode("oauth_token=", $response);
$rtrimarray = explode("&", $ltrimarray[1]);
$oauth_token = $rtrimarray[0];
$ltrimarray = explode("oauth_token_secret=", $response);
$rtrimarray = explode("&", $ltrimarray[1]);
$oauth_token_secret = $rtrimarray[0];
setcookie("token_secret1", $oauth_token_secret, time() + 3600);
header('Location:mailinfo.php?oauth_token=' . $oauth_token);
?>
