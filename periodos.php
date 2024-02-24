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
    <title>Periodos - Yvy Marãe'ỹ</title>
    <?php
    include("head.php");
    ?>
    <script>
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadPeriodos();

            // Agregar nuevo
            $('#formAgregarPeriodo').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/periodo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarPeriodo')[0].reset();
                        loadPeriodos();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar-periodo', function () {
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

            $('#formEditarPeriodo').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/periodo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        loadPeriodos();
                        $('#resultados').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-periodo', function () {
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
                                success: function (response) {
                                    loadPeriodos();
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
                        url: 'funciones/periodo.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function (response) {
                            $('#tablaPeriodo').html(response);
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
                $('#formBuscarPeriodo').keyup(function (e) {
                    e.preventDefault();
                    buscar();
                });
                $('#selectPeriodo').change(function (e) {
                    e.preventDefault();
                    buscar();
                });
                function buscar() {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/periodo.php',
                        type: 'POST',
                        data: $('#formBuscarPeriodo').serialize(),
                        success: function (response) {
                            $('#tablaPeriodo').html(response);
                        }
                    });
                }
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
                success: function (response) {
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
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarPeriodo'> <i
                    class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="input-group mb-2" data-bs-theme="dark">
            <form id="formBuscarPeriodo" class="w-75">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2 w-100">
                    <?php
                    include 'db_connect.php';
                    $sql = "SELECT DISTINCT ano FROM periodo";
                    $resultados = pg_query($conn, $sql);
                    if (pg_num_rows($resultados) > 0) {
                        echo "<select class='form-select w-25' name='ano' id='selectPeriodo' required>";
                        echo "<option selected disabled>Buscar por año</option>";
                        while ($fila = pg_fetch_assoc($resultados)) {
                            echo "<option value='" . $fila['ano'] . "'>" . $fila['ano'] . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "<select class='form-select w-25' name='ano' aria-label='Disabled'>";
                        echo "<option selected disabled>No hay años</option>";
                        echo "</select>";
                    }
                    ?>
                    <input class="input-group-text w-50" type="text" name="buscar" placeholder="Buscar">
                    <button class="btn btn-dark w-25" onclick="loadPeriodos()" type="reset"><i
                            class="bi bi-eraser"></i>Limpiar</button>
                </div>
            </form>
        </div>
        <!-- resultado exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaPeriodo"></div>
    </div>
</body>

</html>