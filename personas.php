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
    <title>Personas - Yvy Marãe'ỹ</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Agregar nuevo
            $('#formAgregarPersona').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/persona.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formAgregarPersona')[0].reset();
                        loadPersonas();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar-persona', function () {
                var id = $(this).closest('tr').find('.id').text();
                var ci = $(this).closest('tr').find('.ci').text();
                var nombre = $(this).closest('tr').find('.nombre').text();
                var apellido = $(this).closest('tr').find('.apellido').text();
                var fecha_nac = $(this).closest('tr').find('.fecha_no_form').text();
                var sexo = $(this).closest('tr').find('.sexo').text();
                var correo = $(this).closest('tr').find('.correo').text();
                var nacionalidad = $(this).closest('tr').find('.nacionalidad').text();
                var direccion = $(this).closest('tr').find('.direccion').text();
                var telefono = $(this).closest('tr').find('.telefono').text();

                $('#editId').val(id);
                $('#editCi').val(ci);
                $('#editNombre').val(nombre);
                $('#editApellido').val(apellido);
                $('#editFechaNac').val(fecha_nac);
                $('#editSexo').val(sexo);
                $('#editCorreo').val(correo);
                $('#editNacionalidad').val(nacionalidad);
                $('#editDireccion').val(direccion);
                $('#editTelefono').val(telefono);
            });

            $('#formEditarPersona').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/persona.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadPersonas();
                        $('#resultados').html(response);
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-persona', function () {
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
                            // Envío de la solicitud AJAX para eliminar el registro
                            $.ajax({
                                url: 'funciones/persona.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },

                                success: function (response) {
                                    loadPersonas();
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
                        url: 'funciones/persona.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },

                        success: function (response) {
                            $('#tablaPersonas').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });

            });
            // Buscar
            $('#formBuscarPersona').keyup(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/persona.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#tablaPersonas').html(response);
                    }
                });
            });

        });

        // Cargar tabla
        function loadPersonas() {
            $.ajax({
                url: 'funciones/persona.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablaPersonas').html(response);
                }
            });
        }
        $(document).on('click', '.btn-inscripciones', function () {
            var id = $(this).closest('tr').find('.id').text();
            $.ajax({
                url: 'funciones/persona.php',
                type: 'POST',
                data: {
                    action: 'inscripciones',
                    id: id

                },

                success: function (response) {
                    $('#tablaInscripciones').html(response);
                }
            });
        });
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/personas.php");
        ?>
    </div>
    <div class="container">
        <h2>Personas</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregarPersona'> <i
                    class="bi bi-person-add"></i> Agregar</button>
        </div>

        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarPersona">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                    <button class="btn btn-dark w-25" type="reset" onclick="loadPersonas()"><i class="bi bi-eraser"></i>
                        Limpiar</button>
                </div>
            </form>
        </div>
        <!-- mostrar resultado exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaPersonas"></div>
    </div>
</body>

</html>