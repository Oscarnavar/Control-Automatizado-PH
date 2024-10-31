const express = require('express');
const mysql = require('mysql2');
const app = express();

app.use(express.json());  // Para leer JSON

// Configurar la conexión a MySQL
const connection = mysql.createConnection({
    host: 'localhost',       // Servidor donde está MySQL (localhost si está en la misma máquina)
    user: 'root',      // Usuario de la base de datos MySQL
    password: '', // Contraseña de MySQL
    database: 'monitoring_data', // Nombre de la base de datos
});

// Ruta que recibe los datos del cliente y los inserta en la base de datos
app.post('/guardar-datos', (req, res) => {
    const datos = req.body.datos;  // Recibe los datos enviados desde el frontend

    // Recorrer los datos y guardarlos en la base de datos
    datos.forEach(row => {
        const [created_at, temperatura, ph, optimo] = row;
        const query = 'INSERT INTO water_data (created_at, temperatura, ph, optimo) VALUES (?, ?, ?, ?)';

        connection.query(query, [created_at, temperatura, ph, optimo], (error, results) => {
            if (error) {
                return res.status(500).send('Error al guardar en la BD');
            }
        });
    });

    res.send('Datos guardados con éxito');
});

// Iniciar el servidor en el puerto 3000
app.listen(3000, () => {
    console.log('Servidor escuchando en el puerto 3000');
});
