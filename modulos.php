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
        $(document).ready(function() {
            // Cargar la tabla al cargar la página
            loadModulos();

            // Agregar nuevo
            $('#formAgregarModulo').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formAgregarModulo')[0].reset();
                        loadModulos();
                        $('#sweetAlerts').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function() {
                var id = $(this).closest('tr').find('.id').text();
                var descri = $(this).closest('tr').find('.descri').text();
                var id_pensum = $(this).closest('tr').find('.id_pensum').text();
                var id_pensum_det = $(this).closest('tr').find('.id_pensum_det').text();
                var id_persona = $(this).closest('tr').find('.id_persona').text();

                $('#editId').val(id);
                $('#editDescri').val(descri);
                $("#editPensum_cab").val(id_pensum);
                $("select.editPensum_cab selected").val(id_pensum).change();
                $("#editModulo").val(id_pensum_det);
                $("select.editModulo selected").val(id_pensum_det).change();
                $("#editPersona").val(id_persona);
                $("select.editPersona selected").val(id_persona).change();
            });

            $('#formEditarModulo').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        loadModulos();
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
                                url: 'funciones/modulo.php',
                                type: 'POST',
                                data: {
                                    action: 'eliminar',
                                    id: id
                                },
                                success: function(response) {
                                    loadModulos();
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
                        url: 'funciones/modulo.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina
                        },
                        success: function(response) {
                            $('#tablaModulo').html(response);
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
            $('#formBuscarModulo').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/modulo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tablaModulo').html(response);
                    }
                });
            });
            $('#pensum_cab').change(function() {
                var pensumCabId = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: 'funciones/modulo.php',
                    data: {
                        action: "buscarMateria",
                        pensumCabId: pensumCabId
                    },
                    success: function(response) {
                        // Actualiza el segundo select con los modulos devueltos
                        $('#modulos').html(response);
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
                success: function(response) {
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
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalAgregar'> <i class="bi bi-person-add"></i> Agregar</button>
        </div>
        <!-- Formulario para buscar -->
        <div class="mb-3" data-bs-theme="dark">
            <form id="formBuscarModulo">
                <input type="hidden" name="action" value="buscar">
                <div class="input-group mb-2">
                    <input class="input-group-text w-25" type="text" name="buscar" placeholder="Nombre, apellido o Ci">
                </div>
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-dark" onclick="loadModulos()" type="reset"><i class="bi bi-eraser"></i>Limpiar</button>
            </form>
        </div>
        <!-- Tabla -->
        <div id="tablaModulo"></div>
    </div>
    <!-- Formulario para agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar modulo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarModulo">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="descri" placeholder="Nombre" required>
                            </div>
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultado = $conn->query($sql);
                                if ($resultado->num_rows > 0) {
                                    echo "<select class='input-group-text w-100' name='id_pensum' required id='editPensum_cab'>";
                                    echo "<option selected disabled>Seleccione curso</option>";
                                    while ($fila = $resultado->fetch_assoc()) {
                                        echo "<option value='" . $fila['pensum_id'] . "'>" . $fila['descri'] . " | Inicio: " . $fila['fecha_ini'] . "</option>";
                                    }
                                    echo "</select>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    // Agrega el segundo select para los modulos
                                    echo "<select class='input-group-text w-100' name='id_modulo' required id='editModulo'>";
                                    echo "<option selected disabled>Seleccione modulo</option>";
                                    echo "</select>";
                                    echo "</div>";
                                }
                                ?>

                                <div class="mb-3">
                                    <?php
                                    $sql = "SELECT * FROM personas WHERE rol_id = 3";
                                    $resultado = $conn->query($sql);
                                    if ($resultado->num_rows > 0) {
                                        echo "<select class='input-group-text w-100' name='id_persona' id='editPersona' required>";
                                        echo "<option selected disabled>Seleccione docente</option>";
                                        while ($fila = $resultado->fetch_assoc()) {
                                            echo "<option value='" . $fila['id_persona'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
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
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar modulo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarModulo">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="row">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="descri" id="editDescri" required>
                            </div>
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultado = $conn->query($sql);
                                if ($resultado->num_rows > 0) {
                                    echo "<select class='input-group-text w-100' name='id_pensum' required id='pensum_cab'>";
                                    echo "<option selected disabled>Seleccione curso</option>";
                                    while ($fila = $resultado->fetch_assoc()) {
                                        echo "<option value='" . $fila['pensum_id'] . "'>" . $fila['curso'] . "</option>";
                                    }
                                    echo "</select>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    // Agrega el segundo select para los modulos
                                    echo "<select class='input-group-text w-100' name='id_modulo' required id='modulos'>";
                                    echo "<option selected disabled>Seleccione modulo</option>";
                                    echo "</select>";
                                    echo "</div>";
                                }
                                ?>

                                <div class="mb-3">
                                    <?php
                                    $sql = "SELECT * FROM personas WHERE rol_id = 3";
                                    $resultado = $conn->query($sql);
                                    if ($resultado->num_rows > 0) {
                                        echo "<select class='input-group-text w-100' name='id_persona' required id='editPersona'>";
                                        echo "<option selected disabled>Seleccione docente</option>";
                                        while ($fila = $resultado->fetch_assoc()) {
                                            echo "<option value='" . $fila['id_persona'] . "'>" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                    ?>
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
</body>

</html>