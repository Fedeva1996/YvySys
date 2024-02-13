<!-- Formulario para agregar pensum-->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle"></i> El <strong>total</strong> se suma automaticamente al
                        guardar.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <form id="formAgregarPensum">
                    <input class="input-group-text" type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="curso"
                                placeholder="Nombre del curso" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="resolucion"
                                placeholder="Resolución de apertura" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="date" name="fecha_res"
                                    placeholder="Fecha de Resolución" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <select class='form-select  w-100' name='modalidad' required>
                                    <option disabled selected>Seleccion la modalidad</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="semi">Semi presencial</option>
                                    <option value="presencial">Presencial</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="obs" placeholder="Obs" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-dark" id="tablaModulos">
                                <thead>
                                    <tr>
                                        <th scope="col">Modulo</th>
                                        <th scope="col">Horas teoricas</th>
                                        <th scope="col">Horas practicas</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary mr-2" onclick="agregarFila()">Agregar
                                    Fila</button>
                                <button type="button" class="btn btn-danger" onclick="eliminarFila()">Eliminar
                                    Fila</button>
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
<!-- Formulario para editar pensum cab-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar cabecera</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPensum">
                    <input type="hidden" name="action" value="editarCab">
                    <input type="hidden" name="idCab" id="editIdCab">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="curso" id="editCursoCab"
                                    placeholder="Nombre del curso" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="resolucion" id="editResolucion"
                                placeholder="Resolución de apertura">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="date" name="fecha_res" id="editFechaRes"
                                    placeholder="Fecha de Resolución">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <select class='form-select  w-100' name='modalidad' required id="editModalidad">
                                    <option disabled selected>Seleccionar la modalidad</option>
                                    <option value="Virtual">Virtual</option>
                                    <option value="Semi presencial">Semi presencial</option>
                                    <option value="Presencial">Presencial</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" name="obs" placeholder="Obs"
                                    id="editObs">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <select class='form-select  w-100' name='estado' required id="editEstado">
                                    <option disabled selected>Seleccionar estado</option>
                                    <option value="t">Activo</option>
                                    <option value="f">Inactivo</option>
                                </select>
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
<!-- Modal para agregar pensum det-->
<div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-labelledby="modalAgregarDetalleLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarDetalleLabel">Agregar Pensum</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarDetalle">
                    <input type="hidden" name="action" value="agregarDetIndividual">
                    <input type="hidden" name="id" id="idCab">
                    <div class="mb-3">
                        <input class="input-group-text w-100" type="text" name="modulo" placeholder="Nombre del modulo"
                            required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="horast"
                                    placeholder="Horas teoricas" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="horasp"
                                    placeholder="Horas practicas" required>
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
<!-- Modal para editar pensum det-->
<div class="modal fade" id="modalEditarDetalle" tabindex="-1" aria-labelledby="modalEditarDetalleLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarDetalleLabel">Editar Pensum</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarDetalle">
                    <input type="hidden" name="action" value="editarDet">
                    <input type="hidden" name="id" id="editIdDet">
                    <div class="mb-3">
                        <input class="input-group-text w-100" type="text" name="modulo" id="editModulo"
                            placeholder="Nombre del modulo" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="horast" id='editHorast'
                                    placeholder="Horas teoricas" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="number" name="horasp" id='editHorasp'
                                    placeholder="Horas practicas" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-bs-target="#modalDetalle" data-bs-toggle="modal"
                            type="submit">Guardar
                            cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-target="#modalDetalle"
                            data-bs-toggle="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Formulario para buscar por curso -->
<div class="modal fade" id="modalBuscarCurso" tabindex="-1" aria-labelledby="modalBuscarCursoLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formBuscarPensum">
                    <input type="hidden" name="action" value="buscarPensum">
                    <input type="hidden" name="pagina" value="1">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for='pensums'>Pensums</label>
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM pensum_cab";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select class='form-select  w-100 keep'  name='id' required>";
                                    echo "<option selected disabled>Seleccione pensum</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_pensum'] . "'>" . $fila['curso'] . " » " . $fila['resolucion'] . " » " . $fila['fecha_res'] . "</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo "<select class='form-select  w-100 keep' aria-label='Disabled'>";
                                    echo "<option selected disabled>No hay pensums</option>";
                                    echo "</select>";

                                }
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
                <!-- Mensaje error/exito -->
                <div id="resultados2"></div>
                <button class="btn btn-secondary btn-agregar-detalle" data-bs-toggle='modal'
                    data-bs-target='#modalAgregarDetalle' id="id"> <i class="bi bi-person-add"></i> Agregar
                    detalle</button>
                <!-- Tabla -->
                <div id="tablaDetalle"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>