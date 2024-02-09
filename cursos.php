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
    <title>Cursos</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Cargar la tabla al cargar la página
            loadCursos();

            // Agregar nuevo
            $('#formAgregarCurso').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/curso.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formAgregarCurso')[0].reset();
                        loadCursos();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar-curso', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();
                var id_periodo = $(this).closest('tr').find('.id_periodo').text();
                var id_turno = $(this).closest('tr').find('.id_turno').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editPensum').val(id_pensum);
                $("select.editPensum selected").val(id_pensum).change();
                $('#editTurno').val(id_turno);
                $("select.editTurno selected").val(id_turno).change();
                $('#editPeriodo').val(id_periodo);
                $("select.editPeriodo selected").val(id_periodo).change();
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();
            });

            $('#formEditarCurso').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/curso.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadCursos();
                        $('#resultados').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-curso', function () {
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
                                url: 'funciones/curso.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },

                                success: function (response) {
                                    loadCursos();
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
                        url: 'funciones/curso.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },

                        success: function (response) {
                            $('#tablaCurso').html(response);
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
                $('#formBuscarCurso').keyup(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/curso.php',
                        type: 'POST',
                        data: $(this).serialize(),

                        success: function (response) {
                            $('#tablaCurso').html(response);
                        }
                    });
                });
            });


        });
        // Cargar tabla
        function loadCursos() {
            $.ajax({
                url: 'funciones/curso.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablaCurso').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/cursos.php")
            ?>
    </div>
    <div class="container">
        <h2>Cursos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalGenerarCurso'> <i
                    class="bi bi-node-plus"></i> Generar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarCurso">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                    <button class="btn btn-dark w-25" onclick="loadCursos()" type="reset"><i
                    class="bi bi-eraser"></i>Limpiar</button>
                </div>
            </form>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultados"></div>

        <!-- Tabla -->
        <div id="tablaCurso"></div>
    </div>

</body>

</html>