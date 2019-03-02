<?php

function grab($site_url) {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $site_url);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

    $data = curl_exec($curl);

    curl_close($curl);

    if ($data) return $data;
    else return FALSE;
}

?>