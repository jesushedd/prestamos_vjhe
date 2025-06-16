
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar</title>
    <?php require_once $CONFIG . 'links.php ' ?>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h2 class="mb-4 text-center">Iniciar Sesión</h2>
        <form method="post" action="<?=  ROOT_ROUTE  . 'login'?>">
            <div class="mb-3">
                <label for="email" class="form-label">Nombre de  Usuario</label>
                <input type="text" name="nombre_usuario" class="form-control" id="email" placeholder="usuario123" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <small>¿No tienes cuenta? <a href="#">Regístrate</a></small>
        </div>
    </div>

</body>

</html>