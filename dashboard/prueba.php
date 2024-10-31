<?php
header('Content-Type: application/json'); // Configura el tipo de contenido a JSON

require_once __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

$api_url = 'https://api.thingspeak.com/channels/2665842/feeds.json?results=1';
$response = file_get_contents($api_url);

if ($response === FALSE) {
    echo json_encode(['error' => 'Error al obtener datos de la API']);
    exit;
}

$data = json_decode($response, true);
if (isset($data['feeds']) && count($data['feeds']) > 0) {
    $feed = $data['feeds'][0];
    $temperatura = isset($feed['field1']) ? $feed['field1'] : null;
    $ph = isset($feed['field2']) ? $feed['field2'] : null;

    // Definir el estado del agua basado en temperatura y pH
    $estado_agua = ($temperatura >= 26 && $temperatura <= 30 && $ph >= 6.5 && $ph <= 8.5) ? '1' : '0';
    
    $result = [
        'temperatura' => $temperatura,
        'ph' => $ph,
        'estado_agua' => $estado_agua,
    ];

    // Solo enviar SMS si el estado del agua es "no óptimo"
    if ($estado_agua === '0') {
        $mensaje = "Alerta: El estado del agua en el criadero de tilapias AQUAPERU es No Óptimo. ";
        $mensaje .= "Temperatura: " . $temperatura . "°C, pH: " . ($ph !== null ? $ph : 'No disponible') . 
        ". Si el pH no está disponible, es crucial revisarlo cuanto antes.";

        $sid = "AC402b63c2ee75d080c1b157001e366e6e";
        $token = "50f2dc1f27ff9d4d705ca4004187b863";
        $twilio = new Client($sid, $token);

        try {
            $message = $twilio->messages->create(
                "+51961576391",
                [
                    "messagingServiceSid" => "MGed5a5f1f6c9b7f671e3904b7a6a198a0",
                    "body" => $mensaje
                ]
            );
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            exit;
        }
           
    }

    echo json_encode(["status" => "success", "data" => $result]);
} else {
    echo json_encode(['error' => 'No se encontraron feeds disponibles']);
}
?>
