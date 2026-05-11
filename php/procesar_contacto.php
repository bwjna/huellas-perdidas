<?php
require_once('../modelo/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_contacto'];
    $email = $_POST['email_contacto'];
    $mensaje = $_POST['mensaje_contacto'];

    try {
        // Aquí podrías guardar en una tabla 'contacto' o enviar un mail
        // Por ahora, simularemos un éxito y redirigimos
        header("Location: ../php/contacto.php?status=success");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>