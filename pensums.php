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
    <title>Pensums</title>
    <?php include("head.php"); ?>
    <script>
        const agregarFila = () => {
            document.getElementById('tablaModulos').insertRow(-1).innerHTML =
                '<td><input class="form-control form-control-sm" type="text"></td><td><input class="form-control form-control-sm" type="text"></td><td><input class="form-control form-control-sm" type="text"></td>'
        }

        const eliminarFila = () => {
            const table = document.getElementById('tablaModulos')
            const rowCount = table.rows.length

            if (rowCount <= 1) {
                alert('No se puede eliminar el encabezado')
            }
            else {
                table.deleteRow(rowCount - 1)
            }
        }
        $(document).ready(function () {
            //buscar
            $('#formBuscarPensum').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#tablaPensums').html(response);
                        loadPensum();
                    }
                });
            });
            // Agregar nuevo
            $('#formAgregarPensum').submit(function (e) {
                e.preventDefault();

                // Obtén la referencia de la tabla
                let tabla = document.getElementById("tablaModulos");

                // Array para almacenar los datos
                var datos = [];

                // Recorre cada fila de la tabla (comenzando desde 1 para omitir la fila de encabezado)
                for (let i = 1; i < tabla.rows.length; i++) {
                    // Obtén las celdas de la fila
                    let celdas = tabla.rows[i].cells;

                    // Accede a los datos de cada celda
                    let moduloInput = $(celdas[0]).find('input');
                    let horastInput = $(celdas[1]).find('input');
                    let horaspInput = $(celdas[2]).find('input');

                    // Obtiene el valor de cada input
                    let modulo = moduloInput.val();
                    let horast = horastInput.val();
                    let horasp = horaspInput.val();

                    datos.push({
                        modulo: modulo,
                        horast: horast,
                        horasp: horasp
                    });
                }

                // Convierte el array a formato JSON
                var datosJSON = JSON.stringify(datos);

                // Realiza la primera llamada AJAX para almacenar los datos principales
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formAgregarPensum')[0].reset();
                        $('#resultados').html(response);

                        // Realiza la segunda llamada AJAX para almacenar los detalles
                        $.ajax({
                            url: 'funciones/pensum.php',
                            type: 'POST',
                            data: {
                                "action": "agregarDet",
                                "datos": datosJSON
                            },

                            success: function (response) {
                                $('#resultados').html(response);
                                loadPensum();
                            }
                        });
                    }
                });
            });

            // Editar det
            $(document).on('click', '.btn-editar-detalle', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_cab = $(this).closest('tr').find('.pensum_cab_id').text();
                var descri = $(this).closest('tr').find('.descri').text();
                var horas_t = $(this).closest('tr').find('.horas_t').text();
                var horas_p = $(this).closest('tr').find('.horas_p').text();

                $('#editIdDet').val(id);
                $('#editModulo').val(descri);
                $('#editHorast').val(horas_t);
                $('#editHorasp').val(horas_p);
            });

            $('#formEditarDetalle').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#resultados').html(response);
                        setTimeout(function () {
                            loadPensum();
                        }, 1000);
                        loadDetalle(id_cab);
                    },
                });
            });
            // Editar cab
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var curso = $(this).closest('tr').find('.curso').text();
                var resolucion = $(this).closest('tr').find('.resolucion').text();
                var fecha_res = $(this).closest('tr').find('.fecha_res_sf').text();
                var modalidad = $(this).closest('tr').find('.modalidad').text();
                var estado = $(this).closest('tr').find('.estado').text();
                var obs = $(this).closest('tr').find('.obs').text();

                $('#editIdCab').val(id);
                $('#editCursoCab').val(curso);
                $('#editResolucion').val(resolucion);
                $('#editFechaRes').val(fecha_res);
                $('#editModalidad').val(modalidad);
                $("select.editModalidad selected").val(modalidad).change();
                $('#editEstado').val(estado);
                $("select.editEstado selected").val(estado).change();
                $('#editObs').val(obs);
            });

            $('#formEditarPensum').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function (response) {
                        $('#resultados').html(response);
                        loadPensum();
                    },
                });
            });

            // Eliminar
            $(document).on('click', '.btn-eliminar-pensum-detalle', function () {
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
                                url: 'funciones/pensum.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },

                                success: function (response) {
                                    $('#resultados').html(response);
                                    loadPensum();
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
                        url: 'funciones/pensum.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
                        },

                        success: function (response) {
                            $('#tablaPensums').html(response);
                        }
                    });
                }
                cargarPagina(1); // Carga la primera página al cargar la página
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    cargarPagina(pagina);
                });
            });

        });
        // Cargar tabla
        function loadPensum() {
            $.ajax({
                url: 'funciones/pensum.php',
                type: 'POST',
                data: {
                    action: 'listar'
                },
                success: function (response) {
                    $('#tablaPensums').html(response);
                }
            });
        }
        // Cargar tabla eventos
        function loadDetalle(id) {
            $.ajax({
                url: 'funciones/pensum.php',
                type: 'POST',
                data: {
                    action: 'verDetalle',
                    id: id
                },
                success: function (response) {
                    $('#tablaDetalle').html(response);
                }
            });
        }
    </script>
    <style>
        .row>* {
            margin: .5rem 0px .5rem 0px;
        }
    </style>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        include("Modals/pensums.php");
        ?>
    </div>
    <div class="container">
        <h2>Pensums</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i
                    class="bi bi-search"></i> Buscar</button>
        </div>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar pensum</button>
        </div>

        <!-- Mensaje error/exito -->
        <div id="resultados"></div>

        <!-- Tabla -->
        <div id="tablaPensums"></div>
    </div>


</body>

</html>