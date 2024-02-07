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
    <title>Turnos</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadTurnos();

            // Agregar nuevo
            $('#formAgregarTurno').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/turno.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formAgregarTurno')[0].reset();
                        loadTurnos();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar-turno', function () {
                var id = $(this).closest('tr').find('.id').text();
                var descri = $(this).closest('tr').find('.descri').text();
                var horario = $(this).closest('tr').find('.horario').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editDescri').val(descri);
                $("select.editDescri selected").val(estado).change();
                $('#editHorario').val(horario);
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();

                // Asignar el valor de action al formulario de edición
                $('#formEditarTurno').find('input[name="action"]').val('editar');
            });

            $('#formEditarTurno').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/turno.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadTurnos();
                        $('#resultados').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-turno', function () {
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
                                url: 'funciones/turno.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
            
                    success: function (response) {
                                    loadTurnos();
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
                        url: 'funciones/turno.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
    
                    success: function (response) {
                            $('#tablaTurno').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

                // Cargar la primera página al cargar el documento
                cargarPagina(1);

                // Buscar
                $('#formBuscarTurno').submit(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/turno.php',
                        type: 'POST',
                        data: $(this).serialize(),
    
                    success: function (response) {
                            $('#tablaTurno').html(response);
                        }
                    });
                });
            });


        });
        // Cargar tabla
        function loadTurnos() {
            $.ajax({
                url: 'funciones/turno.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                
                    success: function (response) {
                    $('#tablaTurno').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/turnos.php");
        ?>
    </div>
    <div class="container">
        <h2>Turnos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarTurno'> <i
                    class="bi bi-person-add"></i> Agregar</button>
        </div>

        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarTurno">
                <input type="hidden" name="action" value="buscar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadTurnos()" type="reset"><i
                        class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaTurno"></div>
    </div>
    <div id="resultados"></div>
</body>

</html>