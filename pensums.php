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
                        $('#resultado').html(response);

                        // Realiza la segunda llamada AJAX para almacenar los detalles
                        $.ajax({
                            url: 'funciones/pensum.php',
                            type: 'POST',
                            data: {
                                "action": "agregarDet",
                                "datos": datosJSON
                            },
                            success: function (response) {
                                $('#resultado').html(response);
                            }
                        });
                    }
                });
            });

            // Editar det
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var id_cab = $(this).closest('tr').find('.id_cab').text();
                var descri = $(this).closest('tr').find('.descri').text();
                var horas_t = $(this).closest('tr').find('.horas_t').text();
                var horas_p = $(this).closest('tr').find('.horas_p').text();

                $('#editIdDet').val(id);
                $("#editCab").val(id_cab);
                $("select.editCab selected").val(id_cab).change();
                $('#editModulo').val(descri);
                $('#editHorast').val(horas_t);
                $('#editHorasp').val(horas_p);
            });

            $('#formEditarPensum').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#resultado').html(response);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                });
            });
            // Editar cab
            $(document).on('click', '.btn-editar-cab', function () {
                var id = $(this).data('id');
                var curso = $(this).closest('.head').find('.curso').val();
                var resolucion = $(this).closest('.head').find('.resolucion').val();
                var fecha_res = $(this).closest('.head').find('.fecha_res_sf').val();
                var modalidad = $(this).closest('.head').find('.modalidad').val();
                var obs = $(this).closest('.head').find('.obs').val();

                console.log(id, curso, resolucion, fecha_res, modalidad, obs);

                $('#editIdCab').val(id);
                $('#editCursoCab').val(curso);
                $('#editResolucion').val(resolucion);
                $('#editFechaRes').val(fecha_res);
                $('#editModalidad').val(modalidad);
                $("select.editModalidad selected").val(modalidad).change();
                $('#editObs').val(obs);
            });

            $('#formEditarPensumCab').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/pensum.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#resultado').html(response);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
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
                                url: 'funciones/pensum.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function (response) {
                                    loadAlumnos();
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
            $(document).ready(function () {
                function cargarPagina(pagina, curso) {
                    $.ajax({
                        url: 'funciones/pensum.php',
                        type: 'POST',
                        data: {
                            action: 'buscarPensum',
                            curso: curso,
                        },
                        success: function (response) {
                            $('#tablaPensums').html(response);
                        }
                    });
                }
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');
                    var curso = $(this).data('curso');

                    cargarPagina(pagina, curso);
                });
            });
        });
        const agregarFila = () => {
            document.getElementById('tablaModulos').insertRow(-1).innerHTML =
                '<td><input class="form-control form-control-sm" type="text"></td><td><input class="form-control form-control-sm" type="text"></td><td><input class="form-control form-control-sm" type="text"></td>'
        }

        const eliminarFila = () => {
            const table = document.getElementById('tablaModulos')
            const rowCount = table.rows.length

            if (rowCount <= 1)
                alert('No se puede eliminar el encabezado')
            else
                table.deleteRow(rowCount - 1)
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
        ?>
    </div>
    <div class="container">
        <h2>Pensums</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i
                    class="bi bi-search"></i> Buscar</button>
        </div>
        <!-- Formulario para buscar por curso -->
        <div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel"
            aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="formBuscarPensum">
                            <input type="hidden" name="action" value="buscarPensum">
                            <input type="hidden" name="pagina" value="1">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for='pensums'>Pensums</label>
                                        <?php
                                        include 'db_connect.php';
                                        $sql = "SELECT * FROM pensum_cab";
                                        $resultado = pg_query($conn, $sql);
                                        if (pg_num_rows($resultado) > 0) {
                                            echo "<select class='form-select  w-100 keep'  name='id' required>";
                                            echo "<option selected disabled>Seleccione pensum</option>";
                                            while ($fila = pg_fetch_assoc($resultado)) {
                                                echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . " » " . $fila['resolucion'] . " » " . $fila['fecha_res'] . "</option>";
                                            }
                                            echo "</select>";
                                        } else {
                                            echo "<select class='form-select  w-100 keep' aria-label='Disabled select example'>";
                                            echo "<option selected disabled>No hay pensums</option>";
                                            echo "</select>";

                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit"><i
                                            class="bi bi-search"></i> Buscar</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i
                    class="bi bi-person-add"></i> Agregar pensum</button>
        </div>
        <!-- Formulario para agregar pensum-->
        <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
            data-bs-theme="dark">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle"></i> El <strong>total</strong> se suma automaticamente al
                                guardar.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <form id="formAgregarPensum">
                            <input class="input-group-text" type="hidden" name="action" value="agregar">
                            <div class="row">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="curso"
                                        placeholder="Nombre del curso" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="resolucion"
                                        placeholder="Resolución de apertura" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="date" name="fecha_res"
                                            placeholder="Fecha de Resolución" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <select class='form-select  w-100' name='modalidad' required>
                                            <option disabled selected>Seleccion la modalidad</option>
                                            <option value="virtual">Virtual</option>
                                            <option value="semi">Semi presencial</option>
                                            <option value="presencial">Presencial</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <input class="input-group-text w-100" type="text" name="obs" placeholder="Obs"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-dark" id="tablaModulos">
                                        <thead>
                                            <tr>
                                                <th scope="col">Modulo</th>
                                                <th scope="col">Horas teoricas</th>
                                                <th scope="col">Horas practicas</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary mr-2"
                                            onclick="agregarFila()">Agregar Fila</button>
                                        <button type="button" class="btn btn-danger" onclick="eliminarFila()">Eliminar
                                            Fila</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                    cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mensaje error/exito -->
        <div id="resultado"></div>

        <!-- Tabla -->
        <div id="tablaPensums"></div>
    </div>

    <!-- Formulario para editar pensum cab-->
    <div class="modal fade" id="modalEditarCab" tabindex="-1" aria-labelledby="modalEditarCabLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarCabLabel">Editar cabecera</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPensumCab">
                        <input type="hidden" name="action" value="editarCab">
                        <input type="hidden" name="idCab" id="editIdCab">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="curso" id="editCursoCab"
                                        placeholder="Nombre del curso" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="resolucion" id="editResolucion"
                                    placeholder="Resolución de apertura">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="date" name="fecha_res" id="editFechaRes"
                                        placeholder="Fecha de Resolución">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <select class='form-select  w-100' name='modalidad' required id="editModalidad">
                                        <option disabled selected>Seleccion la modalidad</option>
                                        <option value="virtual">Virtual</option>
                                        <option value="semi">Semi presencial</option>
                                        <option value="presencial">Presencial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="text" name="obs" placeholder="Obs"
                                        id="editObs">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar pensum det-->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Pensum</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPensum">
                        <input type="hidden" name="action" value="editarDet">
                        <input type="hidden" name="id" id="editIdDet">
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT * FROM pensum_cab";
                            $resultado = pg_query($conn, $sql);
                            if (pg_num_rows($resultado) > 0) {
                                echo "<select id='editCab' class='input-group-text w-100' name='id_pensum' required>";
                                echo "<option selected disabled>Seleccione cabecera</option>";
                                while ($fila = pg_fetch_assoc($resultado)) {
                                    echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="modulo" id="editModulo"
                                placeholder="Nombre del modulo" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="number" name="horast" id='editHorast'
                                        placeholder="Horas teoricas" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" type="number" name="horasp" id='editHorasp'
                                        placeholder="Horas practicas" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>