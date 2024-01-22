<!-- Formulario para agregar periodo-->
<div class="modal fade" id="modalAgregarPeriodo" tabindex="-1" aria-labelledby="modalAgregarPeriodoLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarPeriodoLabel">Agregar periodo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarPeriodo">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="ano" placeholder="Año" required>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="descri"
                                placeholder="Descripción. Ej:Taller enero" required>
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
<!-- Modal para editar periodo-->
<div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-labelledby="modalEditarPeriodoLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarPeriodoLabel">Editar Alumno</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPeriodo">
                    <div class="row">
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="ano" id="editAno" required>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="descri" id="editDescri" required>
                        </div>
                        <div class="mb-3">
                            <select class="input-group-text w-100" class="editEstado" id="editEstado" name="estado">
                                <option value="S">Sin iniciar</option>
                                <option value="C">En curso</option>
                                <option value="F">Finalizado</option>
                            </select>
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