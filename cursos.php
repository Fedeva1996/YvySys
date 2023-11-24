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
    <title>Cursos</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadCursos();

            // Agregar nuevo
            $('#formAgregarCurso').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/curso.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarCurso')[0].reset();
                        loadCursos();
                        $('#sweetAlerts').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();
                var id_periodo = $(this).closest('tr').find('.id_periodo').text();
                var id_turno = $(this).closest('tr').find('.id_turno').text();
                var id_modalidad = $(this).closest('tr').find('.id_modalidad').text();
                var tipo = $(this).closest('tr').find('.tipo').text();
                var fecha_ini = $(this).closest('tr').find('.fecha_ini').text();
                var fecha_fin = $(this).closest('tr').find('.fecha_fin').text();
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
                $('#editFechaIni').val(fecha_ini);
                $('#editFechaFin').val(fecha_fin);
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();
            });

            $('#formEditarCurso').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/curso.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadCursos();
                        $('#sweetAlerts').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar', function() {
                // Obtener el ID del registro a eliminar
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la eliminación con el usuario
                swal.fire({
                        title: "Estás seguro de que deseas eliminar este registro?",
                        text: "Una vez eliminado no se podra recuperar!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Confirmar",
                        cancelButtonColor: '#d33',
                        cancelButtonText: "Cancelar"
                    })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            $.ajax({
                                url: 'funciones/curso.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadCursos();
                                    $('#sweetAlerts').html(response);
                                }
                            });
                        } else {
                            swal.fire("Se mantendra el registro!");
                        }
                    });
            });

            //paginacion
            $(document).ready(function() {
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/curso.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaCurso').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

                // Cargar la primera página al cargar el documento
                cargarPagina(1);

                // Buscar
                $('#formBuscarCurso').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/curso.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#tablaCurso').html(response);
                        }
                    });
                });
            });


        });
        // Cargar tabla
        function loadCursos() {
            $.ajax({
                url: 'funciones/curso.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function(response) {
                    $('#tablaCurso').html(response);
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
        <h2>Cursos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarCurso">
                <input type="hidden" name="action" value="buscar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadCursos()" type="reset"><i class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaCurso"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar curso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCurso">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM pensum_cab";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Pensum</label>";
                                        echo "<select class='input-group-text w-100'  name='id_pensum' required>";
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
                                        echo "<select class='input-group-text w-100'  name='id_periodo' required>";
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
                                        echo "<select class='input-group-text w-100'  name='id_turno' required>";
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
                                        echo "<select class='input-group-text w-100'  name='id_modalidad' required>";
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
                                    <label for="fecha">Fecha inicio</label>
                                    <input class="input-group-text w-100" type="date" name="fecha_ini" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="fecha">Tipo</label>
                                    <select class="input-group-text w-100" name="tipo" required>
                                        <option selected disabled>Seleccione tipo</option>
                                        <option value="Taller">Taller</option>
                                        <option value="Actualizacion">Actualización</option>
                                        <option value="Tecnicatura">Tecnicatura</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar curso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCurso">
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
                                        echo "<select class='input-group-text w-100'  name='id_pensum' required id='editPensum'>";
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
                                        echo "<select class='input-group-text w-100'  name='id_periodo' required id='editPeriodo'>";
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
                                        echo "<select class='input-group-text w-100'  name='id_turno' required id='editTurno>";
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
                                        echo "<select class='input-group-text w-100'  name='id_modalidad' required id='editModalidad'>";
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
                                    <label for="fecha">Fecha inicio</label>
                                    <input class="input-group-text w-100" type="date" name="fecha_ini" required id='editFechaIni'>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="fecha">Fecha fin</label>
                                    <input class="input-group-text w-100" type="date" name="fecha_fin" required id='editFechaFin'>
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
                                    <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                        <option value="S">Sin iniciar</option>
                                        <option value="C">En curso</option>
                                        <option value="F">Finalizado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="sweetAlerts" id="sweetAlerts"></div>
</body>

</html>