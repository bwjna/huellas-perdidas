<?php include '../php/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-nav">
                <a class="nav-link" href="/huellas-perdidas/index.php">Inicio</a>
            </div>
        </div>
    </nav>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if(isset($_SESSION['sugerencias'])): ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <small class="text-muted">Sugerencias:</small><br>
                    <?php foreach($_SESSION['sugerencias'] as $s): ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-1 mt-1 sugerencia">
                            <?= $s ?>
                        </button>
                    <?php endforeach; unset($_SESSION['sugerencias']); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-4">
                <h2 class="text-center mb-3">Registrarse</h2>
                <form method="post" action="/huellas-perdidas/acciones/registro.php">
                <label>Usuario</label>
                <input class="form-control mb-2" type="text" name="nombre_usuario" id="nombre_usuario" required/>
                <label>Nombre</label>
                <input class="form-control mb-2" type="text" name="nombre" required/>
                <label>Apellido</label>
                <input class="form-control mb-2" type="text" name="apellido" required/>
                <label>Teléfono</label>
                <input class="form-control mb-2" type="tel" name="telefono" required/>
                <label>Email</label>
                <input class="form-control mb-2" type="email" name="email" required/>
                <label>Contraseña</label>
                <input class="form-control mb-2" type="password" name="clave" required/>
                <label>Repetir contraseña</label>
                <input class="form-control mb-2" type="password" name="clave2" required/>
                    <button class="btn btn-dark w-100">Registrarse</button>
                </form>
                <p class="text-center mt-2">
                    ¿Ya tenés cuenta? <a href="/acciones/login.php">Iniciá sesión</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.querySelectorAll('.sugerencia').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('nombre_usuario').value = btn.textContent.trim();
        });
    });
    </script>
</body>
</html>