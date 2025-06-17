<?php
include("config.php");

$error = "";
$exito = "";

// Eliminar reserva si llega id por GET
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $exito = "Reserva eliminada correctamente.";
    } else {
        $error = "Error al eliminar la reserva.";
    }
    $stmt->close();
}

// Obtener todas las reservas ordenadas por fecha y hora
$result = $conn->query("SELECT * FROM reservas ORDER BY fecha ASC, hora ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Reservas</title>
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px 20px;
            color: #2c3e50;
        }
        .contenedor {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2980b9;
            margin-bottom: 20px;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f0f8ff;
        }
        a.btn-eliminar {
            color: white;
            background-color: #e74c3c;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        a.btn-eliminar:hover {
            background-color: #c0392b;
        }
        .mensaje-error {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .mensaje-exito {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            background-color: #3a86ff;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-volver:hover {
            background-color: #265dcc;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Mis Reservas</h1>

        <?php if ($error): ?>
            <div class="mensaje-error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($exito): ?>
            <div class="mensaje-exito"><?= htmlspecialchars($exito) ?></div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['apellido']) ?></td>
                            <td><?= htmlspecialchars($row['correo']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                            <td><?= htmlspecialchars(substr($row['hora'], 0, 5)) ?></td>
                            <td>
                                <a href="?eliminar=<?= $row['id'] ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que quieres eliminar esta reserva?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes reservas registradas.</p>
        <?php endif; ?>

        <a href="index.php" class="btn-volver">Volver a Reservar</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
