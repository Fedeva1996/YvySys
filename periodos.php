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
    <?php include("head.php"); ?>
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
            $(document).on('click', '.btn-editar', function() {
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
            $(document).on('click', '.btn-eliminar', function() {
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
        ?>
    </div>
    <div class="container">
        <h2>Periodos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarPeriodo">
                <input type="hidden" name="action" value="buscar">
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
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar periodo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPeriodo">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="ano" placeholder="Año" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="descri" placeholder="Descripción. Ej:Taller enero" required>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Alumno</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPeriodo">
                        <div class="row">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="ano" id="editAno" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="descri" id="editDescri" required>
                            </div>
                            <div class="mb-3">
                                <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                    <option value="S">Sin iniciar</option>
                                    <option value="C">En curso</option>
                                    <option value="F">Finalizado</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="resultado"></div>
</body>

</html>