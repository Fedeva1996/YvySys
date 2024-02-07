<!-- Formulario para buscar por curso -->
<div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formBuscarProcesoClase">
                    <input type="hidden" name="action" value="buscarProcesoClase">
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
                                <input class="input-group-text w-100" type="date" name="fecha_p" required>
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
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar procesoClase</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarProcesoClase">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php

                                ?>
                            </div>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                <input class="form-control" type="date" name="fecha_entrega" required>
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="number" name="puntaje" placeholder="Puntaje">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="text" name="descripcion" placeholder="Descripción">
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
<!-- Formulario para agregar det -->
<div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-labelledby="modalAgregarDetalleLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarDetalleLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarProcesoClaseDet">
                    <input class="input-group-text" type="hidden" name="action" value="agregarDet">
                    <div class="mb-3">
                        <?php

                        ?>
                    </div>
                    <div class="mb-3">
                        <?php

                        ?>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                <input class="form-control" type="date" name="fecha_entrega" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="puntaje" placeholder="Puntaje"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="submit">Guardar cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Formulario para ir a reporte -->
<div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalReporteLabel">Agregar inscripcion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="reportes/procesosClase.php" method="post" id="formReporteInscripcion">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php

                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Fecha</span>
                                <input class="form-control" type="date" id="fecha" name="fecha" required>
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
                <form id="formEditarProcesoClase">
                    <input type="hidden" name="action" value="editarDet">
                    <div class="row">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group flex-nowrap mb-3">
                                    <span class="input-group-text" id="addon-wrapping">Entrega</span>
                                    <input class="form-control" id="editFechaEntrega" type="date" name="fecha_entrega"
                                        required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <input class="input-group-text w-100" id="editPuntaje" type="number" name="puntaje"
                                        placeholder="Puntaje" required>
                                </div>
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