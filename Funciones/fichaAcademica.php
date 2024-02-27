<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo eliminado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> Puede que haya tablas dependiendo de este registro!" . pg_last_error($conn) . ".
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

    }

    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $periodo_id = $_POST['id_periodo'];
        $turno_id = $_POST['id_turno'];
        $estado = $_POST['estado'];

        $sql = "";
        if (pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }


    }

    // Obtener la lista de registros
    if ($action == 'listar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";

        // Consulta para obtener los alumnos
        $sql = "SELECT * from ficha_academica_v WHERE descri ILIKE '%$buscar%' OR ci ILIKE '%$buscar%' ORDER by id_ficha_academica DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Curso</th>"
                . "<th>Alumno</th>"
                . "<th>Fecha</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_ficha_academica'] . "</td>";
                echo "<td class='inscripcion_cab_id' style='display:none;'>" . $fila['inscripcion_cab_id'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . " - " . $fila['ci'] . "</td>";
                echo "<td class='fecha_f'>" . $fila['fecha_f'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-descargar btn-sm'><i class='bi bi-download'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM cursos";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
            echo "<nav aria-label='Page navigation example'>";
            echo "<ul class='pagination justify-content-center'>";
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item'><a class='page-link'><button class='btn-pagina'  style='  border: none;padding: 0;background: none;' data-pagina='$i'>$i</button></a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            echo "</div>";
        } else {
            echo "No se encontraron registros.";
        }


    }
    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];

        // Realizar la consulta a la base de datos
        $sql = "SELECT id_alumno, nombre, apellido FROM alumno_v WHERE ci LIKE '$query%'";
        $resultados = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultados) > 0) {
            while ($row = pg_fetch_assoc($resultados)) {
                $id = $row['id_alumno'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                echo '<div class="suggest-element" onclick="loadCurso('.$id.')" data-id_alumno="' . $id . '">' . $nombre . ' ' . $apellido . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }
    // Obtener la lista de registros
    if ($action == 'selectCurso') {
        include '../db_connect.php';

        $alumno = $_POST['alumnoId'];
        $sql = "SELECT id_inscripcion, descri FROM inscripcion_curso_v WHERE id_alumno = $alumno";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<option selected disabled>Seleccione módulo</option>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<option value='" . $fila['id_inscripcion'] . "'>" . $fila['descri'] . "</option>";
            }
        } else {
            echo "<option selected disabled>No hay módulos</option>";
        }
    }
}
