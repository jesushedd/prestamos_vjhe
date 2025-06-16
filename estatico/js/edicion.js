
const INPUTS_EDITABLES = document.querySelectorAll('form .editando');
const ID_A_EDITAR = null;



document.querySelector('tbody').addEventListener('click', function (event) {
    const filaClicada = event.target.closest('tr');

    if (!filaClicada) return;

    // Marcar la fila clicada y desmarcar las demás
    filaClicada.classList.add('table-warning');
    document.querySelectorAll('tbody .table-warning').forEach(tr => {
        if (tr !== filaClicada) {
            tr.classList.remove('table-warning');
        }
    });

    let celdas = filaClicada.querySelectorAll('.editable');
    console.log(celdas);


    // Llenar los inputs con los valores correspondientes
    for (let i = 0; i < celdas.length ; i++) {
        INPUTS_EDITABLES[i].value = celdas[i].innerText.trim();
    }

    // También llenar el input hidden del ID (columna 0)
    const id = filaClicada.children[0].innerText.trim();
    const inputId = document.getElementById('editando_id');
    if (inputId) {
        inputId.value = id;
    }
});



document.addEventListener('click', function (event) {
    const dentroDeTabla = event.target.closest('tbody');
    const dentroDeFormulario = event.target.closest('.formulario-editar');

    // Si no está ni en la tabla ni en el form de edición
    if (!dentroDeTabla && !dentroDeFormulario) {
        // Quitar la clase de todas las filas
        document.querySelectorAll('tbody .table-warning').forEach(tr => {
            tr.classList.remove('table-warning');
        });
    }
});

