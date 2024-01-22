<!-- Modal ver inscripciones -->
<div class="modal fade" id="modalInscripciones" tabindex="-1" aria-labelledby="modalInscripcionesLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarLabel">Inscripciones</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="tablaInscripciones"></div>
            </div>
        </div>
    </div>
</div>
<!-- Formulario para agregar docente-->
<div class="modal fade" id="modalAgregarDocente" tabindex="-1" aria-labelledby="modalAgregarDocenteLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarDocenteLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarDocente">
                    <input class="input-group-text" type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="ci"
                                    placeholder="Documento de identidad" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="nombre" placeholder="Nombre"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="apellido" placeholder="Apellido"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="date" name="fecha_nac"
                                    placeholder="Fecha de nacimiento" required>
                            </div>
                            <div class="mb-3">
                                <select class="input-group-text w-100" style="width: 95%;" name="sexo" required>
                                    <option selected disabled>Seleccione sexo</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="email" name="correo" placeholder="Correo"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="nacionalidad"
                                    placeholder="Nacionalidad" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="direccion"
                                    placeholder="Dirección" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="telefono" placeholder="Teléfomo"
                                    required>
                            </div>
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
<!-- Formulario para agregar docente existente -->
<div class="modal fade" id="modalAgregarDocenteExistente" tabindex="-1"
    aria-labelledby="modalAgregarDocenteExistenetLabel" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarDocenteExistenteLabel">Agregar existente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarExistente">
                    <input type="hidden" name="action" value="agregarExistente">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="ci-input"
                                    placeholder="Ci del docente" autocomplete="off" required>
                                <input type="hidden" id="id" name="id">
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
</div>
