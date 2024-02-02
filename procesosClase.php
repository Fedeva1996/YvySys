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
    <title>Proceso de clase</title>
    <?php include("head.php"); ?>
    <script>
        //buscar
        $(document).ready(function () {
            $('#formBuscarProcesoClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/procesoClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#tablaProcesoClase').html(response);
                    }
                });
            });
            // Agregar nuevo
            $('#formAgregarProcesoClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/procesoClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarProcesoClase')[0].reset();
                        $('#resultados').html(response);
                        setTimeout(function () {
                            location.reload(true);
                        }, 1500);
                    }
                });
            });
            // Agregar nuevo
            $('#formAgregarProcesoClaseDet').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/procesoClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarProcesoClaseDet')[0].reset();
                        $('#resultados').html(response);
                    }
                });
            });
            //autocompletar alumno
            $(document).ready(function () {
                $('#materia-input').keyup(function () {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/procesoClase.php',
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
                    var id = $(this).data('id-materia');
                    var id_curso = $(this).data('id-curso');

                    $('#materia-input').val(value);
                    $('#id-materia').val(id);
                    $('#id-curso').val(id_curso);
                    $('#suggestions').hide();
                });
            });
            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var idCab = $(this).closest('tr').find('.idCab').text();
                var idAl = $(this).closest('tr').find('.idAlumno').text();
                var fecha_e = $(this).closest('tr').find('.fecha_entrega').text();
                var puntaje = $(this).closest('tr').find('.puntaje').text();

                $('#editId').val(id);
                $('#editIdCab').val(idCab);
                $('#editIdAl').val(idAl);
                $('#editFechaEntrega').val(fecha_e);
                $('#editPuntaje').val(puntaje);
            });

            $('#formEditarProcesoClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/procesoClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#resultados').html(response);
                        $('#formBuscarProcesoClase').submit();
                    },
                });
            });

            //paginacion
            $(document).ready(function () {
                function cargarPagina(pagina, curso, fecha) {
                    $.ajax({
                        url: 'funciones/procesoClase.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
                            curso: curso,
                            fecha: fecha
                        },
                        success: function (response) {
                            $('#tablaProcesoClase').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');
                    var fecha = $(this).data('fecha');

                    cargarPagina(pagina, curso, fecha);
                });

            });
            // Cargar tabla
            function loadProcesosClase() {
                $.ajax({
                    url: 'funciones/procesoClase.php',
                    type: 'POST',
                    data: {
                        action: 'listar'
                    },

                    success: function (response) {
                        $('#tablaProcesoClase').html(response);
                    }
                });
            }
        });
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        ?>
    </div>
    <div class="container">
        <h2>Proceso de clase</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i
                    class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i
                    class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        <!-- Formulario para buscar por curso -->
        <div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel"
            aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="formBuscarProcesoClase">
                            <input type="hidden" name="action" value="buscarProcesoClase">
                            <input type="hidden" name="pagina" value="1">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <?php
                                        include 'db_connect.php';
                                        $sql = "SELECT * FROM cursos";
                                        $resultados = pg_query($conn, $sql);
                                        if (pg_num_rows($resultados) > 0) {
                                            echo "<label for='fecha'>Cursos</label>";
                                            echo "<select class='form-select  w-100'  name='id_curso' required>";
                                            echo "<option selected disabled>Seleccione curso</option>";
                                            while ($fila = pg_fetch_assoc($resultados)) {
                                                echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                            }
                                            echo "</select>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="fecha">Fecha</label>
                                        <input class="input-group-text w-100" type="date" name="fecha_p" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit"><i
                                            class="bi bi-search"></i> Buscar</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-calendar-plus"></i> Agregar proceso de Clase</button>
            &nbsp;&nbsp;
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarDetalle'> <i
                    class="bi bi-person-add"></i> Agregar entrega</button>
        </div>
        <!-- Tabla -->
        <div id="tablaProcesoClase"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar procesoClase</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarProcesoClase">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT 
                                        materias.id_materia,
                                        materias.descri as materia,
                                        cursos.id_curso,
                                        cursos.descri as curso
                                        FROM materias
                                        JOIN cursos ON materias.curso_id = cursos.id_curso";
                                    $resultados = pg_query($conn, $sql);
                                    if (pg_num_rows($resultados) > 0) {
                                        echo "<select class='form-select  w-100' name='id_materia' required>";
                                        echo "<option selected disabled>Seleccione materia</option>";
                                        while ($fila = pg_fetch_assoc($resultados)) {
                                            echo "<option value='" . $fila['id_materia'] . "'>" . $fila['materia'] . " | " . $fila['curso'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                    <input class="form-control" type="date" name="fecha_entrega" required>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="number" name="puntaje" placeholder="Puntaje">
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="descripcion"
                                        placeholder="Descripción">
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
    <!-- Formulario para agregar det -->
    <div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-labelledby="modalAgregarDetalleLabel"
        aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarDetalleLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarProcesoClaseDet">
                        <input class="input-group-text" type="hidden" name="action" value="agregarDet">
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT *
                                    FROM procesos_clase_cab
                                    JOIN materias ON procesos_clase_cab.materia_id = materias.id_materia";
                            $resultados = pg_query($conn, $sql);
                            if (pg_num_rows($resultados) > 0) {
                                echo "<select id='keep' class='input-group-text w-100' name='id_procesos_clase' required>";
                                echo "<option selected disabled>Seleccione cabecera</option>";
                                while ($fila = pg_fetch_assoc($resultados)) {
                                    echo "<option value='" . $fila['id_procesos_clase'] . "'>" . $fila['descri'] . " |  " . $fila['fecha_entrega'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT *
                                FROM inscripciones
                                JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno";
                            $resultados = pg_query($conn, $sql);
                            if (pg_num_rows($resultados) > 0) {
                                echo "<select class='form-select  w-100' name='id_inscripcion' required>";
                                echo "<option selected disabled>Seleccione cabecera</option>";
                                while ($fila = pg_fetch_assoc($resultados)) {
                                    echo "<option value='" . $fila['id_inscripcion'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                    <input class="form-control" type="date" name="fecha_entrega" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="number" name="puntaje"
                                        placeholder="Puntaje" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" type="submit">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Formulario para ir a reporte -->
    <div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalReporteLabel">Agregar inscripcion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="reportes/procesosClase.php" method="post" id="formReporteInscripcion">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM cursos";
                                    $resultados = pg_query($conn, $sql);
                                    if (pg_num_rows($resultados) > 0) {
                                        echo "<select class='form-select  w-100' id='id_curso' name='id_curso' required>";
                                        echo "<option selected disabled>Seleccione curso</option>";
                                        while ($fila = pg_fetch_assoc($resultados)) {
                                            echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Fecha</span>
                                    <input class="form-control" type="date" id="fecha" name="fecha" required>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Inscripción</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarProcesoClase">
                        <input type="hidden" name="action" value="editarDet">
                        <div class="row">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT *
                                    FROM procesos_clase_cab
                                    JOIN materias ON procesos_clase_cab.materia_id = materias.id_materia";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select id='editIdCab' class='input-group-text w-100' name='id_procesos_clase' required>";
                                    echo "<option selected disabled>Seleccione cabecera</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_procesos_clase'] . "'>" . $fila['descri'] . " |  " . $fila['fecha_entrega'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT *
                                FROM inscripciones
                                JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select id='editIdAl' class='input-group-text w-100' name='id_inscripcion' required>";
                                    echo "<option selected disabled>Seleccione cabecera</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_inscripcion'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="input-group flex-nowrap mb-3">
                                        <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                        <input class="form-control" id="editFechaEntrega" type="date"
                                            name="fecha_entrega" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" id="editPuntaje" type="number"
                                            name="puntaje" placeholder="Puntaje" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                    cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="resultados"></div>
</body>

</html>