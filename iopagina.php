<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización de Datos de ThingSpeak</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Datos de ThingSpeak</h1>

        <div id="tabla-datos">
            <!-- Aquí se cargará la tabla con los datos -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para cargar los datos de ThingSpeak
        function cargarDatos() {
            const api_url = 'https://api.thingspeak.com/channels/2665842/feeds.json?results=10';

            fetch(api_url)
                .then(response => response.json())
                .then(data => {
                    let feeds = data.feeds;
                    let tabla = "<table class='table table-striped table-bordered'>";
                    tabla += "<thead class='table-dark'>";
                    tabla += "<tr><th>Fecha y Hora</th><th>Temperatura (°C)</th><th>pH del Agua</th><th>Estado del Agua</th></tr>";
                    tabla += "</thead><tbody>";

                    feeds.forEach(feed => {
                        let created_at = feed.created_at;
                        let field1 = feed.field1;  // Temperatura
                        let field2 = feed.field2;  // pH
                        let field3 = feed.field3;  // Estado del agua

                        // Definir el estado del agua
                        let estado_agua = field3 == '1' 
                            ? "<span class='text-success fw-bold'>Agua Óptima</span>" 
                            : "<span class='text-danger fw-bold'>No Óptima</span>";

                        tabla += `<tr>
                                    <td>${created_at}</td>
                                    <td>${field1}</td>
                                    <td>${field2}</td>
                                    <td>${estado_agua}</td>
                                  </tr>`;
                    });

                    tabla += "</tbody></table>";
                    document.getElementById('tabla-datos').innerHTML = tabla;
                })
                .catch(error => console.error('Error al obtener los datos:', error));
        }

        // Cargar los datos inicialmente
        cargarDatos();

        // Actualizar los datos cada 20 segundos (20000 ms)
        setInterval(cargarDatos, 20000);
    </script>
</body>
</html>