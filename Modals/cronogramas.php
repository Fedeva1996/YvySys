<!-- Formulario para agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar cronograma</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarCronograma">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for='curso'>Curso</label>
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select class='form-select  w-100'  name='curso' required>";
                                    echo "<option selected disabled>Seleccione curso</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha_ini">Fecha Inicio</label>
                                <input class="input-group-text w-100" type="date" name="fecha_ini"
                                    placeholder="Fecha de inicio" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha_fin">Fecha Fin</label>
                                <input class="input-group-text w-100" type="date" name="fecha_fin"
                                    placeholder="Fecha de finalización" required>
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
<!-- Modal para editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar cronograma</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCronograma">
                    <div class="row">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM pensum_cab";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Pensum</label>";
                                    echo "<select class='form-select  w-100'  name='id_pensum' required id='editPensum'>";
                                    echo "<option selected disabled>Seleccione pensum</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM periodo";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Periodo</label>";
                                    echo "<select class='form-select  w-100'  name='id_periodo' required id='editPeriodo'>";
                                    echo "<option selected disabled>Seleccione periodo</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_periodo'] . "'>" . $fila['ano'] . " | " . $fila['descripcion'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM turno";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Turno</label>";
                                    echo "<select class='form-select  w-100'  name='id_turno' required id='editTurno>";
                                    echo "<option selected disabled>Seleccione turno</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_turno'] . "'>" . $fila['descri'] . " | " . $fila['horario'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha">Tipo</label>
                                <select class="input-group-text w-100" name="tipo" required id='editTipo'>
                                    <option selected disabled>Seleccione tipo</option>
                                    <option value="Taller">Taller</option>
                                    <option value="Actualizacion">Actualización</option>
                                    <option value="Tecnicatura">Tecnicatura</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha">Estado</label>
                                <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                    <option value="S">Sin iniciar</option>
                                    <option value="C">En curso</option>
                                    <option value="F">Finalizado</option>
                                </select>
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
<!-- Modal para eventos -->
<div class="modal fade modal-lg" id="modalEventos" tabindex="-1" aria-labelledby="modalEventosLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEventosLabel">Eventos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabla -->
                <div id="tablaEventos"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para editar evento-->
<div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-labelledby="modalEditarEventoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarEventoLabel">Editar cronograma</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCronograma">
                    <div class="row">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM modulos";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Modulos</label>";
                                    echo "<select class='form-select  w-100'  name='id_modulo' required id='editModulo'>";
                                    echo "<option selected disabled>Seleccione modulo</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_modulo'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM periodo";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Periodo</label>";
                                    echo "<select class='form-select  w-100'  name='id_periodo' required id='editPeriodo'>";
                                    echo "<option selected disabled>Seleccione periodo</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_periodo'] . "'>" . $fila['ano'] . " | " . $fila['descripcion'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-bs-target="#modalEventos" data-bs-toggle="modal"
                            type="submit">Guardar
                            cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-target="#modalEventos"
                            data-bs-toggle="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Formulario para asignar modulos-->
<div class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="modalAsignarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAsignarLabel">Asignar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle"></i> <strong>Cuidado!</strong> las fechas no deben sobreponerle, si
                        sucede, el siguiente modulo ocupara esa fecha.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <form id="formAsignarModulo">
                    <?php
                    include 'db_connect.php';
                    $sql = "SELECT * FROM modulos";
                    $resultados = pg_query($conn, $sql);
                    if (pg_num_rows($resultados) > 0) {
                        while ($fila = pg_fetch_assoc($resultados)) {
                            echo "<div class='mb-3'>";
                            echo "<div class='row'>";
                            echo "<input class='id' hidden value='" . $fila['id_modulo'] . "'>";
                            echo "<div class='col'><input disabled class='form-control form-control-sm' name='modulo_id' type='text' value='" . $fila['descri'] . "'></div>";
                            echo "<div class='col'><input class='form-control form-control-sm' name='inicio' type='date'></div>";
                            echo "<div class='col'><input class='form-control form-control-sm' name='fin' type='date'></div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
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