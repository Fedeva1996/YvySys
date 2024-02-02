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
    <title>Modulos</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Generar modulos de curso
            $('#formGenerarModulos').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        $('#formGenerarModulos')[0].reset();
                        loadModulos();
                        $('#resultados').html(response);
                    }
                });
            });
            //autocompletar
            $(document).ready(function () {
                $('#ci-input').keyup(function () {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/modulo.php',
                            method: 'POST',
                            data: {
                                query: query,
                                action: 'autocompletar'
                            },
                            beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
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
                    var id = $(this).data('id-persona');
                    $('#ci-input').val(value);
                    $('#id_docente').val(id);
                    $('#suggestions').hide();
                });
            });
            // Editar
            $(document).on('click', '.btn-editar-modulo', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_persona = $(this).closest('tr').find('.docente_ci').text();

                $('#editId').val(id);
                $("#ci-input").val(id_persona);
            });

            $('#formEditarModulo').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        loadModulos();
                        $('#resultados').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-modulo', function () {
                // Obtener el ID del registro a eliminar
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la eliminación con el usuario
                swal.fire({
                    title: "Estás seguro de que deseas eliminar este registro?",
                    text: "Una vez eliminado no se podra recuperar!",
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
                                url: 'funciones/modulo.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                                    loadModulos();
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
                        url: 'funciones/modulo.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                            $('#tablaModulo').html(response);
                        }
                    });
                }

                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

                // Cargar la primera página al cargar el documento
                cargarPagina(1);
            });
            // Buscar
            $('#formBuscarModulo').keyup(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                        $('#tablaModulo').html(response);
                    }
                });
            });
        });
        // Cargar tabla
        function loadModulos() {
            $.ajax({
                url: 'funciones/modulo.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                beforeSend: function (objeto) {
                    $("#resultados").html("Mensaje: Cargando...");
                    },
                    success: function (response) {
                    $('#tablaModulo').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/modulos.php")
        ?>
    </div>
    <div class="container">
        <h2>Modulos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalGenerarModulo'> <i
                    class="bi bi-node-plus"></i> Generar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarModulo">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadModulos()" type="reset"><i
                        class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultados"></div>
        
        <!-- Tabla -->
        <div id="tablaModulo"></div>
    </div>
    
</body>

</html>