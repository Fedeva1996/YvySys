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
    <title>Planes de Clase</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function() {
            //buscar
            $('#formBuscarPlanClase').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaPlanesClase').html(response);
                    }
                });
            });
            //agregar
            $('#formAgregarPlanClase').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarPlanClase')[0].reset();
                        $('#sweetAlerts').html(response);
                        setTimeout(function() {
                            location.reload(true);
                        }, 1500);
                    }
                });
            });
            // Agregar nuevo detalle
            $('#formAgregarPlanClaseDet').submit(function(e) {
                var keep = $('#keep').find(":selected").val();
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarPlanClaseDet')[0].reset();
                        $("#keep").val(keep).change();
                        $('#sweetAlerts').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var idCab = $(this).closest('tr').find('.idCab').text();
                var idPro = $(this).closest('tr').find('.idProc').text();
                var competencia = $(this).closest('tr').find('.competencia').text();
                var indicadores = $(this).closest('tr').find('.indicadores').text();
                var contenido = $(this).closest('tr').find('.contenido').text();
                var actividad = $(this).closest('tr').find('.actividad').text();

                $('#editId').val(id);
                $('#editIdCab').val(idCab);
                $('#editIdPro').val(idPro);
                $('#editCompetencia').val(competencia);
                $('#editIndicadores').val(indicadores);
                $('#editContenido').val(contenido);
                $('#editActividad').val(actividad);
            });

            $('#formEditarPlanClase').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaPlanesClase').html(response);
                        $('#sweetAlerts').html(response);
                    },
                });
            });
            // Editar
            $(document).on('click', '.btn-editar-cab', function() {
                var id = $(this).closest('tr').find('.idCab').text();
                var id_materia = $(this).closest('tr').find('.id_materia').text();
                var fecha_ini = $(this).closest('tr').find('.fecha_ini').text();
                var fecha_fin = $(this).closest('tr').find('.fecha_fin').text();

                $('#editIdCab').val(id);
                $('#editMateria').val(id_materia).change();
                $('#editFechaIni').val(fecha_ini);
                $('#editFechaFin').val(fecha_fin);
            });

            $('#formEditarPlanClaseCab').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
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
                                url: 'funciones/planClase.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadAlumnos();
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
                function cargarPagina(pagina, curso) {
                    $.ajax({
                        url: 'funciones/pensum.php',
                        type: 'POST',
                        data: {
                            action: 'buscarPlanClase',
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
        <h2>Planes de clase</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarFecha'> <i class="bi bi-search"></i> Buscar</button>
        </div>
        <!-- Formulario para buscar por curso -->
        <div class="modal fade" id="modalBuscarFecha" tabindex="-1" aria-labelledby="modalBuscarFechaLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="formBuscarPlanClase">
                            <input type="hidden" name="action" value="buscarPlanClase">
                            <input type="hidden" name="pagina" value="1">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="fecha de la clase">Fecha</label>
                                        <input class="input-group-text w-100" type="date" name="fecha_p">
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
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar plan de clase</button>
            &nbsp;&nbsp;
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarDetalle'> <i class="bi bi-person-add"></i> Agregar detalles</button>
        </div>
        <!-- Tabla -->
        <div id="tablaPlanesClase"></div>
    </div>
    <!-- Formulario para agregar cab-->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPlanClase">
                        <input class="input-group-text" type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Inicio</span>
                                    <input class="form-control" type="date" name="fecha_ini" required>
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
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="obs" placeholder="Observaciones">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Fin</span>
                                    <input class="form-control" type="date" name="fecha_fin" required>
                                </div>
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT 
                                        * 
                                        FROM 
                                        cronogramas
                                        JOIN convocatorias ON cronogramas.convocatoria_id = convocatorias.id_convocatoria";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<select class='input-group-text w-100' name='id_cronograma' required>";
                                        echo "<option selected disabled>Seleccione cronograma</option>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_cronograma'] . "'>" . $fila['actividad'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="docente_r" placeholder="Docente de reemplazo">
                                </div>
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
    <!-- Formulario para agregar det -->
    <div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-labelledby="modalAgregarDetalleLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarDetalleLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPlanClaseDet">
                        <input class="input-group-text" type="hidden" name="action" value="agregarDet">
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT 
                                    plan_clase_cab.id_plan_clase,
                                    plan_clase_cab.fecha_ini,
                                    plan_clase_cab.fecha_fin,
                                    materias.descri as materia 
                                    FROM plan_clase_cab
                                    JOIN materias ON plan_clase_cab.materia_id = materias.id_materia";
                            $resultado = pg_query($conn, $sql);
                            if (pg_num_rows($resultado) > 0) {
                                echo "<select id='keep' class='input-group-text w-100' name='id_plan_clase' required>";
                                echo "<option selected disabled>Seleccione cabecera</option>";
                                while ($fila = pg_fetch_assoc($resultado)) {
                                    echo "<option value='" . $fila['id_plan_clase'] . "'>" . $fila['materia'] . " | Desde " . $fila['fecha_ini'] . " al " . $fila['fecha_fin'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="procesoClase" placeholder="Proceso de clase" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" name="competencia" placeholder="Competencia" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" name="indicadores" placeholder="Indicadores" required></textarea>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" name="contenido" placeholder="Contenido" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" name="actividad" placeholder="Actividad" required></textarea>
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
    <!-- Formulario para editar plan clase-->
    <div class="modal fade" id="modalEditarCab" tabindex="-1" aria-labelledby="modalEditarCabLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarCabLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPlanClaseCab">
                        <input type="hidden" name="action" value="editarCab">
                        <input type="hidden" name="idCab" id="editIdCab">
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Inicio</span>
                                <input class="form-control" type="date" id="editFechaIni" name="fecha_ini" required>
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
                                    echo "<select class='input-group-text w-100'id='editMateria' name='id_materia' required>";
                                    echo "<option selected disabled>Seleccione materia</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_materia'] . "'>" . $fila['materia'] . " | " . $fila['curso'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Fin</span>
                                <input class="form-control" type="date" id="editFechaFin" name="fecha_fin" required>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar PlanClase</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPlanClase">
                        <input type="hidden" name="action" value="editarDet">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT 
                                    plan_clase_cab.id_plan_clase,
                                    plan_clase_cab.fecha_ini,
                                    plan_clase_cab.fecha_fin,
                                    materias.descri as materia 
                                    FROM plan_clase_cab
                                    JOIN materias ON plan_clase_cab.materia_id = materias.id_materia";
                            $resultado = pg_query($conn, $sql);
                            if (pg_num_rows($resultado) > 0) {
                                echo "<select id='editIdCab' class='input-group-text w-100' name='id_plan_clase' required>";
                                echo "<option selected disabled>Seleccione cabecera</option>";
                                while ($fila = pg_fetch_assoc($resultado)) {
                                    echo "<option value='" . $fila['id_plan_clase'] . "'>" . $fila['materia'] . " | Desde " . $fila['fecha_ini'] . " al " . $fila['fecha_fin'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" id="editIdPro" type="text" name="procesoClase" placeholder="Proceso de clase" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" id="editCompetencia" name="competencia" placeholder="Competencia" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" id="editIndicadores" name="indicadores" placeholder="Indicadores" required></textarea>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" id="editContenido" name="contenido" placeholder="Contenido" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" aria-label="With textarea" id="editActividad" name="actividad" placeholder="Actividad" required></textarea>
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
    </div>
    <div class="sweetAlerts" id="sweetAlerts"></div>

</body>

</html>