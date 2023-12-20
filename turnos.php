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
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadTurnos();

            // Agregar nuevo
            $('#formAgregarTurno').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/turno.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarTurno')[0].reset();
                        loadTurnos();
                        $('#resultado').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
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

            $('#formEditarTurno').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/turno.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadTurnos();
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
                                url: 'funciones/turno.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadTurnos();
                                    $('#resultado').html(response);
                                }
                            });
                        } else {
                            swal.fire("Se mantendra el registro!");
                        }
                    });
            });

            //paginacion
            $(document).ready(function() {
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/turno.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaTurno').html(response);
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
                $('#formBuscarTurno').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'funciones/turno.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
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
                success: function(response) {
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
        ?>
    </div>
    <div class="container">
        <h2>Turnos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>

        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarTurno">
                <input type="hidden" name="action" value="buscar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadTurnos()" type="reset"><i class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaTurno"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar turno</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarTurno">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="mb-3">
                                <select class="input-group-text w-100" name="descri" aria-placeholder="turno" required>
                                    <option selected disabled>Seleccione turno</option>
                                    <option value="Manana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="horario" placeholder="Horario. Ej: 20:00 a 22:00" required>
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
                    <form id="formEditarTurno">
                        <div class="row">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <select class="input-group-text w-100" name="descri" id="editDescri" required>
                                    <option selected disabled>Seleccione turno</option>
                                    <option value="Manana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="horario" id="editHorario" required>
                            </div>
                            <div class="mb-3">
                                <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                    <option value="0">Inactivo</option>
                                    <option value="1">Activo</option>
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