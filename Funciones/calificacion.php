<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $proceso = $_POST['proceso'];
        $trabajo = $_POST['trabajo'];
        $examen = $_POST['examen'];
        $total = $_POST['total'];
        $calificación = $_POST['calificacion'];
        $paso = $_POST['paso'];
        $obs = $_POST['obs'];

        $sql = "UPDATE
        calificaciones
        SET
        puntaje_proceso = '$proceso',
        puntaje_trabajo = '$trabajo',
        puntaje_examen = '$examen',
        puntaje_total = '$total',
        calificacion = '$calificación',
        paso = '$paso',
        obs = '$obs' 
        WHERE id_calificacion ='$id'";
        if (@pg_query($conn, $sql) === TRUE) {
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

    //Editar un registro
    if ($action == 'buscarCurso') {
        include '../db_connect.php';

        // Consulta para llenar el segundo select
        $id = $_POST['id_curso'];
        $sql = "SELECT
        DISTINCT(materias.descri), materias.id_materia
        FROM calificaciones
        JOIN materias ON calificaciones.id_materia = materias.id_materia
        WHERE materias.id_curso = $id";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<option value='" . $fila['id_materia'] . "'>" . $fila['descri'] . "</option>";
            }
        }

        
    }
    if ($action == 'sumar') {
        if (isset($_POST['num1']) && isset($_POST['num2']) && isset($_POST['num3'])) {
            $num1 = intval($_POST['num1']);
            $num2 = intval($_POST['num2']);
            $num3 = intval($_POST['num3']);
            $total = $num1 + $num2 + $num3;
            if ($total >= 95) {
                $calificacion = "5";
                $paso = 1;
                $color = "#81c784";
            } elseif ($total < 95 && $total >= 85) {
                $calificacion = "4";
                $paso = 1;
                $color = "#81c784";
            } elseif ($total < 85 && $total >= 75) {
                $calificacion = "3";
                $paso = 1;
                $color = "#81c784";
            } elseif ($total < 75 && $total >= 65) {
                $calificacion = "2";
                $paso = 1;
                $color = "#81c784";
            } else {
                $calificacion = "1";
                $paso = 0;
                $color = "#e57373";
            }
            $resultados = [$total, $calificacion, $paso, $color];
            echo json_encode($resultados);
        }
    }
    if ($action == 'buscar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 2;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $id_curso = isset($_POST['curso']) ? $_POST['curso'] : "";
        $id_materia = isset($_POST['materia']) ? $_POST['materia'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $curso = isset($_POST['id_curso']) ? $_POST['id_curso'] : $id_curso;
        $materia = isset($_POST['id_materia']) ? $_POST['id_materia'] : $id_materia;

        if (isset($_POST['id_curso']) && $_POST['id_materia']) {
            // Consulta para obtener los alumnos
            $sql = "SELECT
            calificaciones.id_calificacion,
            calificaciones.puntaje_proceso,
            calificaciones.puntaje_trabajo,
            calificaciones.puntaje_examen,
            calificaciones.puntaje_total,
            calificaciones.calificacion,
            inscripciones.id_inscripcion,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            alumnos.ci,
            calificaciones.id_materia,
            materias.descri AS materia,
            materias.id_curso,
            cursos.descri AS curso,
            calificaciones.obs,
            calificaciones.paso
            FROM
            calificaciones
            JOIN inscripciones ON calificaciones.id_inscripcion = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.id_alumno = alumnos.id_alumno
            JOIN materias ON calificaciones.id_materia = materias.id_materia
            JOIN cursos ON materias.id_curso = cursos.id_curso
            WHERE cursos.id_curso LIKE '%$curso%'
            AND materias.id_materia LIKE '%$materia%'
            ORDER by id_calificacion DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultados = pg_query($conn, $sql);
        } else {
            // Consulta para obtener los alumnos
            $sql = "SELECT
                calificaciones.id_calificacion,
                calificaciones.puntaje_proceso,
                calificaciones.puntaje_trabajo,
                calificaciones.puntaje_examen,
                calificaciones.puntaje_total,
                calificaciones.calificacion,
                inscripciones.id_inscripcion,
                alumnos.id_alumno,
                alumnos.nombre,
                alumnos.apellido,
                alumnos.ci,
                calificaciones.id_materia,
                materias.descri AS materia,
                materias.id_curso,
                cursos.descri AS curso,
                calificaciones.obs,
                calificaciones.paso
                FROM
                calificaciones
                JOIN inscripciones ON calificaciones.id_inscripcion = inscripciones.id_inscripcion
                JOIN alumnos ON inscripciones.id_alumno = alumnos.id_alumno
                JOIN materias ON calificaciones.id_materia = materias.id_materia
                JOIN cursos ON materias.id_curso = cursos.id_curso
                WHERE cursos.id_curso LIKE '%$id_curso%'
                AND materias.id_materia LIKE '%$id_materia%'
                ORDER by id_calificacion DESC LIMIT $registros_por_pagina OFFSET $offset";
            $resultados = pg_query($conn, $sql);
        }

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Nombre</th>"
                . "<th>CI</th>"
                . "<th>Materia</th>"
                . "<th>Curso</th>"
                . "<th>Proceso</th>"
                . "<th>Trabajos</th>"
                . "<th>Exámen</th>"
                . "<th>Total</th>"
                . "<th>Calificación</th>"
                . "<th>Pasó</th>"
                . "<th>Obs</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_calificacion'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='id_inscripcion' style='display:none;'>" . $fila['id_inscripcion'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='id_materia' style='display:none;'>" . $fila['id_materia'] . "</td>";
                echo "<td class='materia'>" . $fila['materia'] . "</td>";
                echo "<td class='id_curso' style='display:none;'>" . $fila['id_curso'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='puntaje_proceso'>" . $fila['puntaje_proceso'] . "</td>";
                echo "<td class='puntaje_trabajo'>" . $fila['puntaje_trabajo'] . "</td>";
                echo "<td class='puntaje_examen'>" . $fila['puntaje_examen'] . "</td>";
                echo "<td class='puntaje_total'>" . $fila['puntaje_total'] . "</td>";
                echo "<td class='calificacion'>" . $fila['calificacion'] . "</td>";
                if ($fila['paso'] == '0') {
                    echo "<td class='paso' style='display:none;'>" . $fila['paso'] . "</td>";
                    echo "<td style = 'color:#e57373'>No pasó</td>";
                } else {
                    echo "<td class='paso' style='display:none;'>" . $fila['paso'] . "</td>";
                    echo "<td style = 'color:#81c784'>Pasó</td>";
                }
                echo "<td class='obs'>" . $fila['obs'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT
            COUNT(calificaciones.id_calificacion) AS total FROM calificaciones
            JOIN inscripciones ON calificaciones.id_inscripcion = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.id_alumno = alumnos.id_alumno
            JOIN materias ON calificaciones.id_materia = materias.id_materia
            JOIN cursos ON materias.id_curso = cursos.id_curso
            WHERE cursos.id_curso LIKE '%$curso%'
            AND materias.id_materia LIKE '%$materia%'";

            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
            echo "<nav aria-label='Page navigation example'>";
            echo "<ul class='pagination justify-content-center'>";
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item'><a class='page-link'><button class='btn-pagina'  style='  border: none;padding: 0;background: none;' data-pagina='$i' data-curso='$GLOBALS[curso]' data-materia='$GLOBALS[materia]'>$i</button></a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            echo "</div>";
        } else {
            echo "No se encontraron registros.";
        }
        
    }
}
