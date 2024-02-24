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
    <title>Proceso de clase - Yvy Marãe'ỹ</title>
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
            //generar
            $(document).on('click', '.btn-generar', function () {
                // Obtener el ID
                var id = $(this).closest('tr').find('.id').text();
                var modulo_id = $(this).closest('tr').find('.modulo_id').text();

                // Confirmar la generación de eventos
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
                                url: 'funciones/procesoClase.php',
                                type: 'POST',
                                data: {
                                    action: 'generar',
                                    id: id,
                                    modulo_id: modulo_id
                                },

                                success: function (response) {
                                    loadProcesosClase();
                                    $('#resultados').html(response);
                                }
                            });
                        } else {
                            swal.fire({
                                title: "No se generaran los eventos de este cronograma!",
                                background: "#212529"
                            })
                        }
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
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/procesoClase.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
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
        // Cargar tabla eventos
        function loadDetalle(id) {
            $.ajax({
                url: 'funciones/procesoClase.php',
                type: 'POST',
                data: {
                    action: 'verDetalle',
                    id: id
                },

                success: function (response) {
                    $('#tablaDetalle').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/procesosClase.php");
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
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-calendar-plus"></i> Agregar proceso de Clase</button>
            &nbsp;&nbsp;
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarDetalle'> <i
                    class="bi bi-person-add"></i> Agregar entrega</button>
        </div>

        <!-- muestra mensaje exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaProcesoClase"></div>
    </div>

</body>

</html>