<html><body style="font-family: tahoma; font-size: 12px;">
        <a href="http://lookmywebpage.com/api/google/get-unread-emails-from-gmail-using-gmail-feed-api-oauth-and-php/">
            Visit Article
        </a><hr>
        <?php
        $time = time();
        $querystring =
                'oauth_consumer_key=' . urlencode('kopecki1.projects.cs.illinois.edu') .
                '&oauth_nonce=' . urlencode('4e18ada0d45784e18ada0d495d') .
                '&oauth_signature_method=HMAC-SHA1' .
                '&oauth_timestamp=' . $time .
                '&oauth_token=' . urlencode($_GET["oauth_token"]) .
                '&oauth_version=' . urlencode('1.0');

        $url = 'https://mail.google.com/mail/feed/atom/';

        $basestring = 'GET&' . urlencode($url) . '&' . urlencode($querystring);
        $consumersecret = 'R4Uj20k75MHXB-6IWfnODhtb';
        $token = $_COOKIE["token_secret1"];
        $key = $consumersecret . '&' . $token;
        $signature = base64_encode(hash_hmac('SHA1', $basestring, $key, true));
        $url = 'https://mail.google.com/mail/feed/atom/';
        $querystring = str_replace("=", "=\"", $querystring);
        $querystring = str_replace("&", "\", ", $querystring);
        $querystring = $querystring . "\", oauth_signature=\"" . urlencode($signature) . "\"";
        $r = 'Authorization: OAuth ' . $querystring;
        $header = array($r); //create header array and add 'Expect:'

        $options = array(CURLOPT_HTTPHEADER => $header, //use our authorization and expect header
            CURLOPT_HEADER => false, //don't retrieve the header back from Twitter
            CURLOPT_URL => $url, //the URI we're sending the request to
            CURLOPT_RETURNTRANSFER => true, //return content as a string, don't echo out directly
            CURLOPT_SSL_VERIFYPEER => false); //don't verify SSL certificate, just do it

        $ch = curl_init(); //get a channel
        curl_setopt_array($ch, $options); //set options
        $response = curl_exec($ch); //make the call
        curl_close($ch); //hang up
//extracting contents using simple xml
        $xml = simplexml_load_string($response);
        echo $xml->title . '<br>';
        echo 'You have ' . $xml->fullcount . ' ' . $xml->tagline . '<br><br>';
        foreach ($xml->entry as $child) {
            echo 'Subject: <b>' . $child->title . '</b><br>';
            echo 'From: ' . $child->author->name . '&lt;' . $child->author->email . '&gt;<br>';
            echo 'Time: <i>' . $child->modified . '</i><br>';
            echo 'Summary: ' . $child->summary . '<br><br>';
        }
        ?>

    </body>
</html>