<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "usuariosbd";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) 
{
	die("No hay conexión: ".mysqli_connect_error());
}

$nombre = $_POST["txtcorreo"];
$pass = $_POST["txtcontraseña"];




$query = mysqli_query($conn,"SELECT * FROM usuarios WHERE correo = '".$nombre."' and contraseña = '".$pass."'");
$nr = mysqli_num_rows($query);

if($nr >= 1)
{
	header("Location: dashboard/dashboard.html");
	echo "Bienvenido:";
}
else if ($nr == 0) 
{
	//header("Location: login.html");
	//echo "No ingreso"; 
	echo "<script> alert('Correo electronico o contraseña invalidos');window.location= 'index.html' </script>";
}
	


?>