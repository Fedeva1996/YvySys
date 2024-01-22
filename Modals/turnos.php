    <!-- Formulario para agregar turnos-->
    <div class="modal fade" id="modalAgregarTurno" tabindex="-1" aria-labelledby="modalAgregarTurnoLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgregarTurnoLabel">Agregar turno</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarTurno">
                        <input type="hidden" name="action" value="agregar">
                        <div class="row">
                            <div class="mb-3">
                                <select class="input-group-text w-100" name="descri" aria-placeholder="turno" required>
                                    <option selected disabled>Seleccione turno</option>
                                    <option value="Manana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="horario" placeholder="Horario. Ej: 20:00 a 22:00" required>
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
    <!-- Modal para editar Turno-->
    <div class="modal fade" id="modalEditarTurno" tabindex="-1" aria-labelledby="modalEditarTurnoLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarTurnoLabel">Editar Alumno</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarTurno">
                        <div class="row">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <select class="input-group-text w-100" name="descri" id="editDescri" required>
                                    <option selected disabled>Seleccione turno</option>
                                    <option value="Manana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="horario" id="editHorario" required>
                            </div>
                            <div class="mb-3">
                                <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                    <option value="0">Inactivo</option>
                                    <option value="1">Activo</option>
                                </select>
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