<?php
/*
 * Sample OAuth2 client for simulating process flow from authorisation to retrieving an access token.
 * @author Jack Elendil B. Lagare <j.lagare@irri.org>
 *
 */

    // Client credentials. This is predefined.
    $clientId = 'jack';
    $clientSecret = 'jack';
    $redirectUri = 'http://localhost:8888/oauth2_php_client.php';

    // The URL of the OAuth2 server.
    $oauth2Server = 'http://localhost:8888/oauth2server/v1/authenticate?client_id='
      .$clientId.'&client_secret='.$clientSecret.'&redirect_uri='.$redirectUri.'&response_type=code';


    echo "<h1> Welcome! </h1>";
    echo "<div>";
    echo "<p>Client ID: ".$clientId."</p>";
    echo "<p>Client Secret: ".$clientSecret."</p>";
    echo "<p>Redirect Uri: ".$redirectUri."</p>";
    echo "</div>";

    // Request for an authorisation code
    echo "<a href='".$oauth2Server."'>Request access...</a>";

    echo '<hr/>';

    // If authorisation code was successfully retrieved...
    if(isset($_GET['code'])){

        // The endpoint for getting the access token
        $url = 'http://localhost:8888/oauth2server/v1/authenticate/token';

        //set POST variables
        $fields = array(
                  'client_id' => urlencode($clientId),
                  'client_secret' => urlencode($clientSecret),
                  'response_type' => urlencode('code'),
                  'redirect_uri' => urlencode($redirectUri),
                  'grant_type' => urlencode('authorization_code'),
                  'code' => urlencode($_GET['code']),
              );

        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

    }
?>
