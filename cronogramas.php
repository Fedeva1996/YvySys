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
    <title>Cronogramas</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            // Asignar modulos
            $('#formAsignarModulo').submit(function (e) {
                e.preventDefault();
                // Objeto para almacenar los datos en formato JSON
                var datos = [];

                // Recorre cada fila del formulario
                $(".row").each(function () {
                    let id = $(this).find(".id").val();
                    let inicio = $(this).find("input[name='inicio']").val();
                    let fin = $(this).find("input[name='fin']").val();

                    // Agrega el objeto al array si los campos no están vacíos
                    if (id && inicio && fin) {
                        datos.push({
                        "id": id,
                        "inicio": inicio,
                        "fin": fin
                    });
                    }
                });
                // Convierte el array a formato JSON
                var datosJSON = JSON.stringify(datos);

                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: {
                        "action": "asignarModulo",
                        "datos": datosJSON
                    },

                    success: function (response) {
                        $('#formAgregarCronograma')[0].reset();
                        loadCronogramas();
                        $('#resultados').html(response);
                    }
                });
            });

            // Cargar la tabla al cargar la página
            loadCronogramas();

            // Agregar nuevo
            $('#formAgregarCronograma').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#formAgregarCronograma')[0].reset();
                        loadCronogramas();
                        $('#resultados').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();
                var id_periodo = $(this).closest('tr').find('.id_periodo').text();
                var id_turno = $(this).closest('tr').find('.id_turno').text();
                var id_modalidad = $(this).closest('tr').find('.id_modalidad').text();
                var tipo = $(this).closest('tr').find('.tipo').text();
                var estado = $(this).closest('tr').find('.estado').text();

                $('#editId').val(id);
                $('#editPensum').val(id_pensum);
                $("select.editPensum selected").val(id_pensum).change();
                $('#editTurno').val(id_turno);
                $("select.editTurno selected").val(id_turno).change();
                $('#editModalidad').val(id_modalidad);
                $("select.editModalidad selected").val(id_modalidad).change();
                $('#editPeriodo').val(id_periodo);
                $("select.editPeriodo selected").val(id_periodo).change();
                $('#editTipo').val(tipo);
                $("select.editTipo selected").val(tipo).change();
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();
            });

            $('#formEditarCronograma').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/cronograma.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        loadCronogramas();
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
                                url: 'funciones/cronograma.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                beforeSend: function (objeto) {
                                    $("#resultados").html("Mensaje: Cargando...");
                                },
                                success: function (response) {
                                    loadCronogramas();
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
            //generar
            $(document).on('click', '.btn-generar', function () {
                // Obtener el ID
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la generación de eventos
                swal.fire({
                    title: "Cuidado!",
                    text: "¿Estás seguro de que deseas generar eventos?",
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
                                url: 'funciones/cronograma.php',
                                type: 'POST',
                                data: {
                                    action: 'generar',
                                    id: id
                                },
                                beforeSend: function (objeto) {
                                    $("#resultados").html("Mensaje: Cargando...");
                                },
                                success: function (response) {
                                    loadCronogramas();
                                    $('#resultados').html(response);
                                }
                            });
                        } else {
                            swal.fire({
                                title: "No se generaran los eventos de este cronograma!",
                                background: "#212529"
                            })
                        }
                    });
            });
        });
        //paginacion
        function cargarPagina(pagina) {
            $.ajax({
                url: 'funciones/cronograma.php',
                type: 'POST',
                data: {
                    action: 'listar',
                    pagina: pagina
                },

                success: function (response) {
                    $('#tablaCronograma').html(response);
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
        $('#formBuscarCronograma').keyup(function (e) {
            e.preventDefault();
            $.ajax({
                url: 'funciones/cronograma.php',
                type: 'POST',
                data: $(this).serialize(),

                success: function (response) {
                    $('#tablaCronograma').html(response);
                }
            });
        });

        //autocompletar
        $('#modulo-input').keyup(function () {
            var query = $(this).val();

            if (query !== '') {
                $.ajax({
                    url: 'funciones/cronograma.php',
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
            var id = $(this).data('id-modulo');
            $('#modulo-input').val(value);
            $('#id').val(id);
            $('#suggestions').hide();
        });
        // Cargar tabla
        function loadCronogramas() {
            $.ajax({
                url: 'funciones/cronograma.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },

                success: function (response) {
                    $('#tablaCronograma').html(response);
                }
            });
        }
        // Cargar tabla eventos
        function loadEventos(id) {
            $.ajax({
                url: 'funciones/cronograma.php',
                type: 'POST',
                data: {
                    action: 'verEventos',
                    id: id
                },

                success: function (response) {
                    $('#tablaEventos').html(response);
                }
            });
        }
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/cronogramas.php");
        ?>
    </div>
    <div class="container">
        <h2>Cronogramas</h2>
        <div class="mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarCronograma">
                <input type="hidden" name="action" value="listar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadCronogramas()" type="reset"><i
                        class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultados"></div>

        <!-- Tabla -->
        <div id="tablaCronograma"></div>
    </div>
</body>

</html>