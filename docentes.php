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
    <title>Docentes</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadDocentes();

            // Agregar nuevo
            $('#formAgregarDocente').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/docente.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarDocente')[0].reset();
                        loadDocentes();
                        $('#sweetAlerts').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var ci = $(this).closest('tr').find('.ci').text();
                var nombre = $(this).closest('tr').find('.nombre').text();
                var apellido = $(this).closest('tr').find('.apellido').text();
                var edad = $(this).closest('tr').find('.edad').text();
                var sexo = $(this).closest('tr').find('.sexo').text();
                var correo = $(this).closest('tr').find('.correo').text();
                var nacionalidad = $(this).closest('tr').find('.nacionalidad').text();
                var direccion = $(this).closest('tr').find('.direccion').text();
                var telefono = $(this).closest('tr').find('.telefono').text();

                $('#editId').val(id);
                $('#editCi').val(ci);
                $('#editNombre').val(nombre);
                $('#editApellido').val(apellido);
                $('#editEdad').val(edad);
                $('#editSexo').val(sexo);
                $('#editCorreo').val(correo);
                $('#editNacionalidad').val(nacionalidad);
                $('#editDireccion').val(direccion);
                $('#editTelefono').val(telefono);

                // Asignar el valor de action al formulario de edición
                $('#formEditarDocente').find('input[name="action"]').val('editar');
            });

            $('#formEditarDocente').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/docente.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadDocentes();
                        $('#sweetAlerts').html(response);
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
                                url: 'funciones/docente.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadAlumnos();
                                    $('#sweetAlerts').html(response);
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
                        url: 'funciones/docente.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaDocentes').html(response);
                        }
                    });
                }

                $(document).on('click', '.btn-pagina', function() {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

                // Cargar la primera página al cargar el documento
                cargarPagina(1);
            });
            // Buscar
            $('#formBuscarDocente').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/docente.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaDocentes').html(response);
                    }
                });
            });
        });

        // Cargar tabla
        function loadDocentes() {
            $.ajax({
                url: 'funciones/docente.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function(response) {
                    $('#tablaDocentes').html(response);
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
        <h2>Docentes</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>

        <!-- Formulario para buscar -->
        <div class="mb-3">
            <form id="formBuscarDocente">
                <input type="hidden" name="action" value="buscar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadDocentes()" type="reset"><i class="bi bi-eraser"></i> Limpiar</button>
            </form>
        </div>

        <!-- Tabla -->
        <div id="tablaDocentes"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarDocente">
                        <input class="input-group-text" type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="ci" placeholder="Documento de identidad" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="nombre" placeholder="Nombre" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="apellido" placeholder="Apellido" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="number" name="edad" placeholder="Edad" required>
                                </div>
                                <div class="mb-3">
                                    <select class="input-group-text w-100" style="width: 95%;" name="sexo" required>
                                        <option selected disabled>Seleccione sexo</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="email" name="correo" placeholder="Correo" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="nacionalidad" placeholder="Nacionalidad" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="direccion" placeholder="Dirección" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="telefono" placeholder="Teléfomo" required>
                                </div>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Docente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarDocente">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="ci" id="editCi" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="nombre" id="editNombre" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="apellido" id="editApellido" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="number" name="edad" id="editEdad" required>
                                </div>
                                <div class="mb-3">
                                    <select class="editSexo input-group-text w-100" id="editSexo" name="sexo" required>
                                        <option selected disabled>Seleccione sexo</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="email" name="correo" id="editCorreo" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="nacionalidad" id="editNacionalidad" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="direccion" id="editDireccion" required>
                                </div>
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="telefono" id="editTelefono" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="sweetAlerts" id="sweetAlerts"></div>

</body>

</html>