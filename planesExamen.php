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
    <title>Planes de Examen</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function() {
            //buscar
            $('#formBuscarPlanExamen').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaPlanesExamen').html(response);
                    }
                });
            });

            // Agregar nuevo
            $('#formAgregarPlanExamen').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarPlanExamen')[0].reset();
                        $('#resultado').html(response);
                        setTimeout(function() {
                            location.reload(true);
                        }, 1500);
                    }
                });
            });
            // Agregar nuevo det
            $('#formAgregarPlanExamenDet').submit(function(e) {
                var keep = $('#keep').find(":selected").val();
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarPlanExamenDet')[0].reset();
                        $("#keep").val(keep).change();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var idCab = $(this).closest('tr').find('.id_cab').text();
                var idAl = $(this).closest('tr').find('.id_alumno').text();
                var puntaje = $(this).closest('tr').find('.puntaje_hecho').text();

                $('#editId').val(id);
                $('#editIdCab').val(idCab).change();
                $('#editIdAl').val(idAl).change();
                $('#editPuntaje').val(puntaje);
            });

            $('#formEditarPlanExamen').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadPlanesExamen();
                        $('#resultado').html(response);
                    },
                });
            });
            // Editar
            $(document).on('click', '.btn-editar-cab', function() {
                var id = $(this).closest('tr').find('.idCab').text();
                var id_materia = $(this).closest('tr').find('.id_materia').text();
                var fecha = $(this).closest('tr').find('.fecha').text();
                var Recuperatorio = $(this).closest('tr').find('.recuperatorio').text();
                var puntaje = $(this).closest('tr').find('.puntaje').text();

                $('#editIdCab').val(id);
                $('#editMateria').val(id_materia);
                $('#editFecha').val(fecha);
                $('#editRecuperatorio').val(Recuperatorio);
                $('#editPuntaje').val(puntaje);
            });

            $('#formEditarPlanExamenCab').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#resultado').html(response);
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
                                url: 'funciones/planExamen.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadAlumnos();
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
            $(document).ready(function() {
                function cargarPagina(pagina, curso) {
                    $.ajax({
                        url: 'funciones/pensum.php',
                        type: 'POST',
                        data: {
                            action: 'buscarPlanExamen',
                            curso: curso,
                        },
                        success: function(response) {
                            $('#tablaPensums').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');

                    cargarPagina(pagina, curso);
                });

            });
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
        <h2>Planes de Examen</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarFecha'> <i class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        <!-- Formulario para buscar por curso -->
        <div class="modal fade" id="modalBuscarFecha" tabindex="-1" aria-labelledby="modalBuscarFechaLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="formBuscarPlanExamen">
                            <input type="hidden" name="action" value="buscarPlanExamen">
                            <input type="hidden" name="pagina" value="1">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="fecha">Fecha</label>
                                        <input class="input-group-text w-100" type="date" name="fecha_p">
                                    </div>
                                    <div class="mb-3">
                                        <?php
                                        include 'db_connect.php';
                                        $sql = "SELECT * FROM materias";
                                        $resultado = pg_query($conn, $sql);
                                        if (pg_num_rows($resultado) > 0) {
                                            echo "<label for='materia'>Materias</label>";
                                            echo "<select class='input-group-text w-100'  name='materia' required>";
                                            echo "<option selected disabled>Seleccione materia</option>";
                                            while ($fila = pg_fetch_assoc($resultado)) {
                                                echo "<option value='" . $fila['id_materia'] . "'>" . $fila['descri'] . "</option>";
                                            }
                                            echo "</select>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit"><i class="bi bi-search"></i> Buscar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar examen</button>
            &nbsp;&nbsp;
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarDetalle'> <i class="bi bi-person-add"></i> Agregar puntajes</button>
        </div>
        <!-- Tabla -->
        <div id="tablaPlanesExamen"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPlanExamen">
                        <input class="input-group-text" type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT *
                                        FROM examen";
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select class='input-group-text w-100' name='id_examen' required>";
                                    echo "<option selected disabled>Seleccione examen</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_examen'] . "'>" . $fila['directorio'] . " |  " . $fila['tipo'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
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
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select class='input-group-text w-100' name='id_materia' required>";
                                    echo "<option selected disabled>Seleccione materia</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_materia'] . "'>" . $fila['materia'] . " | " . $fila['curso'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Fecha</span>
                                    <input class="form-control" type="date" name="fecha" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Recuperatorio</span>
                                    <input class="form-control" type="date" name="fecha_recuperatorio" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="puntaje" placeholder="Puntaje" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="obs" placeholder="Observaciones">
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
    <!-- Formulario para agregar det -->
    <div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-labelledby="modalAgregarLabelDet" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabelDet">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPlanExamenDet">
                        <input class="input-group-text" type="hidden" name="action" value="agregarDet">
                        <div class="row">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT
                                *
                                FROM
                                plan_examen_cab
                                JOIN materias ON plan_examen_cab.materia_id = materias.id_materia";
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select id='keep' class='input-group-text w-100' name='id_plan_examen' required>";
                                    echo "<option selected disabled>Seleccione cabecera</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_plan_examen'] . "'>" . $fila['descri'] . " | Examen: " . $fila['fecha'] . " | Recuperatorio: " . $fila['recuperatorio'] . "</option>";
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
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select id='keep' class='input-group-text w-100' name='id_inscripcion' required>";
                                    echo "<option selected disabled>Seleccione alumno</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_inscripcion'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="puntaje" placeholder="Puntaje hecho" required>
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
    <!-- Formulario para ir a reporte -->
    <div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalReporteLabel">Agregar inscripcion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="reportes/planesExamen.php" method="post" id="formReporteInscripcion">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM materias";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<select class='input-group-text w-100' id='id_materia' name='id_materia' required>";
                                        echo "<option selected disabled>Seleccione materia</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_materia'] . "'>" . $fila['descri'] . "</option>";
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
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Formulario para editar plan examen-->
    <div class="modal fade" id="modalEditarCab" tabindex="-1" aria-labelledby="modalEditarCabLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarCabLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPlanExamenCab">
                        <input type="hidden" name="action" value="editarCab">
                        <input type="hidden" name="idCab" id="editIdCab">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM materias";
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select class='input-group-text w-100' id='editMateria' name='id_materia' required>";
                                    echo "<option selected disabled>Seleccione materia</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_materia'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Fecha</span>
                                <input class="form-control" type="date" id="editFecha" name="fecha" required>
                            </div>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Recuperatorio</span>
                                <input class="form-control" type="date" id="editRecuperatorio" name="recuperatorio" required>
                            </div>
                            <div class="input-group flex-nowrap mb-3">
                                <input class="form-control" type="Text" id="editPuntaje" name="puntaje" placeholder="Puntaje" required>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar PlanExamen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPlanExamen">
                        <input type="hidden" name="action" value="editarDet">
                        <input type="hidden" name="id" id="editId">
                        <div class="row">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT
                                *
                                FROM
                                plan_examen_cab
                                JOIN materias ON plan_examen_cab.materia_id = materias.id_materia";
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select id='editIdCab' class='input-group-text w-100' name='id_plan_examen' required>";
                                    echo "<option selected disabled>Seleccione cabecera</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_plan_examen'] . "'>" . $fila['descri'] . " | Examen: " . $fila['fecha'] . " | Recuperatorio: " . $fila['recuperatorio'] . "</option>";
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
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select id='editIdAl' class='input-group-text w-100' name='id_inscripcion' required>";
                                    echo "<option selected disabled>Seleccione alumno</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_inscripcion'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" id='editPuntaje' name="puntaje" placeholder="Puntaje hecho" required>
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
    </div>
    <div id="resultado"></div>

</body>

</html>