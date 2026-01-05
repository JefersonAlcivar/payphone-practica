<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$token = "I-EznX2FMiZTlGc3ZEj0JcG28Ux965RgShGoHvEZZL30tpkHIAb7OEkXLQCAQaOr_uxgR1z9JPT9AAG4Zq2-mXWC4o7_3bxU6xmNyp20y2ueOwDz-jas2aHPQIzsV0LZNx3RYudAej_w2kYEhp47mQO93buJdiTpz_DBv3OyAkjLpFPH8AYYODAxNkZewcfr-_QPGAs41wA9Vh6iNVbzjHNsdSfDZDlwasTEuSIn7ZVfQxvDpTZ5bHccN0oy3xRWogFlJlMi2l0-EV-UT8Te7WbxmeelFphKXdVSCYZW-ovsEKRD5seqqn2NEll-eemn4IhfdA"; // solo en tu PC

// ID que Payphone envía al redirigir
$paymentId = $_REQUEST['id'] ?? null;
$clientTxId = $_REQUEST['clientTransactionId'] ?? null;

$estado = "DESCONOCIDO";
$datosPago = [];

if ($paymentId && $clientTxId) {

    $data = [
        "id" => $paymentId,
        "clientTxId" => $clientTxId
    ];

    $ch = curl_init("https://pay.payphonetodoesposible.com/api/button/V2/Confirm");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $datosPago = json_decode($response, true);

    if (isset($datosPago['transactionStatus'])) {
        $estado = $datosPago['transactionStatus'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Pago</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }

        .contenedor {
            max-width: 650px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        h1 {
            text-align: center;
            color: #0f172a;
        }

        .estado {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px;
            margin: 20px 0;
        }

        .aprobado {
            background-color: #dcfce7;
            color: #166534;
        }

        .rechazado {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .detalle p {
            margin: 6px 0;
            font-size: 15px;
        }

        .detalle span {
            font-weight: bold;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 25px;
            background-color: #2563eb;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
        }

        a:hover {
            background-color: #1e40af;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h1>💳 Resultado del Pago</h1>

    <?php if ($estado === "Approved"): ?>
        <div class="estado aprobado">✅ Pago aprobado correctamente</div>
    <?php else: ?>
        <div class="estado rechazado">❌ Pago no aprobado</div>
    <?php endif; ?>

    <div class="detalle">
        <?php foreach ($datosPago as $clave => $valor): ?>
            <p><span><?= htmlspecialchars($clave) ?>:</span> <?= htmlspecialchars(print_r($valor, true)) ?></p>
        <?php endforeach; ?>
    </div>

    <a href="index.html">⬅ Volver a la tienda</a>
</div>

</body>
</html>
