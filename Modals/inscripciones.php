<!-- formulario agregar alumno -->
<div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel"
    aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarAlumnoLabel">Agregar Alumno</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarAlumno">
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
                                <input class="input-group-text w-100" type="date" name="edad"
                                    placeholder="Fecha nacimiento" required>
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
<!-- Formulario para agregar inscripcion -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarLabel">Agregar inscripcion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarInscripcion">
                    <input type="hidden" name="action" value="agregar">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <input class="input-group-text w-100" type="text" id="ci-input"
                                    placeholder="Ci del alumno" autocomplete="off" required>
                                <input type="hidden" id="id_alumno" name="id_alumno">
                                <div id="suggestions"></div>
                            </div>
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select class='form-select  w-100' name='id_curso' required>";
                                    echo "<option selected disabled>Seleccione curso</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <input class="input-group-text w-100" readonly type="datetime" id="fecha" name="fecha"
                                    value="<?php echo date("d-m-Y"); ?>">
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
<!-- Formulario para agregar matriculación -->
<div class="modal fade" id="modalMatricular" tabindex="-1" aria-labelledby="modalMatricularLabel" aria-hidden="true"
    data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalMatricularLabel">Agregar inscripcion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarMatriculacion">
                    <input type="hidden" name="action" value="matricular">
                    <input type="hidden" id="matId" name="id">
                    <div class="row">
                        <div class="mb-3">
                            <?php
                            include 'db_connect.php';
                            $sql = "SELECT * FROM modulo_v";
                            $resultados = pg_query($conn, $sql);
                            if (pg_num_rows($resultados) > 0) {
                                echo "<select class='form-select  w-100' name='modulo' required>";
                                echo "<option selected disabled>Seleccione modulo</option>";
                                while ($fila = pg_fetch_assoc($resultados)) {
                                    echo "<option value='" . $fila['id_modulo'] . "'>" . $fila['modulo'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" readonly type="datetime" name="fecha"
                                value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="mb-3">
                            <input class="input-group-text w-100" type="text" name="obs" placeholder="Observación">
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
                <form action="reportes/inscriptos.php" method="post" id="formReporteInscripcion">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select class='form-select  w-100' id='id_curso' name='id_curso' required>";
                                    echo "<option selected disabled>Seleccione curso</option>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
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
                <h1 class="modal-title fs-5" id="modalEditarLabel">Editar inscripcion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarInscripcion">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="editNombre" class="col-sm-2 col-form-label">Alumno:</label>
                                <input class="form-control-plaintext w-100" type="text" readonly name="editNombre"
                                    id="editNombre">
                            </div>
                            <div class="mb-3">
                                <?php
                                include 'db_connect.php';
                                $sql = "SELECT * FROM cursos";
                                $resultados = pg_query($conn, $sql);
                                if (pg_num_rows($resultados) > 0) {
                                    echo "<select class='editCurso input-group-text w-100' id='editCurso' name='id_curso' required>";
                                    while ($fila = pg_fetch_assoc($resultados)) {
                                        echo "<option value='" . $fila['id_curso'] . "'>" . $fila['descri'] . "</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </div>
                            <select class="editEstado input-group-text w-100" id="editEstado" name="estado" required>
                                <option selected disabled>Seleccione estado</option>
                                <option value="0">Inactivo</option>
                                <option value="1">Activo</option>
                            </select>
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