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
    <title>Asistencias</title>
    <?php include("head.php"); ?>
    <script>
        //buscar
        $(document).ready(function() {
            $('#formBuscarAsistencia').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaAsistencia').html(response);
                    }
                });
            });
            // Agregar nuevo
            $('#formAgregarAsistencia').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarAsistencia')[0].reset();
                        $('#resultado').html(response);
                    }
                });
            });
            //autocompletar alumno
            $(document).ready(function() {
                $('#planClase-input').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/asistencia.php',
                            method: 'POST',
                            data: {
                                query: query,
                                action: 'autocompletar'
                            },
                            success: function(response) {
                                $('#suggestions').html(response).show();
                            }
                        });
                    } else {
                        $('#suggestions').hide();
                    }
                });

                $(document).on('click', '.suggest-element', function() {
                    var value = $(this).text();
                    var id = $(this).data('id-materia');
                    var id_curso = $(this).data('id-curso');
                    $('#planClase-input').val(value);
                    $('#id-plan').val(id);
                    $('#id-materia').val(id_curso);
                    $('#suggestions').hide();
                });
            });
            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editEstado').val(estado);
                document.getElementById("editEstado").value = estado;

                // Asignar el valor de action al formulario de edición
                $('#formEditarAsistencia').find('input[name="action"]').val('editar');
            });

            $('#formEditarAsistencia').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#resultado').html(response);
                        $('#formBuscarAsistencia').submit();
                    },
                });
            });

            //paginacion
            $(document).ready(function() {
                function cargarPagina(pagina, curso, fecha) {
                    $.ajax({
                        url: 'funciones/asistencia.php',
                        type: 'POST',
                        data: {
                            action: 'buscarAsistencia',
                            pagina: pagina,
                            curso: curso,
                            fecha: fecha
                        },
                        success: function(response) {
                            $('#tablaAsistencia').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');
                    var fecha = $(this).data('fecha');

                    cargarPagina(pagina, curso, fecha);
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
        <h2>Asistencias</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i class="bi bi-search"></i> Buscar</button>
        </div>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-calendar-plus"></i> Agregar asistencia de hoy</button>
        </div>
        <!-- Tabla -->
        <div id="tablaAsistencia"></div>
    </div>
    <!-- Formulario para buscar por curso -->
    <div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="formBuscarAsistencia">
                        <input type="hidden" name="action" value="buscarAsistencia">
                        <input type="hidden" name="pagina" value="1">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM cursos";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<label for='fecha'>Cursos</label>";
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
                            <div class="col">
                                <div class="mb-3">
                                    <label for="fecha">Fecha</label>
                                    <input class="input-group-text w-100" type="date" name="fecha_p" required>
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
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarAsistencia">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" id="planClase-input" placeholder="Nombre de materia, curso o docente" autocomplete="off" required>
                                    <input type="hidden" id="id-plan" name="id-plan">
                                    <div id="suggestions"></div>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" readonly type="datetime" id="fecha" name="fecha" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" name="asistenciaD" id="asistenciaD" checked>
                                    <label class="form-check-label" for="asistenciaD">
                                        Docente asistió
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" id="obs" name="obs" placeholder="Observaciones">
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Inscripción</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarAsistencia">
                        <input type="hidden" name="action" value="editar">
                        <div class="row">
                            <input type="hidden" name="id" id="editId">
                            <div class="col">
                                <div class="mb-3">
                                    <select class="editEstado input-group-text w-100" id="editEstado" name="estado" required>
                                        <option selected disabled>Seleccione asistencia</option>
                                        <option value="1">Presente</option>
                                        <option value="0">Ausente</option>
                                    </select>
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
    </div>
    <div id="resultado"></div>
</body>

</html>