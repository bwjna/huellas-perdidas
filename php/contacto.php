<?php include('header.php'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-dark text-white p-5 d-flex flex-column justify-content-center">
                        <h3>Información de Contacto</h3>
                        <p class="mt-3">¿Tienes dudas sobre cómo usar la plataforma o quieres reportar un problema técnico?</p>
                        <div class="mt-4">
                            <p><i class="fas fa-envelope me-3 text-warning"></i> soporte@huellasperdidas.com</p>
                            <p><i class="fas fa-phone me-3 text-warning"></i> +54 9 11 0000-0000</p>
                            <p><i class="fas fa-location-dot me-3 text-warning"></i> Buenos Aires, Argentina</p>
                        </div>
                    </div>
                    <div class="col-md-7 p-5">
                        <h2 class="mb-4">Envíanos un mensaje</h2>
                        <form action="../acciones/procesar_contacto.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" name="nombre_contacto" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email_contacto" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea name="mensaje_contacto" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-naranja w-100 p-3 fw-bold text-uppercase">Enviar Consulta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>