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
    <title>Puntuación de Examenes - Yvy Marãe'ỹ</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            //buscar
            $('#formBuscarPuntuarExamen').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/puntuarExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadPuntuarExamen();
                        $('#tablaPuntuarExamen').html(response);
                    }
                });
            });

            // Agregar nuevo
            $('#formAgregarPuntuarExamen').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'funciones/puntuarExamen.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (response) {
                        $('#formAgregarPuntuarExamen')[0].reset();
                        loadPuntuarExamen();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var directorio = $(this).closest('tr').find('.directorio').text();
                var cronograma = $(this).closest('tr').find('.cronograma').text();
                var modulo = $(this).closest('tr').find('.modulo').text();
                var fecha = $(this).closest('tr').find('.fecha').text();
                var recuperatorio = $(this).closest('tr').find('.recuperatorio').text();
                var puntaje = $(this).closest('tr').find('.puntaje').text();
                var tipo = $(this).closest('tr').find('.tipo').text();
                var obs = $(this).closest('tr').find('.obs').text();

                $('#editarId').val(id);
                $('#editarDirectorio').val(directorio);
                $('#editarCronogramaSelect').val(cronograma).change();
                $('#editarModuloSelect').val(modulo).change();
                $('#editarFecha').val(fecha);
                $('#editarRecuperatorio').val(recuperatorio);
                $('#editarTipo').val(tipo).change();
                $('#editarPuntaje').val(puntaje);
                $('#editarPbs').val(obs);
            });

            $('#formEditarPuntuarExamen').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/puntuarExamen.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadPuntuarExamen();
                        $('#resultados').html(response);
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
                                url: 'funciones/puntuarExamen.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },

                                success: function (response) {
                                    loadPuntuarExamen();
                                    $('#resultados').html(response);
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
                        url: 'funciones/puntuarExamen.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },

                        success: function (response) {
                            $('#tablaPuntuarExamen').html(response);
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
        function loadPuntuarExamen() {
            $.ajax({
                url: 'funciones/puntuarExamen.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablaPuntuarExamen').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/puntuarExamen.php");
        ?>
    </div>
    <div class="container">
        <h2>Puntajes de Examenes</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarFecha'> <i
                    class="bi bi-search"></i> Buscar</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar examen</button>
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalReporte'><i
                    class="bi bi-filetype-pdf"></i> Descargar reporte</button>
        </div>
        <!-- mensaje exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaPuntuarExamen"></div>
    </div>
</body>

</html>