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
        AND materias.descri LIKE '%$query%'";
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
        if (pg_query($conn, $sql) === TRUE) {
            echo "<script>
                Swal.fire(
                'Agregado!',
                'Ha agregado el registro con exito!',
                'success')
                .then((value) =>{
                    $('.sweetAlerts').empty();
                });
                </script>";
        } else {
            echo "<script>
            swal.fire('Error al registrar! . pg_last_error($conn)', 
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
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
        if (pg_query($conn, $sql) === TRUE) {
            echo "<script>
                Swal.fire(
                'Agregado!',
                'Ha editado el registro con exito!',
                'success')
                .then((value) =>{
                    $('.sweetAlerts').empty();
                });
                </script>";
        } else {
            echo "<script>
            swal.fire('Error al registrar! . pg_last_error($conn)', 
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
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
        $id_curso = isset($_POST['curso']) ? $_POST['curso'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $fecha_p = isset($_POST['fecha_p']) ? $_POST['fecha_p'] : $fecha;
        $curso = isset($_POST['id_curso']) ? $_POST['id_curso'] : $id_curso;
        if (isset($_POST['fecha_p']) && $_POST['id_curso']) {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            asistencias_det.id_asistencia_det,
            asistencias_cab.plan_clase_cab_id,
            asistencias_cab.docente_asis,
            plan_clase_cab.materia_id,
            asistencias_cab.fecha,
            materias.id_materia,
            materias.descri as materia,
            cursos.id_curso,
            cursos.tipo,
            cursos.descri as curso,
            docentes.id_docente,
            docentes.nombre as nombre_docente,
            docentes.apellido as apellido_docente,
            asistencias_det.inscripcion_id,
            asistencias_det.estado,
            asistencias_det.obs,
            inscripciones.id_inscripcion,
            inscripciones.alumno_id,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            alumnos.ci
            FROM
            asistencias_det
            JOIN asistencias_cab ON asistencias_det.asistenicia_cab_id = asistencias_cab.id_asistencia
            JOIN plan_clase_cab ON asistencias_cab.plan_clase_cab_id = plan_clase_cab.id_plan_clase
            JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
            JOIN inscripciones ON asistencias_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            WHERE asistencias_cab.fecha BETWEEN '$fecha_p' AND '$fecha_p'
            AND cursos.id_curso LIKE '$curso'
            ORDER by id_asistencia_det DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        } else {
            $sql = "SELECT 
            asistencias_det.id_asistencia_det,
            asistencias_cab.plan_clase_cab_id,
            asistencias_cab.docente_asis,
            plan_clase_cab.materia_id,
            asistencias_cab.fecha,
            materias.id_materia,
            materias.descri as materia,
            cursos.id_curso,
            cursos.tipo,
            cursos.descri as curso,
            docentes.id_docente,
            docentes.nombre as nombre_docente,
            docentes.apellido as apellido_docente,
            asistencias_det.inscripcion_id,
            asistencias_det.estado,
            asistencias_det.obs,
            inscripciones.id_inscripcion,
            inscripciones.alumno_id,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido
            FROM
            asistencias_det
            JOIN asistencias_cab ON asistencias_det.asistenicia_cab_id = asistencias_cab.id_asistencia
            JOIN plan_clase_cab ON asistencias_cab.plan_clase_cab_id = plan_clase_cab.id_plan_clase
            JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
            JOIN inscripciones ON asistencias_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            WHERE asistencias_cab.fecha BETWEEN '$fecha' AND '$fecha'
            AND cursos.id_curso LIKE '$id_curso'
            ORDER by id_asistencia_det DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        }

        if (pg_num_rows($resultado) > 0) {
            if ($cab = pg_fetch_assoc($cabecera)) {
                echo "<!-- cabecera -->";
                echo "<div class='row g-3'>";
                echo "<div class='col-md-6'>";
                echo "<label>Curso</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['curso'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Materia</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['materia'] . "'>";
                echo "</div>";
                echo "<div class='col-md-12'>";
                echo "<label>Docente</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['nombre_docente'] . " " . $cab['apellido_docente'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Asistio  </label>";
                if ($cab['docente_asis'] == 1) {
                    echo "<input class='form-check-input' type='checkbox' value='' checked disabled>";
                } else {
                    echo "<input class='form-check-input' type='checkbox' value='' disabled>";
                }
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Fecha</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha'] . "'>";
                echo "</div>";
                echo "</div>";

                echo "</br>";
            }

            echo "<table class='table table-hover table-dark' ;  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Nombre</th>"
                . "<th>CI</th>"
                . "<th>Materia</th>"
                . "<th>Curso</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_asistencia_det'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='id_inscripcion' style='display:none;'>" . $fila['id_inscripcion'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='id_materia' style='display:none;'>" . $fila['id_materia'] . "</td>";
                echo "<td class='materia'>" . $fila['materia'] . "</td>";
                echo "<td class='id_curso' style='display:none;'>" . $fila['id_curso'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Ausente</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Presente</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_inscripcion'] . "' 
           data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT
            COUNT(asistencias_det.id_asistencia_det) AS total FROM asistencias_det
            JOIN asistencias_cab ON asistencias_det.asistenicia_cab_id = asistencias_cab.id_asistencia
            JOIN plan_clase_cab ON asistencias_cab.plan_clase_cab_id = plan_clase_cab.id_plan_clase
            JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
            JOIN inscripciones ON asistencias_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            WHERE asistencias_cab.fecha BETWEEN '$fecha' AND '$fecha'
            AND cursos.id_curso LIKE '%$id_curso%'";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div ;  margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
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
