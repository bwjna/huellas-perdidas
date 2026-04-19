<?php
session_start();
require_once('../modelo/conexion.php'); // Usamos require_once para asegurar la conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiamos los datos de entrada
    $email = trim($_POST['email']);
    $clave = $_POST['clave'];

    try {
        // Buscamos al usuario por su email
        $stmt = $conn->prepare("SELECT id_usuario, nombre, rol, clave FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Verificación de contraseña
            // NOTA: Si en la DB las tienes en texto plano, usa: if ($clave == $usuario['clave'])
            // Pero lo ideal es usar password_verify($clave, $usuario['clave'])
            if ($clave == $usuario['clave']) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];

                header("Location: ../php/index.php");
                exit();
            } else {
                header("Location: ../php/index.php?error=clave_incorrecta");
            }
        } else {
            header("Location: ../php/index.php?error=no_existe");
        }
    } catch (PDOException $e) {
        echo "Error en el sistema: " . $e->getMessage();
    }
}
?>