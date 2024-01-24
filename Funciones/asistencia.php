<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];
        $date = date("Y-m-d");
        // Realizar la consulta a la base de datos
        $sql = "SELECT 
        plan_clase_cab.id_plan_clase,
        plan_clase_cab.fecha_ini,
        plan_clase_cab.fecha_fin,
        plan_clase_cab.docente_reemplazo,
        materias.id_materia,
        materias.descri as materia,
        cursos.descri as curso,
        docentes.nombre,
        docentes.apellido,
        plan_clase_cab.obs
        FROM plan_clase_cab
        JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
        JOIN cursos ON materias.curso_id = cursos.id_curso
        JOIN docentes ON materias.docente_id = docentes.id_docente
        WHERE ('$date' BETWEEN plan_clase_cab.fecha_ini AND plan_clase_cab.fecha_fin)
        AND materias.descri LIKE '$query%'";
        $resultado = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultado) > 0) {
            while ($row = pg_fetch_assoc($resultado)) {
                $id = $row['id_plan_clase'];
                $id_materia = $row['id_materia'];
                $materia = $row['materia'];
                $curso = $row['curso'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                echo '<div class="suggest-element" data-id-plan="' . $id . '" data-id-materia="' . $id_materia . '"><i>' . $materia . '</i> <br><b>Curso:</b> ' . $curso . ' | <b>Docente: </b>' . $nombre . ' ' . $apellido . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }
    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $plan_clase_cab_id = $_POST['id-plan'];
        $fecha = $_POST['fecha'];
        $docente_asis = isset($_POST['asistenciaD']) ? $_POST['asistenciaD'] : 0;
        $obs = $_POST['obs'];

        $sql = "INSERT INTO asistencias_cab(plan_clase_cab_id, fecha, docente_asis, obs) 
        VALUES ('$plan_clase_cab_id','$fecha','$docente_asis','$obs')";
        if (@pg_query($conn, $sql) === TRUE) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }

        pg_close($conn);
    }
    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $estado = $_POST['estado'];

        $sql = "UPDATE asistencias_det SET estado='$estado' WHERE id_asistencia_det ='$id'";
        if (@pg_query($conn, $sql) === TRUE) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }
        pg_close($conn);
    }

    if ($action == 'buscarAsistencia') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";
        $id_modulo = isset($_POST['curso']) ? $_POST['curso'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $fecha_p = isset($_POST['fecha_p']) ? $_POST['fecha_p'] : $fecha;
        $curso = isset($_POST['id_modulo']) ? $_POST['id_modulo'] : $id_modulo;
        if (isset($_POST['fecha_p']) && $_POST['id_modulo']) {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            *
            FROM
            asistencia_v
            WHERE fecha = '$fecha_p'
            AND id_modulo = '$curso'
            ORDER by id_asistencia_det DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        } else {
            $sql = "SELECT 
            *
            FROM
            asistencia_v
            WHERE fecha = '$fecha'
            AND id_modulo = '$id_modulo'
            ORDER by id_asistencia_det DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        }

        if (pg_num_rows($resultado) > 0) {
            if ($cab = pg_fetch_assoc($cabecera)) {
                echo "<!-- cabecera -->";
                echo "<div class='row g-3'>";
                echo "<div class='col-md-6'>";
                echo "<label>Materia</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['descri'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Fecha</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha'] . "'>";
                echo "</div>";
                echo "</div>";
                echo "</br>";
            }

            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Nombre</th>"
                . "<th>CI</th>"
                . "<th>Modulo</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_asistencia_det'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='id_modulo' style='display:none;'>" . $fila['id_modulo'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Ausente</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Presente</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
           data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT
            COUNT(*) AS total FROM asistencia_v
            WHERE fecha = '2024/01/16'
            AND id_modulo = '1'";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
            echo "<nav aria-label='Page navigation example'>";
            echo "<ul class='pagination justify-content-center'>";
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item'><a class='page-link'><button class='btn-pagina' style='border: none;padding: 0;background: none;' data-pagina='$i' data-curso='$GLOBALS[curso]' data-fecha='$GLOBALS[fecha_p]'>$i</button></a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            echo "</div>";
        } else {
            echo "No se encontraron registros.";
        }
        pg_close($conn);
    }
}
