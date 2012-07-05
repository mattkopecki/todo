<?php

//to store time stamp
$time = time();
//preparing the parameters that too in alphabetical order
//alphabetiacal order is to generate signature
$querystring =
        'oauth_callback=' . urlencode('http://kopecki1.projects.cs.illinois.edu/home.php') .
        '&oauth_consumer_key=' . urlencode('kopecki1.projects.cs.illinois.edu') .
        '&oauth_nonce=' . urlencode(uniqid() . uniqid()) .
        '&oauth_signature_method=HMAC-SHA1' .
        '&oauth_timestamp=' . $time .
        '&scope=' . urlencode('http://mail.google.com/mail/feed/atom');
//url to request for unauthorized request token
$url = 'https://www.google.com/accounts/OAuthGetRequestToken';
//creating base string
$basestring = 'GET&' . urlencode($url) . '&' . urlencode($querystring);
$consumersecret = 'R4Uj20k75MHXB-6IWfnODhtb';
$token = '';
$key = $consumersecret . '&' . $token;
$signature = base64_encode(hash_hmac('SHA1', $basestring, $key, true));
$url = 'https://www.google.com/accounts/OAuthGetRequestToken?' . $querystring . '&oauth_signature=' . urlencode($signature);
//fetching token and token secret
$response = file_get_contents($url);
//extracting token
$ltrimarray = explode("oauth_token=", $response);
$rtrimarray = explode("&", $ltrimarray[1]);
$oauth_token = $rtrimarray[0];
//extracting token secret
$ltrimarray = explode("oauth_token_secret=", $response);
$rtrimarray = explode("&", $ltrimarray[1]);
$oauth_token_secret = $rtrimarray[0];
//storing token secret to cookie to use in next page
setcookie("token_secret", $oauth_token_secret, time() + 3600);
//redirecting to google access/deny page
header('Location:https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=' . $oauth_token);
?>