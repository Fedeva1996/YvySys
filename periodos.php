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
    <title>Periodos</title>
    <?php 
    include("head.php"); 
    ?>
    <script>
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadPeriodos();

            // Agregar nuevo
            $('#formAgregarPeriodo').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/periodo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarPeriodo')[0].reset();
                        loadPeriodos();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar-periodo', function() {
                var id = $(this).closest('tr').find('.id').text();
                var ano = $(this).closest('tr').find('.ano').text();
                var descri = $(this).closest('tr').find('.descri').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editAno').val(ano);
                $('#editDescri').val(descri);
                $("select.editEstado selected").val(estado).change();

                // Asignar el valor de action al formulario de edición
                $('#formEditarPeriodo').find('input[name="action"]').val('editar');
            });

            $('#formEditarPeriodo').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/periodo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadPeriodos();
                        $('#resultado').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-periodo', function() {
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
                                url: 'funciones/periodo.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadPeriodos();
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
                        url: 'funciones/periodo.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaPeriodo').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

                // Cargar la primera página al cargar el documento
                cargarPagina(1);

                // Buscar
                $('#formBuscarPeriodo').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/periodo.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#tablaPeriodo').html(response);
                        }
                    });
                });
            });


        });
        // Cargar tabla
        function loadPeriodos() {
            $.ajax({
                url: 'funciones/periodo.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function(response) {
                    $('#tablaPeriodo').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/periodos.php")
        ?>
    </div>
    <div class="container">
        <h2>Periodos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarPeriodo'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarPeriodo">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Año">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadPeriodos()" type="reset"><i class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaPeriodo"></div>
    </div>
    <div id="resultado"></div>
</body>

</html>