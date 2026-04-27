<?php
include '../php/config.php';
include '../modelo/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST['nombre_usuario'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    // verificar si el nombre de usuario ya existe
    $check = $conn->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ?");
    $check->execute([$usuario]);

    if($check->fetch()){
        $sugerencias = [];
        $opciones = [
            $usuario . rand(1, 99),
            $usuario . '_' . rand(1, 99),
            $usuario . '.' . $apellido,
        ];

        foreach($opciones as $opcion){
            $check2 = $conn->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ?");
            $check2->execute([$opcion]);
            if(!$check2->fetch()){
                $sugerencias[] = $opcion;
            }
        }

        $_SESSION['error'] = "El nombre de usuario ya está en uso";
        $_SESSION['sugerencias'] = $sugerencias;
        header("location: /acciones/registro.php");
        die();
    }

    $consulta = "INSERT INTO usuario (nombre_usuario, nombre, apellido, email, clave, rol, telefono) 
         VALUES (?, ?, ?, ?, ?, 'usuario', ?)";

    try {
        $stmt = $conn->prepare($consulta);
        $stmt->execute([$usuario, $nombre, $apellido, $email, $clave, $telefono]);

        $_SESSION['exito'] = "Registro exitoso, ya podés iniciar sesión";
       header("location: /huellas-perdidas/acciones/login.php");
        die();

    } catch (Exception $e) {
        $_SESSION['error'] = "El email ya está registrado";
        header("location: /huellas-perdidas/php/registro.php");
        die();
    }
}
?>