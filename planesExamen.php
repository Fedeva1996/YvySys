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
        $(document).ready(function () {
            //buscar
            $('#formBuscarPlanExamen').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadPlanesExamen();
                        $('#tablaPlanesExamen').html(response);
                    }
                });
            });

            // Agregar nuevo
            $('#formAgregarPlanExamen').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#formAgregarPlanExamen')[0].reset();
                        loadPlanesExamen();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var idCab = $(this).closest('tr').find('.id_cab').text();
                var idAl = $(this).closest('tr').find('.id_alumno').text();
                var puntaje = $(this).closest('tr').find('.puntaje_hecho').text();

                $('#editId').val(id);
                $('#editIdCab').val(idCab).change();
                $('#editIdAl').val(idAl).change();
                $('#editPuntaje').val(puntaje);
            });

            $('#formEditarPlanExamen').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadPlanesExamen();
                        $('#resultado').html(response);
                    },
                });
            });
            // Editar
            $(document).on('click', '.btn-editar-cab', function () {
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

            $('#formEditarPlanExamenCab').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/planExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadPlanesExamen();
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
                                url: 'funciones/planExamen.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function (response) {
                                    loadPlanesExamen();
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
                        url: 'funciones/planExamen.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        beforeSend: function (objeto) {
                            $("#resultados").html("Mensaje: Cargando...");
                        },
                        success: function (response) {
                            $('#tablaPlanesExamen').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });
            });
        });
        // Cargar tabla
        function loadPlanesExamen() {
            $.ajax({
                url: 'funciones/planExamen.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                },
                success: function (response) {
                    $('#tablaPlanesExamen').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/planExamenes.php");
        ?>
    </div>
    <div class="container">
        <h2>Planes de Examen</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarFecha'> <i
                    class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar examen</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i
                    class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        <!-- mensaje exito/error -->
        <div id="resultado"></div>
        <!-- Tabla -->
        <div id="tablaPlanesExamen"></div>
    </div>
</body>

</html>