<!-- Formulario para agregar alumno-->
<div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarAlumnoLabel">Agregar Nuevo Alumno</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarAlumno">
                    <input class="input-group-text" type="hidden" name="action" value="agregarNuevo">
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
                                <input class="input-group-text w-100" type="text" name="telefono" placeholder="Teléfono"
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
<!-- Formulario para agregar alumno existentes -->
<div class="modal fade" id="modalAgregarAlumnoExistente" tabindex="-1"
    aria-labelledby="modalAgregarAlumnoExistenteLabel" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarAlumnoExistenteLabel">Agregar existente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarAlumnoExistente">
                    <input type="hidden" name="action" value="agregarExistente">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="ci-input"
                                    placeholder="CI del alumno" autocomplete="off" required>
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
<!-- Modal para editar alumno -->
<div class="modal fade" id="modalEditarAlumno" tabindex="-1" aria-labelledby="modalEditarAlumnoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarAlumnoLabel">Editar Alumno</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarAlumno">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="ci" id="editCi" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="nombre" id="editNombre"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="apellido" id="editApellido"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="date" name="fecha_nac" id="editFechaNac"
                                    required>
                            </div>
                            <div class="mb-3">
                                <select class="editSexo input-group-text w-100" id="editSexo" name="sexo" required>
                                    <option selected disabled>Seleccione sexo</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="email" name="correo" id="editCorreo"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="nacionalidad"
                                    id="editNacionalidad" required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="direccion" id="editDireccion"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="telefono" id="editTelefono"
                                    required>
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
<!-- Modal para detalles -->
<div class="modal fade modal-lg" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalDetalleLabel">Detalle</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabla -->
                <div id="tablaDetalle"></div>
            </div>
        </div>
    </div>
</div>