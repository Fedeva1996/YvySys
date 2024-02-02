<!-- Formulario para generar curso -->
<div class="modal fade" id="modalGenerarCurso" tabindex="-1" aria-labelledby="modalGenerarCursoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalGenerarCursoLabel">Agregar curso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarCurso">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM pensum_cab";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Pensum</label>";
                                    echo "<select class='form-select  w-100'  name='id_pensum' required>";
                                    echo "<option selected disabled>Seleccione pensum</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . " » " . $fila['resolucion'] . " » " . $fila['fecha_res'] . "</option>";
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
                                $sql = "SELECT * FROM periodo";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Periodo</label>";
                                    echo "<select class='form-select  w-100'  name='id_periodo' required>";
                                    echo "<option selected disabled>Seleccione periodo</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_periodo'] . "'>" . $fila['ano'] . " | " . $fila['descripcion'] . "</option>";
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
                                $sql = "SELECT * FROM turno";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<label for='fecha'>Turno</label>";
                                    echo "<select class='form-select  w-100'  name='id_turno' required>";
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
<!-- Modal para editar curso-->
<div class="modal fade" id="modalEditarCurso" tabindex="-1" aria-labelledby="modalEditarCursoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarCursoLabel">Editar curso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCurso">
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