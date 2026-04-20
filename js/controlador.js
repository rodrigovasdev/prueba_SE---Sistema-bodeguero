/**
 * El siguiente archivo JavaScript se encarga de manipular el DOM en conjunto
 * de hacer las llamadas a archivos PHP para interactuar con la base de datos,
 * con funciones como: cargarBodegas, cargarEncargados, modificarBodega,
 * gestionaBodega y eliminarBodega.
 */

async function cargarBodegas(filtro = '') {
    const tbody = document.getElementById('cuerpoTablaBodegas');
    tbody.innerHTML = '<tr><td colspan="8">Cargando...</td></tr>';

    try {
        const params = new URLSearchParams({ filtro });
        const response = await fetch('php/cargarBodegas.php?' + params.toString());
        const json = await response.json();

        if (!json.success) {
            tbody.innerHTML = `<tr><td colspan="8">Error: ${json.message}</td></tr>`;
            return;
        }

        const bodegas = json.data;

        if (bodegas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8"><i>No se encontraron bodegas.</i></td></tr>';
            return;
        }

        tbody.innerHTML = bodegas.map(b => {
            const estado = b.estado_bool ? 'Activa' : 'Desactivada';
            const idsJson = JSON.stringify(b.ids_encargados).replace(/"/g, '&quot;');
            return `
            <tr id="bodega-${b.id}">
                <td>${b.codigo}</td>
                <td>${b.nombre}</td>
                <td>${b.direccion}</td>
                <td>${b.dotacion}</td>
                <td>${b.encargados}</td>
                <td>${b.fecha_crea ? new Date(b.fecha_crea).toLocaleDateString('es-CL') : '-'}</td>
                <td>${estado}</td>
                <td>
                    <button type="button"
                        class="btn-modificar"
                        onclick="modificarBodega('${b.id}', '${escapar(b.codigo)}', '${escapar(b.nombre)}', '${escapar(b.direccion)}', '${b.dotacion}', '${b.estado}', '${idsJson}')">Modificar</button>
                    <button type="button"
                        class="btn-eliminar"
                        onclick="eliminarBodega(${b.id})">Eliminar</button>
                </td>
            </tr>`;
        }).join('');
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="8">Error en la solicitud.</td></tr>';
        console.error(error);
    }
}

function escapar(str) {
    return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

async function cargarEncargados(seleccionados = []) {
    const select = document.getElementById('encargados');
    select.innerHTML = '<option disabled>Cargando...</option>';

    try {
        const response = await fetch('php/cargarEncargados.php');
        const encargados = await response.json();

        if (!encargados.success) {
            select.innerHTML = '<option disabled>Error al cargar encargados</option>';
            return;
        }

        const selStrings = seleccionados.map(String);
        select.innerHTML = encargados.data.map(e => {
            const sel = selStrings.includes(String(e.id)) ? 'selected' : '';
            return `<option value="${e.id}" ${sel}>${e.run} - ${e.nombre} ${e.apellido1}</option>`;
        }).join('');
    } catch (error) {
        select.innerHTML = '<option disabled>Error en la solicitud</option>';
        console.error(error);
    }
}

function modificarBodega(id, codigo, nombre, direccion, dotacion, estado, encargadosJsonStr) {
    document.getElementById('tituloBodega').textContent = 'Modificar bodega: ' + codigo + ' - ' + nombre;
    document.getElementById('estado').disabled = false;
    document.getElementById('codigo').readOnly = true;
    document.getElementById('id_bodega_oculto').value = id;

    document.getElementById('codigo').value = codigo;
    document.getElementById('nombre').value = nombre;
    document.getElementById('direccion').value = direccion;
    document.getElementById('dotacion').value = dotacion;

    const esActivo = (estado === 'true' || estado === 't' || estado === '1' || estado === true);
    document.getElementById('estado').value = esActivo ? 'true' : 'false';

    let encargadosAsignados = [];
    try { encargadosAsignados = JSON.parse(encargadosJsonStr); } catch (e) { }
    cargarEncargados(encargadosAsignados);

    document.querySelector('#formNuevaBodega button[type="submit"]').textContent = 'Actualizar';
}

async function gestionaBodega(form) {
    try {
        const formData = new FormData(form);
        const response = await fetch('php/gestionaBodega.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            form.reset();
            document.getElementById('estado').disabled = true;
            document.getElementById('codigo').readOnly = false;
            document.getElementById('id_bodega_oculto').value = '0';
            document.getElementById('tituloBodega').textContent = 'Nueva bodega';
            document.querySelector('#formNuevaBodega button[type="submit"]').textContent = 'Guardar';
            cargarBodegas(document.getElementById('filtroEstado').value);
            cargarEncargados();
        } else {
            alert('Error al guardar.');
        }
    } catch (error) {
        alert('Hubo un error en la solicitud.');
        console.error(error);
    }
}

async function eliminarBodega(id) {
    if (!confirm('¿Seguro que deseas eliminar esta bodega?\nEsta acción NO se puede deshacer.')) {
        return;
    }
    try {
        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch('php/eliminarBodega.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            cargarBodegas(document.getElementById('filtroEstado').value);
        } else {
            alert('Error al eliminar: ' + data.message);
        }
    } catch (error) {
        alert('Hubo un error en la solicitud.');
        console.error(error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    cargarBodegas();
    cargarEncargados();
});
