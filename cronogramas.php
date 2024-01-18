<!DOCTYPE html>
<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Redirige al formulario de inicio de sesión
    exit();
} else {
    if ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2) {
        header('Location: dashboard.php'); // Redirige al dashboard
        exit();
    }
}
?>
<html>

<head>
    <title>Cronogramas</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadCronogramas();

            // Agregar nuevo
            $('#formAgregarCronograma').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarCronograma')[0].reset();
                        loadCronogramas();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();
                var id_periodo = $(this).closest('tr').find('.id_periodo').text();
                var id_turno = $(this).closest('tr').find('.id_turno').text();
                var id_modalidad = $(this).closest('tr').find('.id_modalidad').text();
                var tipo = $(this).closest('tr').find('.tipo').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editPensum').val(id_pensum);
                $("select.editPensum selected").val(id_pensum).change();
                $('#editTurno').val(id_turno);
                $("select.editTurno selected").val(id_turno).change();
                $('#editModalidad').val(id_modalidad);
                $("select.editModalidad selected").val(id_modalidad).change();
                $('#editPeriodo').val(id_periodo);
                $("select.editPeriodo selected").val(id_periodo).change();
                $('#editTipo').val(tipo);
                $("select.editTipo selected").val(tipo).change();
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();
            });

            $('#formEditarCronograma').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadCronogramas();
                        $('#resultado').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar', function () {
                // Obtener el ID del registro a eliminar
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la eliminación con el usuario
                swal.fire({
                    title: "Estás seguro de que deseas eliminar este registro?",
                    text: "Una vez eliminado no se podra recuperar!",
                    icon: "warning",
                    showCancelButton: true,
                    background: "#212529",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Confirmar",
                    cancelButtonColor: '#6e7881',
                    cancelButtonText: "Cancelar"
                })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            $.ajax({
                                url: 'funciones/cronograma.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function (response) {
                                    loadCronogramas();
                                    $('#resultado').html(response);
                                }
                            });
                        } else {
                            swal.fire({
                                title: "Se mantendra el registro!",
                                background: "#212529"
                            })
                        }
                    });
            });

            //paginacion
            function cargarPagina(pagina) {
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: {
                        action: 'listar',
                        pagina: pagina
                    },
                    success: function (response) {
                        $('#tablaCronograma').html(response);
                    }
                });
            }
            $(document).on('click', '.btn-pagina', function () {
                var pagina = $(this).data('pagina');
                cargarPagina(pagina);
            });

            // Cargar la primera página al cargar el documento
            cargarPagina(1);

            // Buscar
            $('#formBuscarCronograma').keyup(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#tablaCronograma').html(response);
                    }
                });
            });

            //autocompletar
            $('#modulo-input').keyup(function () {
                var query = $(this).val();

                if (query !== '') {
                    $.ajax({
                        url: 'funciones/cronograma.php',
                        method: 'POST',
                        data: {
                            query: query,
                            action: 'autocompletar'
                        },
                        success: function (response) {
                            $('#suggestions').html(response).show();
                        }
                    });
                } else {
                    $('#suggestions').hide();
                }
            });

            $(document).on('click', '.suggest-element', function () {
                var value = $(this).text();
                var id = $(this).data('id-modulo');
                $('#modulo-input').val(value);
                $('#id').val(id);
                $('#suggestions').hide();
            });
        });
        const agregarFila = () => {
            document.getElementById('tablaEventos').insertRow(-1).innerHTML =
                '<td><input class="form-control form-control-sm" type="text" id="modulo-input" placeholder="Nombre modulo" autocomplete="off" required><input type="hidden" id="id" name="id"><div id="suggestions"></div></td><td><input class="form-control form-control-sm" type="text" ></td><td><input class="form-control form-control-sm" type="date"></td><td><input class="form-control form-control-sm" type="date"></td>'
        }

        const eliminarFila = () => {
            const table = document.getElementById('tablaEventos')
            const rowCount = table.rows.length

            if (rowCount <= 1)
                alert('No se puede eliminar el encabezado')
            else
                table.deleteRow(rowCount - 1)
        }
        // Cargar tabla
        function loadCronogramas() {
            $.ajax({
                url: 'funciones/cronograma.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function (response) {
                    $('#tablaCronograma').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        ?>
    </div>
    <div class="container">
        <h2>Cronogramas</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarCronograma">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadCronogramas()" type="reset"><i
                        class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultado"></div>

        <!-- Tabla -->
        <div id="tablaCronograma"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar cronograma</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCronograma">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for='curso'>Curso</label>
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM cursos";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<select class='form-select  w-100'  name='id_curso' required>";
                                        echo "<option selected disabled>Seleccione curso</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table table-dark" id="tablaEventos">
                                    <thead>
                                        <tr>
                                            <th scope="col">Evento</th>
                                            <th scope="col">Modulo</th>
                                            <th scope="col">Fecha inicio</th>
                                            <th scope="col">Fecha fin</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mr-2" onclick="agregarFila()">Agregar
                                        Fila</button>
                                    <button type="button" class="btn btn-danger" onclick="eliminarFila()">Eliminar
                                        Fila</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar cronograma</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCronograma">
                        <div class="row">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" id="editId">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM pensum_cab";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Pensum</label>";
                                        echo "<select class='form-select  w-100'  name='id_pensum' required id='editPensum'>";
                                        echo "<option selected disabled>Seleccione pensum</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM periodo";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Periodo</label>";
                                        echo "<select class='form-select  w-100'  name='id_periodo' required id='editPeriodo'>";
                                        echo "<option selected disabled>Seleccione periodo</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_periodo'] . "'>" . $fila['ano'] . " | " . $fila['descripcion'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM turno";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Turno</label>";
                                        echo "<select class='form-select  w-100'  name='id_turno' required id='editTurno>";
                                        echo "<option selected disabled>Seleccione turno</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_turno'] . "'>" . $fila['descri'] . " | " . $fila['horario'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM modalidad";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Modalidad</label>";
                                        echo "<select class='form-select  w-100'  name='id_modalidad' required id='editModalidad'>";
                                        echo "<option selected disabled>Seleccione modalidad</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_modalidad'] . "'>" . $fila['descri'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="fecha">Tipo</label>
                                    <select class="input-group-text w-100" name="tipo" required id='editTipo'>
                                        <option selected disabled>Seleccione tipo</option>
                                        <option value="Taller">Taller</option>
                                        <option value="Actualizacion">Actualización</option>
                                        <option value="Tecnicatura">Tecnicatura</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="fecha">Estado</label>
                                    <select class="input-group-text w-100" class="editEstado" id="editEstado"
                                        name="estado">
                                        <option value="S">Sin iniciar</option>
                                        <option value="C">En curso</option>
                                        <option value="F">Finalizado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>