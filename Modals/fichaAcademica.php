<!-- Formulario para generar ficha -->
<div class="modal fade" id="modalGenerar" tabindex="-1" aria-labelledby="modalGenerarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalGenerarLabel">Generar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formGenerarFichaAcademica">
                    <input type="hidden" name="action" value="generar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="ci-input"
                                    placeholder="Ci del alumno" autocomplete="off" required>
                                <input type="hidden" id="id_alumno" name="id_alumno">
                                <div id="suggestions"></div>
                            </div>
                            <div class="mb-3">
                                <label for='curso'>Curso</label>
                                <select class='form-select  w-100' name='curso' id="cursoSelect" required>
                                    <option selected disabled>Seleccione curso</option>
                                </select>
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