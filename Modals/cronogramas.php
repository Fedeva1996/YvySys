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
                                <label for="fecha_ini">Fecha Inicio</label>
                                <input class="input-group-text w-100" type="date" name="fecha_ini" id="editFecha_ini"
                                    placeholder="Fecha de inicio" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha_fin">Fecha Fin</label>
                                <input class="input-group-text w-100" type="date" name="fecha_fin" id="editFecha_fin"
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
                <h1 class="modal-title fs-5" id="modalEditarEventoLabel">Editar Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarEvento">
                    <div class="row">
                        <input type="hidden" name="action" value="editarDet">
                        <input type="hidden" name="id" id="editIdDet">
                        <div class="col">
                            <div class="mb-3">
                                <label for='fecha'>Estado</label>
                                <select class='form-select  w-100' name='estado' id='editEstado' required>
                                    <option value="t">Activa</option>
                                    <option value="f">Suspendida</option>
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
                <form id="formAsignarModulo">
                    <!-- modulos -->
                    <div id='formModulos'></div>
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
<!-- Formulario para generar eventos-->
<div class="modal fade" id="modalGenerar" tabindex="-1" aria-labelledby="modalGenerarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalGenerarLabel">Generar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> Para seleccionar multiples días, mantener presionado Ctrl en el
                    teclado + click en cada día.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <form id="formGenerarEventos">
                    <div class="row">
                        <input type="hidden" name="id" id="genId">
                        <div class="mb-2">
                            <label class="form-label">Días de clase:</label>
                            <select class="form-select dias" multiple size="6" aria-label="Multiple Size 6 select">
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miercoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sabado</option>
                            </select>
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