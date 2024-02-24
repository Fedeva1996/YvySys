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
    <title>Asistencias - Yvy Marãe'ỹ</title>
    <?php include("head.php"); ?>
    <script>
        //buscar
        $(document).ready(function () {
            $('#formBuscarAsistencia').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#tablaAsistencia').html(response);
                    }
                });
            });
            // Agregar nuevo
            $('#formAgregarAsistencia').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formAgregarAsistencia')[0].reset();
                        loadAsistencias();
                        $('#resultados').html(response);
                    }
                });
            });
            //autocompletar alumno
            $(document).ready(function () {
                $('#planClase-input').keyup(function () {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/asistencia.php',
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
                    $('#planClase-input').val(value);
                    $('#id-plan').val(id);
                    $('#id-materia').val(id_curso);
                    $('#suggestions').hide();
                });
            });
            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editEstado').val(estado);
                document.getElementById("editEstado").value = estado;

                // Asignar el valor de action al formulario de edición
                $('#formEditarAsistencia').find('input[name="action"]').val('editar');
            });

            $('#formEditarAsistencia').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#resultados').html(response);
                        loadAsistencias();
                        $('#formBuscarAsistencia').submit();
                    },
                });
            });
            // marcar asistencia
            $(document).on('change', '.btn-asistencia', function () {
                var id = $(this).closest('tr').find('.id').text();
                var estado = $('#asistenciaCheck').is(':checked') ;

                $.ajax({
                    url: 'funciones/asistencia.php',
                    type: 'POST',
                    data: {
                        action: 'marcarAsistencia',
                        id: id,
                        estado: estado,
                    },

                    success: function (response) {
                        $('#resultados2').html(response);
                    },
                });
            });

            //paginacion
            $(document).ready(function () {
                function cargarPagina(pagina, id_modulo, fecha) {
                    $.ajax({
                        url: 'funciones/asistencia.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
                            id_modulo: id_modulo,
                            fecha: fecha
                        },
                        success: function (response) {
                            $('#tablaAsistencia').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    var id_modulo = $(this).data('id_modulo');
                    var fecha = $(this).data('fecha');

                    cargarPagina(pagina, id_modulo, fecha);
                });
            });
            //generar
            $(document).on('click', '.btn-generar', function () {
                // Obtener el ID 
                var id = $(this).closest('tr').find('.id').text();
                var modulo_id = $(this).closest('tr').find('.modulo_id').text();

                // Confirmar la generación de asistencias
                swal.fire({
                    title: "Cuidado!",
                    text: "¿Estás seguro de que deseas generar eventos?",
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
                                url: 'funciones/asistencia.php',
                                type: 'POST',
                                data: {
                                    action: 'generar',
                                    id: id,
                                    modulo_id: modulo_id
                                },

                                success: function (response) {
                                    $('#resultados').html(response);
                                    loadCronogramas();
                                }
                            });
                        } else {
                            swal.fire({
                                title: "No se generaran las asistencia del día!",
                                background: "#212529"
                            })
                        }
                    });
            });
        });
        // Cargar tabla
        function loadAsistencias() {
            $.ajax({
                url: 'funciones/asistencia.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function (response) {
                    $('#tablaAsistencia').html(response);
                }
            });
        }
        // Cargar tabla eventos
        function loadVerAsistencias(id) {
            $.ajax({
                url: 'funciones/asistencia.php',
                type: 'POST',
                data: {
                    action: 'verAsistencias',
                    id: id
                },
                success: function (response) {
                    $('#tablaVerAsistencias').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/asistencias.php");
        ?>
    </div>
    <div class="container">
        <h2>Asistencias</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarModulo'> <i
                    class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-calendar-plus"></i> Agregar asistencia de hoy</button>
        </div>
        <!-- mostrar exito/error -->
        <div id="resultados"></div>

        <!-- Tabla -->
        <div id="tablaAsistencia"></div>
    </div>
</body>

</html>