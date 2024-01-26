<!-- Formulario para buscar por curso -->
<div class="modal fade" id="modalBuscarFecha" tabindex="-1" aria-labelledby="modalBuscarFechaLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formBuscarPlanExamen">
                    <input type="hidden" name="action" value="buscarPlanExamen">
                    <input type="hidden" name="pagina" value="1">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="fecha">Fecha</label>
                                <input class="input-group-text w-100" type="date" name="fecha_p">
                            </div>
                            <div class="mb-3">
                                <?php

                                ?>
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
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarPlanExamen" enctype="multipart/form-data">
                    <input class="input-group-text" type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="examen" name="examen" required>
                            <label class="input-group-text" for="examen">PDF</label>
                        </div>
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT * FROM modulo_v";
                            $resultado = pg_query($conn, $sql);
                            if (pg_num_rows($resultado) > 0) {
                                echo "<select class='form-select  w-100 keep'  name='modulo' required>";
                                echo "<option selected disabled>Seleccione modulo</option>";
                                while ($fila = pg_fetch_assoc($resultado)) {
                                    echo "<option value='" . $fila['id_modulo'] . "'>" . $fila['modulo'] . "</option>";
                                }
                                echo "</select>";
                            } else {
                                echo "<select class='form-select  w-100 keep' aria-label='Disabled select example'>";
                                echo "<option selected disabled>No hay modulos</option>";
                                echo "</select>";

                            }
                            ?>
                        </div>
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Fecha</span>
                                <input class="form-control" type="date" name="fecha" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping">Recuperatorio</span>
                                <input class="form-control" type="date" name="fecha_recuperatorio" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <select class="input-group-text w-100" name="tipo" required>
                                <option selected disabled>Seleccione tipo</option>
                                <option value="P">Parcial</option>
                                <option value="F">Final</option>
                                <option value="R">Recuperatorio</option>
                                <option value="E">Extraordinario</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="number" name="puntaje" placeholder="Puntaje"
                                required>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="obs" placeholder="Observaciones">
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
                <form action="reportes/planesExamen.php" method="post" id="formReporteInscripcion">
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
<!-- Formulario para editar plan examen-->
<div class="modal fade" id="modalEditarCab" tabindex="-1" aria-labelledby="modalEditarCabLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarCabLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPlanExamenCab">
                    <input type="hidden" name="action" value="editarCab">
                    <input type="hidden" name="idCab" id="editIdCab">
                    <div class="col">
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Fecha</span>
                            <input class="form-control" type="date" id="editFecha" name="fecha" required>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Recuperatorio</span>
                            <input class="form-control" type="date" id="editRecuperatorio" name="recuperatorio"
                                required>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <input class="form-control" type="Text" id="editPuntaje" name="puntaje"
                                placeholder="Puntaje" required>
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
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar PlanExamen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPlanExamen">
                    <input type="hidden" name="action" value="editarDet">
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="mb-3">
                            <?php

                            ?>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="number" id='editPuntaje' name="puntaje"
                                placeholder="Puntaje hecho" required>
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