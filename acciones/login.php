<?php
include '../modelo/conexion.php';
include '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST['nombre_usuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $consulta = <<<SQL
    SELECT
    id_usuario,
    nombre,
    rol,
    clave
    FROM usuario
    WHERE nombre_usuario = ?
    AND email = ?
    SQL;

    $stmt = $conn->prepare($consulta);
    $stmt->execute([$usuario, $email]);

    $datos = $stmt->fetch();
    if($datos === false || !password_verify($clave, $datos['clave'])){
        $_SESSION['error'] = "el usuario o la clave son incorrectas";
        header("location: /huellas-perdidas/login.php");
        die();
    }

    //si llego aca tengo un login exitoso 
    unset($datos['clave']); // por seguridad no guardamos la clave en sesion
    $_SESSION['logueado'] = $datos;
    header("location: /huellas-perdidas/index.php");
    die();
}
?>
<?php 
    if( isset( $_SESSION['error'] ) ){
      echo "<p class='error'>$_SESSION[error]</p>";
      unset( $_SESSION['error'] );
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <form method="post" action="">
                        <h2 class="text-center">Login</h2>
                        <label for="nombre_usuario">Usuario</label>   
                        <input class="form-control mb-2" type="text" name="nombre_usuario" id="nombre_usuario"/>
                        <label for="email">email</label>
                        <input class="form-control mb-2" type="email" name="email" id="email"/>
                        <label for="clave">contraseña</label>
                        <input class="form-control mb-2" type="password" name="clave" id="clave"/>
                        <button class="btn btn-dark w-100">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>