<?php
$token = "I-EznX2FMiZTlGc3ZEj0JcG28Ux965RgShGoHvEZZL30tpkHIAb7OEkXLQCAQaOr_uxgR1z9JPT9AAG4Zq2-mXWC4o7_3bxU6xmNyp20y2ueOwDz-jas2aHPQIzsV0LZNx3RYudAej_w2kYEhp47mQO93buJdiTpz_DBv3OyAkjLpFPH8AYYODAxNkZewcfr-_QPGAs41wA9Vh6iNVbzjHNsdSfDZDlwasTEuSIn7ZVfQxvDpTZ5bHccN0oy3xRWogFlJlMi2l0-EV-UT8Te7WbxmeelFphKXdVSCYZW-ovsEKRD5seqqn2NEll-eemn4IhfdA"; // solo en tu entorno local

$monto = $_POST['monto'];
$descripcion = $_POST['descripcion'];

$data = [
    "amount" => (int)$monto,
    "amountWithoutTax" => (int)$monto,
    "tax" => 0,
    "clientTransactionId" => "PEDIDO-" . time(),
    "responseUrl" => "http://localhost:8000/respuesta.php",
    "currency" => "USD",
    "reference" => $descripcion
];

$ch = curl_init("https://pay.payphonetodoesposible.com/api/button/Prepare");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$resultado = json_decode($response, true);

/* Redirección correcta */
if (isset($resultado['payWithPayPhone'])) {
    header("Location: " . $resultado['payWithPayPhone']);
    exit;
} else {
    echo "No se pudo iniciar el pago";
}
