<main>


    <h2>Administrar Clientes</h2>
    <section class="add-actualizar">
        <script src="<?= ROOT_ROUTE . 'estatico/js/edicion.js' ?>" defer></script>
        <script src="<?= ROOT_ROUTE . 'estatico/js/ajax.js' ?>" defer></script>
        <div class="container">
            <div class="row">
                <!-- Formulario: Registrar Nuevo Cliente -->
                <div class="card mb-4 col">
                    <div class="card-header bg-primary text-white">
                        Registrar Nuevo Cliente
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <input type="submit" class="btn btn-success" value="Guardar" name="accion">
                        </form>
                    </div>
                </div>
                <!-- Formulario: Editar Cliente -->
                <div class="card mb-4 col formulario-editar">
                    <div class="card-header bg-warning text-dark">
                        Editar Cliente
                    </div>
                    <div class="card-body">

                        <form method="POST">
                            <input type="hidden" name="editando_id" id="editando_id" value="">

                            <div class="mb-3">
                                <label for="editando_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control editando" id="editando_nombre"
                                    name="editando_nombre" required>
                            </div>

                            <div class="mb-3">
                                <label for="editando_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control editando" id="editando_apellido"
                                    name="editando_apellido" required>
                            </div>

                            <div class="mb-3">
                                <label for="editando_dni" class="form-label">DNI</label>
                                <input type="text" class="form-control editando" id="editando_dni" name="editando_dni"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="editando_email" class="form-label">Email</label>
                                <input type="email" class="form-control editando" id="editando_email"
                                    name="editando_email" required>
                            </div>



                            <div class="mb-3">
                                <label for="editando_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control editando" id="editando_direccion"
                                    name="editando_direccion" required>
                            </div>

                            <div class="mb-3">
                                <label for="editando_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control editando" id="editando_telefono"
                                    name="editando_telefono" required>
                            </div>

                            <input value="Actualizar" type="submit" class="btn btn-warning" name="accion">
                        </form>
                    </div>
                </div>
                <!-- Informacion sobre los prestamos del cliente TODO -->
                <div class="card col">
                    <caption>Prestamos del Cliente</caption>
                    <table class="table table-striped table-bordered">
                        
                        <thead class="table-dark">
                            <th>id</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Numero de Plazos</th>
                            <th>Interés Anual</th>
                            <th>Estado</th>
                        </thead>
                        <tbody class="clase-2">
                            
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Fecha de creación</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="clase-1">
            <?php foreach ($CLIENTES as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente->id) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->nombre) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->apellido) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->dni) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->email) ?></td>
                    <td><?= htmlspecialchars($cliente->str_fecha()) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->direccion) ?></td>
                    <td class="editable"><?= htmlspecialchars($cliente->telefono) ?></td>
                    <td><a class="btn btn-success " href=<?= ROOT_ROUTE . "prestamos/nuevo?id_cliente=$cliente->id" ?>>Nuevo Préstamo</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




</main>