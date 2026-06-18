<?php

function call_api($url, $method = 'GET', $data = null, $timeout = 6)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

    if ($data !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response  = curl_exec($curl);
    $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($curlError || $httpCode >= 400) {
        return null;
    }

    return json_decode($response, true);
}