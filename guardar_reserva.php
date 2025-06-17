<?php
include("config.php");

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

$consulta = "SELECT COUNT(*) AS cantidad FROM reservas WHERE fecha = '$fecha'";
$resultado = $conn->query($consulta);
$fila = $resultado->fetch_assoc();

if ($fila['cantidad'] >= 10) {
    echo "Lo sentimos, ya hay 10 reservas para esa fecha.<br><a href='index.php'>Volver</a>";
} else {
    $sql = "INSERT INTO reservas (nombre, apellido, correo, telefono, fecha, hora)
            VALUES ('$nombre', '$apellido', '$correo', '$telefono', '$fecha', '$hora')";
    if ($conn->query($sql) === TRUE) {
        header("Location: ver_reservas.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
