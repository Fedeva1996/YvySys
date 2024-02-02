<!-- Formulario para agregar modulo-->
<div class="modal fade" id="modalGenerarModulo" tabindex="-1" aria-labelledby="modalGenerarModuloLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalGenerarModuloLabel">Agregar modulo</h1>
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
                            $resultados = pg_query($conn, $sql);
                            if (pg_num_rows($resultados) > 0) {
                                echo "<select class='form-select  w-100 keep'  name='pensum' required>";
                                echo "<option selected disabled>Seleccione pensum</option>";
                                while ($fila = pg_fetch_assoc($resultados)) {
                                    echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . " » " . $fila['descripcion'] . " » Turno " . $fila['turno'] . "</option>";
                                }
                                echo "</select>";
                            } else {
                                echo "<select class='form-select  w-100 keep' aria-label='Disabled'>";
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
<!-- Modal para editar modulo-->
<div class="modal fade" id="modalEditarModulo" tabindex="-1" aria-labelledby="modalEditarModuloLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarModuloLabel">Asignar docente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarModulo">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" id="ci-input" placeholder="Ci del docente"
                                autocomplete="off" required>
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
