<?php
include("config.php");

$error = "";
$exito = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $fecha = trim($_POST["fecha"] ?? "");
    $hora = trim($_POST["hora"] ?? "");

    if (!$nombre || !$apellido || !$correo || !$telefono || !$fecha || !$hora) {
        $error = "Por favor, completa todos los campos.";
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM reservas WHERE fecha = ?");
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $stmt->bind_result($cantidad);
        $stmt->fetch();
        $stmt->close();

        if ($cantidad >= 10) {
            $error = "Lo siento, ya hay 10 reservas para esa fecha.";
        } else {
            $stmt = $conn->prepare("INSERT INTO reservas (nombre, apellido, correo, telefono, fecha, hora) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nombre, $apellido, $correo, $telefono, $fecha, $hora);
            if ($stmt->execute()) {
                $exito = "Reserva creada correctamente.";
                $nombre = $apellido = $correo = $telefono = $fecha = $hora = "";
            } else {
                $error = "Error al guardar la reserva.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Reservar Mesa</title>
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
        }
        .contenedor {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            width: 380px;
        }
        h1 {
            margin-bottom: 20px;
            color: #2980b9;
            font-weight: 700;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 18px;
            font-weight: 600;
            color: #34495e;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px 14px;
            margin-top: 6px;
            border: 2px solid #ddd;
            border-radius: 7px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus {
            border-color: #2980b9;
            outline: none;
        }
        button {
            margin-top: 30px;
            width: 100%;
            padding: 12px 0;
            background-color: #2980b9;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1c5980;
        }
        .mensaje-error {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .mensaje-exito {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .btn-ver-reservas {
            margin-top: 25px;
            display: block;
            text-align: center;
            background-color: #3a86ff;
            color: white;
            padding: 12px 0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-ver-reservas:hover {
            background-color: #265dcc;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Reservar Mesa</h1>
        <?php if ($error): ?>
            <div class="mensaje-error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($exito): ?>
            <div class="mensaje-exito"><?= htmlspecialchars($exito) ?></div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre ?? '') ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($apellido ?? '') ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo ?? '') ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono ?? '') ?>" required>

            <label for="fecha">Fecha de Reserva:</label>
            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha ?? '') ?>" required>

            <label for="hora">Hora de Reserva:</label>
            <input type="time" id="hora" name="hora" value="<?= htmlspecialchars($hora ?? '') ?>" required>

            <button type="submit">Reservar</button>
        </form>

        <a href="ver_reservas.php" class="btn-ver-reservas">Ver Reservas</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
