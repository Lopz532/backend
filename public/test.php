<?php

$url = "https://script.google.com/macros/s/AKfycbwN9kBAK6aSRTU7v3XnSwmizmzHg2t1i3JIiSqYgbsqQuXTci7CFIl5jKCUTXj2OIbffA/exec";

$data = [
    "nombre" => "JVLEX",
    "mensaje" => "Prueba desde Render"
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "<h2>Resultado</h2>";

if ($error) {
    echo "<p>Error cURL: $error</p>";
} else {
    echo "<p>HTTP Code: $httpCode</p>";
    echo "<pre>$response</pre>";
}