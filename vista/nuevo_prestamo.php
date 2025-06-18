<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Préstamo</title>
    <?php require_once $CONFIG . 'links.php ' ?>
</head>

<body>
    <?php require $HEADER; ?>
    <main>
        <h1>Nuevo Préstamo</h1>
        <h2>Cliente: <?= "$EL_CLIENTE->nombre $EL_CLIENTE->apellido" ?></h2>
        <section class="container">
            <form action="" method="post">
                <input type="text" hidden value="<?= $EL_CLIENTE->id ?>" name="id_cliente">
                <div class="mb-3">
                    <label for="monto" class="form-label">Monto</label>
                    <input type="number" class="form-control" id="monto" name="monto" min="1000" step="1" required>
                </div>
                <div class="mb-3">
                    <label for="plazo_interes" class="form-label">Plazo e Interés</label>

                    <select class="form-select" id="plazo_interes" name="plazos" required>

                        <?php foreach ($LOS_PLAZOS as $plazo): ?>
                            <option value="<?= $plazo->numero_plazos ?>">
                                <?= $plazo->numero_plazos ?> meses - <?= $plazo->tasa ?>%
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button id="cronograma" class="btn btn-success">Simular Cronograma de Pagos</button>
                <button type="submit" class="btn btn-success">Enviar</button>
            </form>
        </section>
        <section class="container">
            <table>
                <thead>
                    <tr>

                    </tr>
                </thead>
                <tbody class="clase-2">

                </tbody>
            </table>

        </section>
    </main>



</body>
<script>
    const TABLA_CLASE2 = document.querySelector('tbody.clase-2');
    const RUTA = '/prestamos-vjhe/prestamos';
    //cargar datos


    function rellenar_filas(array_json) {
        //limpiar tabla
        TABLA_CLASE2.replaceChildren();
        array_json.forEach(prestamo => {
            const fila = document.createElement('tr');

            // Crear y añadir celdas en orden
            const celda_id = document.createElement('td');
            celda_id.textContent = prestamo.id;
            fila.appendChild(celda_id);

            const celda_monto = document.createElement('td');
            celda_monto.textContent = prestamo.monto.toFixed(2); // con dos decimales
            fila.appendChild(celda_monto);

            const celda_fecha = document.createElement('td');
            // Asumimos formato ISO en item.fecha_inicio.date
            const fecha = new Date(prestamo.fecha_inicio.date);
            celda_fecha.textContent = fecha.toLocaleDateString(); // formato local
            fila.appendChild(celda_fecha);

            const celda_plazos = document.createElement('td');
            celda_plazos.textContent = prestamo.plazos;
            fila.appendChild(celda_plazos);

            const celda_interes = document.createElement('td');
            celda_interes.textContent = prestamo.interes_anual + '%';
            fila.appendChild(celda_interes);

            const celda_estado = document.createElement('td');
            celda_estado.textContent = prestamo.estado;
            fila.appendChild(celda_estado);

            TABLA_CLASE2.appendChild(fila);


        });



    }

    function get_cronograma(numero_plazos, monto) {
        if (monto >= 1000) {

            let form = new FormData();
            form.append('id_cliente', ID_ENTIDAD_SELECCIONADA);
            form.append('accion', 'listar');
            const response = fetch(
                RUTA,
                {
                    method: "POST",
                    body: form
                }
            ).then(function (response) {
                return response.json();
            })
                .then(rellenar_filas)
        }
    }

    document.querySelector('#cronograma').addEventListener('click', function (event) {
        event.preventDefault();
        
        const form = document.querySelector('form');
        const data = new FormData(form);
        data.append('accion', 'cronograma');

        if (data.get('monto') < 1000 || data.get('plazos') < 6) {
            return;
        }

        const response = fetch(
            RUTA,
            {
                method: 'POST',
                body: data
            }
        ).then(function (response) {
            return response.json()
        })
            .then(function (array_json) {
                console.log(array_json)//TODO añadir los datos a una tabla
            });

        //get_cronograma(numero_plazos, monto);
    });

</script>

</html>