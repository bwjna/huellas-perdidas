<?php
$server = 'mysql:dbname=HuellasPerdidas;host=localhost;charset=utf8mb4';
$usuario = 'root';
$clave = '';

try {
    $conn = new PDO($server, $usuario, $clave);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
     PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>