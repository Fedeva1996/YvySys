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
        $(document).ready(function () {
            //buscar
            $('#formBuscarPlanClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        $('#tablaPlanesClase').html(response);
                    }
                });
            });
            //agregar
            $('#formAgregarPlanClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        $('#formAgregarPlanClase')[0].reset();
                        $('#resultado').html(response);
                        setTimeout(function () {
                            location.reload(true);
                        }, 1500);
                    }
                });
            });
            // Agregar nuevo detalle
            $('#formAgregarPlanClaseDet').submit(function (e) {
                var keep = $('#keep').find(":selected").val();
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        $('#formAgregarPlanClaseDet')[0].reset();
                        $("#keep").val(keep).change();
                        loadPlanClase();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
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

            $('#formEditarPlanClase').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        loadPlanClase();
                        $('#resultado').html(response);
                    },
                });
            });
            // Editar
            $(document).on('click', '.btn-editar-cab', function () {
                var id = $(this).closest('tr').find('.idCab').text();
                var id_materia = $(this).closest('tr').find('.id_materia').text();
                var fecha_ini = $(this).closest('tr').find('.fecha_ini').text();
                var fecha_fin = $(this).closest('tr').find('.fecha_fin').text();

                $('#editIdCab').val(id);
                $('#editMateria').val(id_materia).change();
                $('#editFechaIni').val(fecha_ini);
                $('#editFechaFin').val(fecha_fin);
            });

            $('#formEditarPlanClaseCab').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planClase.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        loadPlanClase();
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
                            $.ajax({
                                url: 'funciones/planClase.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                                    loadPlanClase();
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
                function cargarPagina(pagina, curso) {
                    $.ajax({
                        url: 'funciones/planClase.php',
                        type: 'POST',
                        data: {
                            action: 'listar'
                        },
                        beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                            $('#tablaPlanesClase').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');

                    cargarPagina(pagina, curso);
                });

            });
        });
        // Cargar tabla
        function loadPlanClase() {
            $.ajax({
                url: 'funciones/planClase.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                    $('#tablaPlanesClase').html(response);
                }
            });
        }
        // Cargar tabla eventos
        function loadDetalle(id) {
            $.ajax({
                url: 'funciones/planClase.php',
                type: 'POST',
                data: {
                    action: 'verDetalle',
                    id: id
                },
                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
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
        include("Modals/planClases.php");
        ?>
    </div>
    <div class="container">
        <h2>Planes de clase</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarFecha'> <i
                    class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar plan de clase</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarDetalle'> <i
                    class="bi bi-person-add"></i> Agregar detalles</button>
        </div>

        <!-- Mensaje exito/error -->
        <div id="resultado"></div>
        <!-- Tabla -->
        <div id="tablaPlanesClase"></div>

    </div>
</body>

</html>