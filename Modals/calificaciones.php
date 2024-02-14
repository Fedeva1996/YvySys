<!-- Formulario para buscar por curso -->
<div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formBuscarCurso">
                    <input type="hidden" name="action" value="buscar">
                    <input type="hidden" name="pagina" value="1">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                            </div>
                            <div class="mb-3">

                                <select class="input-group-text w-100" name='id_materia' id="id_materia">
                                    <option value="">Seleccionar materia</option>

                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                    cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
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
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar calificacion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCalificacion">
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" disabled id="editNombre">
                            </div>
                            <div class="mb-3">
                                <input class="input-number input-group-text w-100" type="number" name="proceso"
                                    id="editProceso" placeholder="Puntaje de procesos">
                            </div>
                            <div class="mb-3">
                                <input class="input-number input-group-text w-100" type="number" name="trabajo"
                                    id="editTrabajo" placeholder="Puntaje de trabajos">
                            </div>
                            <div class="mb-3">
                                <input class="input-number input-group-text w-100" type="number" name="examen"
                                    id="editExamen" placeholder="Puntaje de exámenes">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-number input-group-text w-100" type="number" readonly name="total"
                                    id="editTotal">
                            </div>
                            <div class="mb-3">
                                <input class="input-number input-group-text w-100" readonly type="number"
                                    name="calificacion" id="editCalificacion">
                            </div>
                            <input type="hidden" name="paso" id="editPaso">
                            <div class="mb-3">
                                <select class="input-group-text w-100" disabled id="selectPaso" name="selectPaso">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                            <input class="input-group-text w-100" type="text" name="obs" id="editObs"
                                placeholder="Observación">

                            <div class="modal-footer">
                                <button class="btn btn-outline-primary" data-bs-dismiss="modal" type="submit">Guardar
                                    cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>