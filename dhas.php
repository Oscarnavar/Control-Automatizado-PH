<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Parámetros del Agua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
        }
        h1 {
            color: #333;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        iframe {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Monitoreo de Parámetros del Agua</h1>
        <p>Aquí puedes ver el monitoreo en tiempo real del pH y la temperatura del agua en tu criadero de tilapia.</p>
        
        <!-- Gráfico de pH (usando el código iframe) -->
        <iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2665842/charts/3?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&type=line&update=15"></iframe>
    </div>
</body>
</html>
