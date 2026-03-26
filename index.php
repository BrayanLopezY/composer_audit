<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;
use Symfony\Component\VarDumper\VarDumper;

// Logger
$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::WARNING));

// Fecha con Carbon
$fecha = Carbon::now();

// Cliente HTTP (con User-Agent para evitar bloqueos)
$client = new Client([
    'headers' => [
        'User-Agent' => 'MiniAuditApp'
    ]
]);

$responseText = "";

try {
    $response = $client->request('GET', 'https://api.github.com');
    $responseText = $response->getStatusCode();
} catch (Exception $e) {
    // Log del error correctamente
    $log->addWarning("Error HTTP: " . $e->getMessage());

    // Mostrar error en pantalla (opcional)
    $responseText = "Error en la petición";
}

// Dump de variables
ob_start();
VarDumper::dump($fecha);
$dump = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mini Audit App</title>
</head>

<body>

    <h1>Mini App para Composer Audit 🚀</h1>

    <h3>Fecha actual:</h3>
    <p><?php echo $fecha; ?></p>

    <h3>Petición HTTP:</h3>
    <p>Status API GitHub: <?php echo $responseText; ?></p>

    <h3>Log:</h3>
    <p>Revisa el archivo <b>app.log</b></p>

    <h3>Dump:</h3>
    <pre><?php echo $dump; ?></pre>

</body>

</html>