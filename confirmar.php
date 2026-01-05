<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// TOKEN DE PAYPHONE (solo para pruebas locales)
$token = "CbcWYhhDEHD2GSmkIJ8wrK-uDKyAOfL7uF0Fnnm-Zs5xHREIhAge8xNoLX3UaBjriHLrjA6LSY0Ch7jQsxe5JCJBl2miWSzVWSR1knCHdlSanaBtqur1toNeevexiEljGASvI6XtwrcTcMN4wx_tpLSS3GuSh35dcaolUHJXK5lxaDzCAOQDFs191fcXgn4bQ28gIPUKC8IgWCJ_DD5WzzqhhNs5ZuwgnwmSSJPB6TnO9cWdRYuKf9ShatPfkiPsnx16vlQG0YkpZyy_dBA-AmlcGvHoNhJiY9bTN1NCi3dQg-NRiyEth8XzHbXz96db1UASkQ";

// DATOS A CONFIRMAR (pueden venir por GET o POST)
$paymentId = $_REQUEST['paymentId'] ?? null;
$clientTxId = $_REQUEST['clientTxId'] ?? null;

if (!$paymentId || !$clientTxId) {
    echo json_encode([
        "error" => "Faltan parámetros: paymentId o clientTxId"
    ]);
    exit;
}

// CUERPO DE LA PETICIÓN
$data = [
    "id" => (int)$paymentId,
    "clientTxId" => $clientTxId
];

// INICIALIZAR CURL
$ch = curl_init("https://pay.payphonetodoesposible.com/api/button/V2/Confirm");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// EJECUTAR
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// RESPUESTA EN JSON
header("Content-Type: application/json");
echo $response;
