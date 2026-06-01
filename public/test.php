<?php

$url = "https://script.google.com/macros/s/AKfycbwN9kBAK6aSRTU7v3XnSwmizmzHg2t1i3JIiSqYgbsqQuXTci7CFIl5jKCUTXj2OIbffA/execv";

$data = [
    "nombre" => "JVLEX",
    "mensaje" => "Prueba desde Render"
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);

echo $response;