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
        $(document).ready(function () {
            //generar
            $(document).on('click', '.btn-generar', function () {
                // Obtener el ID
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la generación de eventos
                swal.fire({
                    title: "Cuidado!",
                    text: "¿Estás seguro de que deseas generar la escala?",
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
                                url: 'funciones/calificacion.php',
                                type: 'POST',
                                data: {
                                    action: 'generar',
                                    id: id,
                                },

                                success: function (response) {
                                    $('#resultados').html(response);
                                    loadCalificacion();
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
            //paginacion
            $(document).ready(function () {
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/calificacion.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
                        },
                        success: function (response) {
                            $('#tablaCalificacion').html(response);
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
        function loadCalificacion() {
            $.ajax({
                url: 'funciones/calificacion.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablaCalificacion').html(response);
                }
            });
        }
        // Cargar tabla eventos
        function loadDetalle(id) {
            $.ajax({
                url: 'funciones/calificacion.php',
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
        include("Modals/calificaciones.php");
        ?>
    </div>
    <div class="container">
        <h2>Calificaciones</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i
                    class="bi bi-search"></i> Buscar</button>
        </div>
        <!-- meustra mensaje exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaCalificacion"></div>
    </div>

</body>

</html>