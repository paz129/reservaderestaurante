<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $sql = "DELETE FROM reservas WHERE id = $id";
    $conn->query($sql);
}

header("Location: ver_reservas.php");
exit();

$conn->close();
?>
