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

function get_prestamos() {
    if (ID_ENTIDAD_SELECCIONADA) {

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

document.querySelector('tbody.clase-1').addEventListener('click', function (event) {
    const filaClicada = event.target.closest('tr');

    if (!filaClicada) return;
    console.log("AJAX");

    get_prestamos();
});


document.addEventListener('click', function (event) {
    const dentroDeTabla = event.target.closest('tbody.clase-1');
    const dentroDeTabla2 = event.target.closest('tbody.clase-2');



    // Si no está en ninuga de las tablas
    if (!dentroDeTabla && !dentroDeTabla2) {
        // Quitar los prestamos
        TABLA_CLASE2.replaceChildren();
        
    }
});






//dibujar celdas