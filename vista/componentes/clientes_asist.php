<main>


    <h2>Resumen - Clientes</h2>
    <section class="add-actualizar">
        <script src="<?= ROOT_ROUTE . 'estatico/js/edicion.js' ?>" defer></script>
        <div class="container">
            <div class="row">
                <!-- Informacion sobre los prestamos del cliente TODO -->
                <div class="card col">
                    <table class="table table-striped table-bordered">
                        <caption>Prestamos del Cliente</caption>
                        <thead class="table-dark">
                            <th>id</th>
                        </thead>

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
            </tr>
        </thead>
        <tbody>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



</main>