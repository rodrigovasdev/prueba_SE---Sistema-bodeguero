<link rel="stylesheet" href="estilos/estilos.css">

<h1>Sistema registro de bodegas</h1>

<form id="formNuevaBodega" onsubmit="event.preventDefault(); gestionaBodega(this);">
    <h2 id="tituloBodega">Nueva bodega</h2>
    <input type="hidden" value="0" id="id_bodega_oculto" name="id">
    <label for="codigo">Código:</label>
    <input type="text" id="codigo" name="codigo" required maxlength="5">
    <br>
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required maxlength="100">
    <br>
    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" required>
    <br>
    <label for="dotacion">Dotación:</label>
    <input type="number" id="dotacion" name="dotacion" required>
    <br>
    <label for="estado">Estado:</label>
    <select id="estado" name="estado" disabled required>
        <option value="true">Activada</option>
        <option value="false">Desactivada</option>
    </select>
    <br>
    <label for="encargados">Encargados:</label>
    <br>
    <select id="encargados" name="encargados[]" multiple required style="width: 100%; min-height: 80px;">
        <option disabled>Cargando encargados...</option>
    </select>
    <br>
    <br>
    <button type="submit">Guardar</button>
</form>

<h2>Lista de bodegas</h2>

<table id="tablaBodegas">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Dotación</th>
            <th>Encargados</th>
            <th>Fecha creación</th>
            <th><label for="filtroEstado">Estado</label>
                <select id="filtroEstado" name="filtroEstado" onchange="cargarBodegas(this.value)">
                    <option value="">Todos</option> 
                    <option value="true">Activada</option>
                    <option value="false">Desactivada</option>
                </select>
            </th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="cuerpoTablaBodegas">
        <tr><td colspan="8">Cargando...</td></tr>
    </tbody>
</table>

<script src="js/controlador.js"></script>