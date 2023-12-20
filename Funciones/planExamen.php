<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $id_examen = $_POST['id_examen'];
        $id_materia = $_POST['id_materia'];
        $fecha = $_POST['fecha'];
        $fecha_recuperatorio = $_POST['fecha_recuperatorio'];
        $puntaje = $_POST['puntaje'];
        $obs = $_POST['obs'];

        $sql = "INSERT INTO plan_examen_cab(examen_id, materia_id, fecha, recuperatorio, puntaje, obs) 
        VALUES ('$id_examen','$id_materia','$fecha','$fecha_recuperatorio','$puntaje','$obs')";
        if (@pg_query($conn, $sql) === TRUE) {
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
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }

        pg_close($conn);
    }
    // Agregar un nuevo detalle
    if ($action == 'agregarDet') {
        include '../db_connect.php';

        $id_plan_examen = $_POST['id_plan_examen'];
        $id_inscripcion = $_POST['id_inscripcion'];
        $puntaje = $_POST['puntaje'];

        $sql = "INSERT INTO plan_examen_det(plan_examen_cab_id, inscripcion_id, puntaje_hecho) 
        VALUES ('$id_plan_examen','$id_inscripcion','$puntaje')";
        if (@pg_query($conn, $sql) === TRUE) {
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
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $id_materia = $_POST['id_materia'];
        $fecha = $_POST['fecha'];
        $recuperatorio = $_POST['recuperatorio'];
        $puntaje = $_POST['puntaje'];

        $sql = "UPDATE plan_examen_cab SET materia_id='$id_materia', fecha='$fecha', recuperatorio='$recuperatorio', puntaje='$puntaje' WHERE id_plan_examen ='$id'";
        if (@pg_query($conn, $sql) === TRUE) {
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
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }
        pg_close($conn);
    }

    // Obtener la lista de registros
    if ($action == 'buscarPlanExamen') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $fecha_p = isset($_POST['fecha_p']) ? $_POST['fecha_p'] : "";
        $materia = isset($_POST['materia']) ? $_POST['materia'] : "";
        if ($fecha_p != "") {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            plan_examen_cab.id_plan_examen,
            plan_examen_cab.obs,
            plan_examen_cab.fecha,
            plan_examen_cab.recuperatorio,
            examen.puntaje,
            examen.directorio,
            materias.id_materia,
            materias.descri as materia,
            cursos.id_curso,
            cursos.descri as curso,
            plan_examen_det.id_plan_examen_det,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            plan_examen_det.puntaje_hecho
            FROM plan_examen_det
            JOIN plan_examen_cab ON plan_examen_det.plan_examen_cab_id = plan_examen_cab.id_plan_examen
            JOIN inscripciones ON plan_examen_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            JOIN examen ON plan_examen_cab.examen_id = examen.id_examen
            JOIN materias ON plan_examen_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
        WHERE plan_examen_cab.fecha BETWEEN '$fecha_p' AND '$fecha_p'
        AND materias.id_materia = '$materia'
         ORDER by id_plan_examen_det LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        } else {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            plan_examen_cab.id_plan_examen,
            plan_examen_cab.obs,
            plan_examen_cab.fecha,
            plan_examen_cab.recuperatorio,
            examen.puntaje,
            examen.directorio,
            materias.id_materia,
            materias.descri as materia,
            cursos.id_curso,
            cursos.descri as curso,
            plan_examen_det.id_plan_examen_det,
            alumnos.id_alumno,
            alumnos.nombre,
            alumnos.apellido,
            plan_examen_det.puntaje_hecho
            FROM plan_examen_det
            JOIN plan_examen_cab ON plan_examen_det.plan_examen_cab_id = plan_examen_cab.id_plan_examen
            JOIN inscripciones ON plan_examen_det.inscripcion_id = inscripciones.id_inscripcion
            JOIN alumnos ON inscripciones.alumno_id = alumnos.id_alumno
            JOIN examen ON plan_examen_cab.examen_id = examen.id_examen
            JOIN materias ON plan_examen_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
        ORDER by id_plan_examen_det LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
        }
        if (pg_num_rows($resultado) > 0) {
            if ($fecha_p != "" && $cab = pg_fetch_assoc($cabecera)) {
                echo "<!-- cabecera -->";
                echo "<div class='row g-3'>";
                echo "<div class='col-md-6'>";
                echo "<label>Fecha</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Recuperatorio</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['recuperatorio'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Puntaje total</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['puntaje'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Ubicación del archivo</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['directorio'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Curso</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['curso'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Materia</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['materia'] . "'>";
                echo "</div>";
                echo "</div>";
                echo "</br>";
            }
            echo "<table class='table table-hover table-dark' ;  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Alumno</th>"
                . "<th>Puntaje hecho</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_examen_det'] . "</td>";
                echo "<td class='id_cab' style='display:none;'>" . $fila['id_plan_examen'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='puntaje_hecho'>" . $fila['puntaje_hecho'] . "</td>";
                echo "<td class='id_materia' style='display:none;'>" . $fila['id_materia'] . "</td>";
                echo "<td class='fecha' style='display:none;'>" . $fila['fecha'] . "</td>";
                echo "<td class='recuperatorio' style='display:none;'>" . $fila['recuperatorio'] . "</td>";
                echo "<td class='puntaje' style='display:none;'>" . $fila['puntaje'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_plan_examen_det'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_plan_examen_det"] . "'><i class='bi bi-trash'></i></button>|
        <button class='btn btn-secondary btn-editar-cab btn-sm' data-id='" . $fila['id_plan_examen'] . "' data-bs-toggle='modal' data-bs-target='#modalEditarCab'><i class='bi bi-pencil'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM plan_examen_det
            JOIN plan_examen_cab ON plan_examen_det.id_plan_examen_det = plan_examen_det.plan_examen_cab_id";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div ;  margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
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
        pg_close($conn);
    }
}
