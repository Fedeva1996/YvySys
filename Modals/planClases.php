<!-- Formulario para buscar por curso -->
<div class="modal fade" id="modalBuscarFecha" tabindex="-1" aria-labelledby="modalBuscarFechaLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formBuscarPlanClase">
                    <input type="hidden" name="action" value="buscarPlanClase">
                    <input type="hidden" name="pagina" value="1">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha de la clase">Fecha</label>
                                <input class="input-group-text w-100" type="date" name="fecha_p">
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
<!-- Formulario para agregar cab-->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarPlanClase">
                    <input class="input-group-text" type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Inicio</span>
                                <input class="form-control" type="date" name="fecha_ini" required>
                            </div>
                            <div class="mb-3">
                                <?php

                                ?>
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="text" name="obs" placeholder="Observaciones">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Fin</span>
                                <input class="form-control" type="date" name="fecha_fin" required>
                            </div>
                            <div class="mb-3">
                                <?php

                                ?>
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="text" name="docente_r"
                                    placeholder="Docente de reemplazo">
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
                <form id="formAgregarPlanClaseDet">
                    <input class="input-group-text" type="hidden" name="action" value="agregarDet">
                    <div class="mb-3">
                        <?php

                        ?>
                    </div>
                    <div class="mb-3">
                        <input class="input-group-text w-100" type="text" name="procesoClase"
                            placeholder="Proceso de clase" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" name="competencia"
                                    placeholder="Competencia" required></textarea>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" name="indicadores"
                                    placeholder="Indicadores" required></textarea>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" name="contenido"
                                    placeholder="Contenido" required></textarea>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" name="actividad"
                                    placeholder="Actividad" required></textarea>
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
<!-- Formulario para editar plan clase-->
<div class="modal fade" id="modalEditarCab" tabindex="-1" aria-labelledby="modalEditarCabLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarCabLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPlanClaseCab">
                    <input type="hidden" name="action" value="editarCab">
                    <input type="hidden" name="idCab" id="editIdCab">
                    <div class="col">
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Inicio</span>
                            <input class="form-control" type="date" id="editFechaIni" name="fecha_ini" required>
                        </div>
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Fin</span>
                            <input class="form-control" type="date" id="editFechaFin" name="fecha_fin" required>
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
<!-- Modal para editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar PlanClase</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPlanClase">
                    <input type="hidden" name="action" value="editarDet">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">

                    </div>
                    <div class="mb-3">
                        <input class="input-group-text w-100" id="editIdPro" type="text" name="procesoClase"
                            placeholder="Proceso de clase" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" id="editCompetencia"
                                    name="competencia" placeholder="Competencia" required></textarea>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" id="editIndicadores"
                                    name="indicadores" placeholder="Indicadores" required></textarea>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" id="editContenido"
                                    name="contenido" placeholder="Contenido" required></textarea>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" aria-label="With textarea" id="editActividad"
                                    name="actividad" placeholder="Actividad" required></textarea>
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