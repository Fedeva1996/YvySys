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
    <title>Ficha Academica - Yvy Marãe'ỹ</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadFichaAcademica();

            // Agregar nuevo
            $('#formGenerarFichaAcademica').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/fichaAcademica.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formGenerarFichaAcademica')[0].reset();
                        loadFichaAcademica();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();

                $('#editId').val(id);
                $('#editPensum').val(id_pensum);
                $("select.editPensum selected").val(id_pensum).change();
            });

            $('#formEditarfichaAcademica').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/fichaAcademica.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadFichaAcademica();
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
                    background: "#212529",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Confirmar",
                    cancelButtonColor: '#6e7881',
                    cancelButtonText: "Cancelar"
                })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            $.ajax({
                                url: 'funciones/fichaAcademica.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },

                                success: function (response) {
                                    loadFichaAcademica();
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
                        url: 'funciones/fichaAcademica.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },

                        success: function (response) {
                            $('#tablafichaAcademica').html(response);
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
                $('#formBuscarfichaAcademica').keyup(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/fichaAcademica.php',
                        type: 'POST',
                        data: $(this).serialize(),

                        success: function (response) {
                            $('#tablafichaAcademica').html(response);
                        }
                    });
                });
            });
            //autocompletar alumno
            $(document).ready(function () {
                $('#ci-input').keyup(function () {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'funciones/fichaAcademica.php',
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
                    var id = $(this).data('id_alumno');
                    $('#ci-input').val(value);
                    $('#id_alumno').val(id);
                    $('#suggestions').hide();
                });
            });

        });
        // Cargar tabla
        function loadFichaAcademica() {
            $.ajax({
                url: 'funciones/fichaAcademica.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablafichaAcademica').html(response);
                }
            });
        }
        function loadCurso(id) {
            $.ajax({
                url: 'funciones/fichaAcademica.php',
                type: 'POST',
                data: {
                    action: 'selectCurso',
                    alumnoId: id
                },
                success: function (response) {
                    $('#cursoSelect').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/fichaAcademica.php")
            ?>
    </div>
    <div class="container">
        <h2>fichaAcademica</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalGenerar'> <i
                    class="bi bi-node-plus"></i> Generar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarfichaAcademica">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2 w-50">
                    <input class="input-group-text w-50" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                    <button class="btn btn-dark w-25" onclick="loadFichaAcademica()" type="reset"><i
                            class="bi bi-eraser"></i>Limpiar</button>
                </div>
            </form>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultados"></div>

        <!-- Tabla -->
        <div id="tablafichaAcademica"></div>
    </div>

</body>

</html>