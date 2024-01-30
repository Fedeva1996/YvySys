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
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadInscripcion();

            // Agregar nuevo
            $('#formAgregarAlumno').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/alumno.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarAlumno')[0].reset();
                        $('#resultado').html(response);
                    }
                });
            });
            //autocompletar alumno
            $(document).ready(function () {
                $('#ci-input').keyup(function () {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/inscripcion.php',
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
                    var id = $(this).data('id_alumno');
                    $('#ci-input').val(value);
                    $('#id_alumno').val(id);
                    $('#suggestions').hide();
                });
            });

            // Agregar existente
            $('#formAgregarInscripcion').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarInscripcion')[0].reset();
                        loadInscripcion();
                        $('#resultado').html(response);

                    }
                });
            });
            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var nombre = $(this).closest('tr').find('.nombre').text();
                var apellido = $(this).closest('tr').find('.apellido').text();
                var id_curso = $(this).closest('tr').find('.id_curso').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editNombre').val(nombre + " " + apellido);
                document.getElementById("editCurso").value = id_curso;
                document.getElementById("editEstado").value = estado;
            });

            $('#formEditarInscripcion').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadInscripcion();
                        $('#resultado').html(response);
                    },
                });
            });

            // matricular
            $(document).on('click', '.btn-matricular', function () {
                var id = $(this).closest('tr').find('.id').text();

                $('#matId').val(id);
            });

            $('#formAgregarMatriculacion').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadInscripcion();
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
                                success: function (response) {
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
            // Eliminar det
            $(document).on('click', '.btn-eliminar-det', function () {
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
                                    action: 'eliminarDet',
                                    id: id
                                },
                                success: function (response) {
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
            $(document).ready(function () {
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/inscripcion.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function (response) {
                            $('#tablaInscripcion').html(response);
                        }
                    });
                }

                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });
            });
            // Buscar
            $('#formBuscarInscripcion').keyup(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/inscripcion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
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
                success: function (response) {
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
        include("Modals/inscripciones.php");
        ?>
    </div>
    <div class="container">
        <h2>Inscripciones</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarAlumno'><i
                    class="bi bi-person-add"></i> Agregar alumno</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'><i
                    class="bi bi-person-vcard"></i> Inscribir a curso</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i
                    class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarInscripcion">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar"
                        placeholder="Nombre, apellido o Ci del alumno">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadInscripcion()" type="reset"><i class="bi bi-eraser"></i>
                    Limpiar</button>
            </form>
        </div>
        <!-- muestra resultasdo error/exito -->
        <div id="resultado"></div>

        <!-- Tabla -->
        <div id="tablaInscripcion"></div>
    </div>

</body>

</html>