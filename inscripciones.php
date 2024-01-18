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
    <title>Inscriptos</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadInscripcion();

            // Agregar nuevo
            $('#formAgregarAlumno').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/alumno.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarAlumno')[0].reset();
                        $('#resultado').html(response);
                    }
                });
            });
            //autocompletar alumno
            $(document).ready(function() {
                $('#ci-input').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/inscripcion.php',
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
                    var id = $(this).data('id_alumno');
                    $('#ci-input').val(value);
                    $('#id_alumno').val(id);
                    $('#suggestions').hide();
                });
            });
            // Agregar existente
            $('#formAgregarInscripcion').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarInscripcion')[0].reset();
                        loadInscripcion();
                        $('#resultado').html(response);

                    }
                });
            });
            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var nombre = $(this).closest('tr').find('.nombre').text();
                var apellido = $(this).closest('tr').find('.apellido').text();
                var id_curso = $(this).closest('tr').find('.id_curso').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editNombre').val(nombre + " " + apellido);
                document.getElementById("editCurso").value = id_curso;
                document.getElementById("editEstado").value = estado;


                // Asignar el valor de action al formulario de edición
                $('#formEditarInscripcion').find('input[name="action"]').val('editar');
            });

            $('#formEditarInscripcion').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadInscripcion();
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
                            // Envío de la solicitud AJAX para eliminar el registro
                            $.ajax({
                                url: 'funciones/inscripcion.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadInscripcion();
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
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/inscripcion.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaInscripcion').html(response);
                        }
                    });
                }

                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

            });
            // Buscar
            $('#formBuscarInscripcion').keyup(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaInscripcion').html(response);
                    }
                });
            });
        });

        // Cargar tabla
        function loadInscripcion() {
            $.ajax({
                url: 'funciones/inscripcion.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function(response) {
                    $('#tablaInscripcion').html(response);
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
        <h2>Inscripciones</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarAlumno'><i class="bi bi-person-add"></i> Agregar alumno</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'><i class="bi bi-person-vcard"></i> Inscribir a curso</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        
        <!-- Formulario para buscar -->
        <div class="mb-3">
            <form id="formBuscarInscripcion">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci del alumno">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadInscripcion()" type="reset"><i class="bi bi-eraser"></i> Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaInscripcion"></div>
    </div>
    <!-- formulario agregar alumno -->
    <div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAgregarAlumnoLabel">Agregar Alumno</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregarAlumno">
                            <input class="input-group-text" type="hidden" name="action" value="agregar">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="ci" placeholder="Documento de identidad" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="nombre" placeholder="Nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="apellido" placeholder="Apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="date" name="edad" placeholder="Fecha nacimiento" required>
                                    </div>
                                    <div class="mb-3">
                                        <select class="input-group-text w-100" style="width: 95%;" name="sexo" required>
                                            <option selected disabled>Seleccione sexo</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="email" name="correo" placeholder="Correo" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="nacionalidad" placeholder="Nacionalidad" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="direccion" placeholder="Dirección" required>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="telefono" placeholder="Teléfomo" required>
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
        <!-- Formulario para agregar inscripcion -->
        <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar inscripcion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregarInscripcion">
                            <input type="hidden" name="action" value="agregar">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" id="ci-input" placeholder="Ci del alumno" autocomplete="off" required>
                                        <input type="hidden" id="id_alumno" name="id_alumno">
                                        <div id="suggestions"></div>
                                    </div>
                                    <div class="mb-3">
                                        <?php
                                        include 'db_connect.php';
                                        $sql = "SELECT * FROM cursos";
                                        $resultado = pg_query($conn, $sql);
                                        if (pg_num_rows($resultado) > 0) {
                                            echo "<select class='form-select  w-100' name='id_curso' required>";
                                            echo "<option selected disabled>Seleccione curso</option>";
                                            while ($fila = pg_fetch_assoc($resultado)) {
                                                echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                            }
                                            echo "</select>";
                                        }
                                        ?>
                                    </div>
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" readonly type="datetime" id="fecha" name="fecha" value="<?php echo date("d-m-Y"); ?>">
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
        <!-- Formulario para ir a reporte -->
        <div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalReporteLabel">Agregar inscripcion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="reportes/inscriptos.php" method="post" id="formReporteInscripcion">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <?php
                                        include 'db_connect.php';
                                        $sql = "SELECT * FROM cursos";
                                        $resultado = pg_query($conn, $sql);
                                        if (pg_num_rows($resultado) > 0) {
                                            echo "<select class='form-select  w-100' id='id_curso' name='id_curso' required>";
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar inscripcion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarInscripcion">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="editNombre" class="col-sm-2 col-form-label">Alumno:</label>
                                    <input class="form-control-plaintext w-100" type="text" readonly name="editNombre" id="editNombre">
                                </div>
                                <div class="mb-3">
                                    <?php
                                    include 'db_connect.php';
                                    $sql = "SELECT * FROM cursos";
                                    $resultado = pg_query($conn, $sql);
                                    if (pg_num_rows($resultado) > 0) {
                                        echo "<select class='editCurso input-group-text w-100' id='editCurso' name='id_curso' required>";
                                        while ($fila = pg_fetch_assoc($resultado)) {
                                            echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                                </div>
                                <select class="editEstado input-group-text w-100" id="editEstado" name="estado" required>
                                    <option selected disabled>Seleccione estado</option>
                                    <option value="0">Inactivo</option>
                                    <option value="1">Activo</option>
                                </select>
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
    <div id="resultado"></div>

</body>

</html>