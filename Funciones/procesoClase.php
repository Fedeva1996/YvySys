<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $id_materia = $_POST['id_materia'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $puntaje = $_POST['puntaje'];
        $descripcion = $_POST['descripcion'];

        $sql = "INSERT INTO procesos_clase_cab(materia_id, fecha_entrega, puntaje, descripcion) 
        VALUES ('$id_materia','$fecha_entrega','$puntaje','$descripcion')";
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

        
    }
    // Agregar un nuevo detalle
    if ($action == 'agregarDet') {
        include '../db_connect.php';

        $id_procesos_clase_cab = $_POST['id_procesos_clase'];
        $id_inscripcion = $_POST['id_inscripcion'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $puntaje = $_POST['puntaje'];

        $sql = "INSERT INTO procesos_clase_det(procesos_clase_cab_id, inscripcion_id, fecha_entrega, puntaje_hecho) 
        VALUES ('$id_procesos_clase_cab', '$id_inscripcion', '$fecha_entrega','$puntaje')";
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

        
    }
    //Editar un registro
    if ($action == 'editarDet') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $idCab = $_POST['id_procesos_clase'];
        $idAl = $_POST['id_inscripcion'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $puntaje = $_POST['puntaje'];

        $sql = "UPDATE procesos_clase_det SET procesos_clase_cab_id='$idCab', inscripcion_id='$idAl', fecha_entrega='$fecha_entrega', puntaje_hecho='$puntaje' WHERE id_procesos_clase_det='$id'";
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
        
    }

    if ($action == 'buscarProcesoClase') {
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
            procesos_clase_cab.id_procesos_clase,
            procesos_clase_cab.fecha_entrega as fecha_entregat,
            procesos_clase_cab.puntaje,
            procesos_clase_cab.descripcion,
            materias.descri as materia,
            cursos.descri as curso,
            procesos_clase_det.id_procesos_clase_det,
            docentes.nombre as nombred,
            docentes.apellido as apellidod,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            procesos_clase_det.fecha_entrega,
            procesos_clase_det.puntaje_hecho,
            procesos_clase_det.estado
            FROM 
            procesos_clase_det
            JOIN procesos_clase_cab ON procesos_clase_det.procesos_clase_cab_id = procesos_clase_cab.id_procesos_clase
            JOIN inscripciones ON procesos_clase_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            JOIN materias ON procesos_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
            WHERE procesos_clase_cab.fecha_entrega BETWEEN '$fecha_p' AND '$fecha_p'
            AND cursos.id_curso LIKE '$curso'
            ORDER by id_procesos_clase_det DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        } else {
            $sql = "SELECT 
            procesos_clase_cab.id_procesos_clase,
            procesos_clase_cab.fecha_entrega as fecha_entregat,
            procesos_clase_cab.puntaje,
            procesos_clase_cab.descripcion,
            materias.descri as materia,
            cursos.descri as curso,
            procesos_clase_det.id_procesos_clase_det,
            docentes.nombre as nombred,
            docentes.apellido as apellidod,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            procesos_clase_det.fecha_entrega,
            procesos_clase_det.puntaje_hecho,
            procesos_clase_det.estado
            FROM 
            procesos_clase_det
            JOIN procesos_clase_cab ON procesos_clase_det.procesos_clase_cab_id = procesos_clase_cab.id_procesos_clase
            JOIN inscripciones ON procesos_clase_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            JOIN materias ON procesos_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
            WHERE procesos_clase_cab.fecha_entrega BETWEEN '$fecha' AND '$fecha'
            AND cursos.id_curso LIKE '$id_curso'
            ORDER by id_procesos_clase_det DESC LIMIT $registros_por_pagina OFFSET $offset";
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
                echo "<div class='col-md-6'>";
                echo "<label>Descripción</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['descripcion'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Puntaje</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['puntaje'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Docente</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['nombred'] . " " . $cab['apellidod'] . "'>";
                echo "</div>";;
                echo "<div class='col-md-6'>";
                echo "<label>Fecha</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha_entregat'] . "'>";
                echo "</div>";
                echo "</div>";

                echo "</br>";
            }

            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Nombre</th>"
                . "<th>Fecha entrega</th>"
                . "<th>Puntaje hecho</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_procesos_clase_det'] . "</td>";
                echo "<td class='idCab' style='display:none;'>" . $fila['id_procesos_clase'] . "</td>";
                echo "<td class='idAlumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='fecha_entrega'>" . $fila['fecha_entrega'] . "</td>";
                echo "<td class='puntaje'>" . $fila['puntaje_hecho'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Asignado</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Presentado</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
           data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT
            COUNT(procesos_clase_det.id_procesos_clase_det) AS total FROM 
            procesos_clase_det
            JOIN procesos_clase_cab ON procesos_clase_det.procesos_clase_cab_id = procesos_clase_cab.id_procesos_clase
            JOIN inscripciones ON procesos_clase_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            JOIN materias ON procesos_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente";
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
        
    }
}
