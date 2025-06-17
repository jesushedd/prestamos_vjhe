<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sistema de Prestamos - Home</title>
    <?php
    require $CONFIG . 'links.php';
    ?>

</head>

<body>
    <?php
    require $HEADER;
    ?>
    <main>


        <h2>Administrar Usuarios</h2>
        <section class="add-actualizar">
            <script src="<?= ROOT_ROUTE . 'estatico/js/edicion.js' ?>" defer></script>

            <div class="container">
                <div class="row">

                    <!-- Formulario: Registrar Nuevo Usuario -->
                    <div class="card mb-4 col">
                        <div class="card-header bg-primary text-white">
                            Registrar Nuevo Usuario
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control " id="nombre_usuario"
                                        name="nombre_usuario" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password"
                                        name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombres</label>
                                    <input type="text" class="form-control " id="nombre"
                                        name="nombre" required>
                                </div>

                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellido"
                                        name="apellido" required>
                                </div>

                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control " id="dni"
                                        name="dni" >
                                </div>

                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo</label>
                                    <select class="form-select" id="tipo" name="tipo"
                                        required>
                                        <?php foreach ($TIPOS as $tipo): ?>
                                            <option value="<?= htmlspecialchars($tipo->id) ?>">
                                                <?= htmlspecialchars($tipo->nombre_tipo) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <input class="btn btn-success" type="submit" value="Guardar Usuario" placeholder="Guardar Usuario" name="operacion">
                                
                            </form>
                        </div>
                    </div>
                    <!-- Formulario: Editar Usuario -->
                    <div class="card mb-4 col formulario-editar">
                        <div class="card-header bg-warning text-dark">
                            Editar Usuario
                        </div>
                        <div class="card-body">

                            <form method="POST">
                                <input type="hidden" name="editando_id" id="editando_id" value="">

                                <div class="mb-3">
                                    <label for="editando_nombre_usuario" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control editando" id="editando_nombre_usuario"
                                        name="editando_nombre_usuario" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editando_nombre" class="form-label">Nombres</label>
                                    <input type="text" class="form-control editando" id="editando_nombre"
                                        name="editando_nombre" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editando_apellido" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control editando" id="editando_apellido"
                                        name="editando_apellido" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editando_dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control editando" id="editando_dni"
                                        name="editando_dni" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="editando_tipo" class="form-label">Tipo</label>
                                    <select class="form-select editando" id="editando_tipo" name="editando_tipo"
                                        required>
                                        <?php foreach ($TIPOS as $tipo): ?>
                                            <option value="<?= htmlspecialchars($tipo->nombre_tipo) ?>">
                                                <?= htmlspecialchars($tipo->nombre_tipo) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                 <input class="btn btn-warning" type="submit" value="Actualizar Usuario" placeholder="Guardar Usuario" name="operacion">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Nombres</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Tipo de Usuario</th>
                </tr>
            </thead>
            <tbody class="clase-1">
                <?php foreach ($USUARIOS as $usuario): ?>
                    <tr>

                        <td><?= htmlspecialchars($usuario->id) ?></td>
                        <td class="editable"><?= htmlspecialchars($usuario->nombre_usuario) ?></td>
                        <td class="editable"><?= htmlspecialchars($usuario->nombre) ?></td>
                        <td class="editable"><?= htmlspecialchars($usuario->apellido) ?></td>
                        <td class="editable"><?= htmlspecialchars($usuario->dni) ?></td>
                        <td class="editable"><?= htmlspecialchars($usuario->tipo_usuario->nombre_tipo) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>





</body>

</html>