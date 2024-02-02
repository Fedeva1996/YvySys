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
    <title>Calificaciones</title>
    <?php include("head.php"); ?>

    <script>
        //buscar
        $(document).ready(function() {
            $('#formBuscarCurso').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/calificacion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaCalificacion').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var alumno = $(this).closest('tr').find('.alumno').text();
                var puntaje_proceso = $(this).closest('tr').find('.puntaje_proceso').text();
                var puntaje_trabajo = $(this).closest('tr').find('.puntaje_trabajo').text();
                var puntaje_examen = $(this).closest('tr').find('.puntaje_examen').text();
                var calificacion = $(this).closest('tr').find('.calificacion').text();
                var paso = $(this).closest('tr').find('.paso').text();
                var obs = $(this).closest('tr').find('.obs').text();
                if (paso == 1) {
                    var color = "#81c784";
                } else {
                    var color = "#e57373";
                }
                $('#editId').val(id);
                $('#editNombre').val(alumno);
                $('#editProceso').val(puntaje_proceso);
                $('#editTrabajo').val(puntaje_trabajo);
                $('#editExamen').val(puntaje_examen);
                $('#editTotal').val(Number(puntaje_proceso) + Number(puntaje_trabajo) + Number(puntaje_examen));
                $('#editCalificacion').val(calificacion);
                $('#editPaso').val(paso);
                $('#selectPaso').val(paso);
                $("select.selectPaso selected").val(paso).change();
                changeColor(color);
                $('#editObs').val(obs);

                // Asignar el valor de action al formulario de edición
                $('#formEditarCalificacion').find('input[name="action"]').val('editar');
            });
            $('#formEditarCalificacion').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/calificacion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formBuscarCurso').submit();
                        $('#resultados').html(response);
                    },
                });
            });

            //paginacion
            $(document).ready(function() {
                function cargarPagina(pagina, curso, materia) {
                    $.ajax({
                        url: 'funciones/calificacion.php',
                        type: 'POST',
                        data: {
                            action: 'buscar',
                            pagina: pagina,
                            curso: curso,
                            materia: materia
                        },
                        success: function(response) {
                            $('#tablaCalificacion').html(response);
                        }
                    });
                }

                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');
                    var materia = $(this).data('materia');

                    cargarPagina(pagina, curso, materia);
                });

            });

            //actualiza select de materias
            $(document).ready(function() {
                $("#id_curso").change(function() {
                    var valorSelect1 = document.getElementById("id_curso").value;
                    $.ajax({
                        url: "funciones/calificacion.php",
                        type: "POST",
                        data: {
                            id_curso: valorSelect1,
                            action: "buscarCurso"
                        },
                        success: function(data) {
                            $("#id_materia").empty();
                            $("#id_materia").append(data);
                        }
                    });
                });
            });
            $(document).ready(function() {
                $(".input-number").on("input", function() {
                    var num1 = parseInt($("#editProceso").val()) || 0;
                    var num2 = parseInt($("#editTrabajo").val()) || 0;
                    var num3 = parseInt($("#editExamen").val()) || 0;

                    $.ajax({
                        type: "POST",
                        url: "funciones/calificacion.php",
                        dataType: 'json',
                        data: {
                            num1: num1,
                            num2: num2,
                            num3: num3,
                            action: "sumar"
                        },
                        success: function(data) {
                            $("#editTotal").val(data[0]);
                            $("#editCalificacion").val(data[1]);
                            $('#editPaso').val(data[2]);
                            $('#selectPaso').val(data[2]);
                            changeColor(data[3]);
                        }
                    });
                });
            });

            function changeColor(colorParam) {
                let color = colorParam;
                var optionElement = document.getElementById('selectPaso');
                optionElement.style.background = color;
            };
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
        <h2>Calificaciones</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i class="bi bi-search"></i> Buscar</button>
        </div>
        <!-- Tabla -->
        <div id="tablaCalificacion"></div>
    </div>
    <!-- Formulario para buscar por curso -->
    <div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="formBuscarCurso">
                        <input type="hidden" name="action" value="buscar">
                        <input type="hidden" name="pagina" value="1">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';

                                    // Consulta para llenar el primer select
                                    $consultaCurso = "SELECT * FROM cursos";
                                    $resultadoCurso = mysqli_query($conn, $consultaCurso);

                                    ?>
                                    <select class="input-group-text w-100" name='id_curso' id="id_curso">
                                        <option value="">Seleccionar curso</option>
                                        <?php while ($filaCurso = mysqli_fetch_assoc($resultadoCurso)) { ?>
                                            <option value="<?php echo $filaCurso['id_curso']; ?>"><?php echo $filaCurso['descri']; ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">

                                    <select class="input-group-text w-100" name='id_materia' id="id_materia">
                                        <option value="">Seleccionar materia</option>
                                        <?php if (isset($resultadoMateria)) {
                                            while ($filaMateria = mysqli_fetch_assoc($resultadoMateria)) { ?>
                                                <option value="<?php echo $filaMateria['id_materia']; ?>"><?php echo $filaMateria['descri']; ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar calificacion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCalificacion">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="action" value="editar">
                                <input type="hidden" name="id" id="editId">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" disabled id="editNombre">
                                </div>
                                <div class="mb-3">
                                    <input class="input-number input-group-text w-100" type="number" name="proceso" id="editProceso" placeholder="Puntaje de procesos">
                                </div>
                                <div class="mb-3">
                                    <input class="input-number input-group-text w-100" type="number" name="trabajo" id="editTrabajo" placeholder="Puntaje de trabajos">
                                </div>
                                <div class="mb-3">
                                    <input class="input-number input-group-text w-100" type="number" name="examen" id="editExamen" placeholder="Puntaje de exámenes">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-number input-group-text w-100" type="number" readonly name="total" id="editTotal">
                                </div>
                                <div class="mb-3">
                                    <input class="input-number input-group-text w-100" readonly type="number" name="calificacion" id="editCalificacion">
                                </div>
                                <input type="hidden" name="paso" id="editPaso">
                                <div class="mb-3">
                                    <select class="input-group-text w-100" disabled id="selectPaso" name="selectPaso">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                                <input class="input-group-text w-100" type="text" name="obs" id="editObs" placeholder="Observación">

                                <div class="modal-footer">
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
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