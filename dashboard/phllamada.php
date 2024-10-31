<?php
header('Content-Type: application/json'); // Configura el tipo de contenido a JSON

require_once __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

// Datos de la API de ThingSpeak
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

    // Configuración de los mensajes de Telegram
    $chatId = '7918368896';  // Reemplaza con el ID del chat o grupo al que enviarás el mensaje
    $botToken = '7640093296:AAFw7U_iHYHT5LTbjUvU98QozJqiGh584mc';  // Reemplaza con tu token del bot

    if ($estado_agua === '0') {
        $mensaje = "Alerta: El estado del agua en el criadero de tilapias AQUAPERU es No Óptimo. ";
        $mensaje .= "Temperatura: " . $temperatura . "°C, pH: " . ($ph !== null ? $ph : 'No disponible') . 
        ". Si el pH no está disponible, es crucial revisarlo cuanto antes.";

        // Enviar mensaje a Telegram
        $telegram_url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($mensaje);
        file_get_contents($telegram_url);

        // Hacer la llamada usando Twilio
        $sid = "AC402b63c2ee75d080c1b157001e366e6e";
        $token = "50f2dc1f27ff9d4d705ca4004187b863";
        $twilio = new Client($sid, $token);

        try {
            $call = $twilio->calls->create(
                "+51961576391", // Número de teléfono al que deseas llamar
                "+15017122661", // Número de teléfono de Twilio que realiza la llamada
                [
                    "url" => "https://oscarnavar.github.io/Control-Automatizado-PH.github.io/dashboard/Mensaje.html" // URL del TwiML para manejar la llamada
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
