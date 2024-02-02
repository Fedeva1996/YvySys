<!-- Formulario para buscar por modulo -->
<div class="modal fade" id="modalBuscarModulo" tabindex="-1" aria-labelledby="modalBuscarModuloLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalBuscarModuloLabel">Agregar asistencia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formBuscarAsistencia">
                    <input type="hidden" name="action" value="listar">
                    <input type="hidden" name="pagina" value="1">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php

                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha">Fecha</label>
                                <input class="input-group-text w-100" type="date" name="fecha" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit"><i
                                    class="bi bi-search"></i> Buscar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Formulario para agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar asistencia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarAsistencia">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="planClase-input"
                                    placeholder="Nombre de materia, curso o docente" autocomplete="off" required>
                                <input type="hidden" id="id-plan" name="id-plan">
                                <div id="suggestions"></div>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" readonly type="datetime" id="fecha" name="fecha"
                                    value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            <div class="mb-3">
                                <input class="form-check-input" type="checkbox" value="1" name="asistenciaD"
                                    id="asistenciaD" checked>
                                <label class="form-check-label" for="asistenciaD">
                                    Docente asistió
                                </label>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="obs" name="obs"
                                    placeholder="Observaciones">
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
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Inscripción</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarAsistencia">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <select class="editEstado input-group-text w-100" id="editEstado" name="estado" required>
                            <option selected disabled>Seleccione asistencia</option>
                            <option value="1">Presente</option>
                            <option value="0">Ausente</option>
                        </select>
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
<!-- Modal para asistencias -->
<div class="modal fade modal-lg" id="modalAsistencias" tabindex="-1" aria-labelledby="modalAsistenciasLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAsistenciasLabel">Asistencias</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- mostrar exito/error -->
                <div id="resultados2"></div>
                <!-- Tabla -->
                <div id="tablaVerAsistencias"></div>
            </div>
        </div>
    </div>
</div>