<?php
$options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
    ); 
$url="http://temoignage-normandie.site/routiers/Cron/addCren";
$ch = curl_init($url);
curl_setopt_array($ch, $options);
$content  = curl_exec($ch);
curl_close($ch);
var_dump($content);

$url="https://www.temoignage-normandie.site/routiers/Cron/lancerInvitation";
$ch = curl_init($url);
curl_setopt_array($ch, $options);
$content  = curl_exec($ch);
curl_close($ch);
var_dump($content);


if(date("d", strtotime("first monday of this month"))==date('d')){
$url="https://www.temoignage-normandie.site/routiers/Cron/sendReport";
$ch = curl_init($url);
curl_setopt_array($ch, $options);
$content  = curl_exec($ch);
curl_close($ch);
var_dump($content);
}
