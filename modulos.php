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
                    success: function (response) {
                        $('#formGenerarModulos')[0].reset();
                        loadModulos();
                        $('#resultado').html(response);
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
            $(document).on('click', '.btn-editar', function () {
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
                    success: function (response) {
                        loadModulos();
                        $('#resultado').html(response);
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
                                url: 'funciones/modulo.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function (response) {
                                    loadModulos();
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
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/modulo.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
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
        ?>
    </div>
    <div class="container">
        <h2>Modulos</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalGenerar'> <i
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
        <div id="resultado"></div>
        
        <!-- Tabla -->
        <div id="tablaModulo"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalGenerar" tabindex="-1" aria-labelledby="modalGenerarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalGenerarLabel">Agregar modulo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGenerarModulos">
                        <input type="hidden" name="action" value="generar">
                        <div class="row">
                            <div class="mb-3">
                                <label for='cursos'>Pensums</label>
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM curso_v";
                                $resultado = pg_query($conn, $sql);
                                if (pg_num_rows($resultado) > 0) {
                                    echo "<select class='form-select  w-100 keep'  name='pensum' required>";
                                    echo "<option selected disabled>Seleccione pensum</option>";
                                    while ($fila = pg_fetch_assoc($resultado)) {
                                        echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . " » " . $fila['descripcion'] . " » Turno " . $fila['turno'] . "</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo "<select class='form-select  w-100 keep' aria-label='Disabled select example'>";
                                    echo "<option selected disabled>No hay cursos</option>";
                                    echo "</select>";

                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                    cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
        data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Asignar docente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarModulo">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="row">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="ci-input"
                                    placeholder="Ci del docente" autocomplete="off" required>
                                <input type="hidden" id="id_docente" name="docente">
                                <div id="suggestions"></div>
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
</body>

</html>