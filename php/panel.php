<?php
include '../php/config.php';
include '../modelo/conexion.php';

validarAdmin(); // si no es admin lo redirige

$consulta = "SELECT id_usuario,nombre, apellido, email, rol, fecha_registro FROM usuario";
$stmt = $conn->prepare($consulta);
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="/huellas-perdidas/index.php">Inicio</a>
                
                <a class="nav-link active" href="#">Panel</a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href='/login/acciones/logout.php'>
                    Logout (<?php echo $_SESSION['logueado']['nombre']; ?>)
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-3">Usuarios registrados</h2>
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Fecha de registro</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= $u['nombre'] ?></td>
                    <td><?= $u['apellido'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['fecha_registro'] ?></td>
                    <td>
                        <span class="badge <?= $u['rol'] == 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                            <?= $u['rol'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>