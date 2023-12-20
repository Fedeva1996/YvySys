<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $materia_id  = $_POST['id_materia'];
        $cronograma_id  = $_POST['id_cronograma'];
        $obs = $_POST['obs'];
        $docente_reemplazo = $_POST['docente_r'];

        $sql = "INSERT INTO plan_clase_cab(fecha_ini, fecha_fin, materia_id, cronograma_id, obs, docente_reemplazo) 
        VALUES ('$fecha_ini','$fecha_fin','$materia_id','$cronograma_id','$obs','$docente_reemplazo')";
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
    if ($action == 'agregarDet') {
        include '../db_connect.php';

        $id_plan_clase = $_POST['id_plan_clase'];
        $procesoClase  = $_POST['procesoClase'];
        $competencia = $_POST['competencia'];
        $indicadores  = $_POST['indicadores'];
        $contenido  = $_POST['contenido'];
        $actividad  = $_POST['actividad'];

        $sql = "INSERT INTO plan_clase_det(plan_clase_cab_id, proceso_clase_cab_id, competencia, indicadores, contenido, actividad) 
        VALUES ('$id_plan_clase','$procesoClase','$competencia','$indicadores','$contenido','$actividad')";
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
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $fecha_ini = $_POST['fecha_ini'];
        $id_materia = $_POST['id_materia'];
        $fecha_fin = $_POST['fecha_fin'];

        $sql = "UPDATE plan_clase_cab SET fecha_ini = '$fecha_ini', fecha_fin = '$fecha_fin', materia_id = '$id_materia' WHERE id_plan_clase = '$id'";
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


    // Obtener la lista de registros
    if ($action == 'buscarPlanClase') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $fecha_p = isset($_POST['fecha_p']) ? $_POST['fecha_p'] : $fecha;
        if ($fecha_p != "") {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            plan_clase_det.id_plan_clase_det,
            plan_clase_det.proceso_clase_cab_id,
        plan_clase_det.competencia,
        plan_clase_det.indicadores,
        plan_clase_det.contenido,
        plan_clase_det.actividad,
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
        FROM plan_clase_det
        JOIN plan_clase_cab ON plan_clase_det.plan_clase_cab_id = plan_clase_cab.id_plan_clase
        JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
        JOIN cursos ON materias.curso_id = cursos.id_curso
        JOIN docentes ON materias.docente_id = docentes.id_docente
        WHERE '$fecha_p' BETWEEN plan_clase_cab.fecha_ini AND plan_clase_cab.fecha_fin
        ORDER by id_plan_clase_det LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
            $cabecera = pg_query($conn, $sql);
        } else {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            plan_clase_det.id_plan_clase_det,
            plan_clase_det.proceso_clase_cab_id,
            plan_clase_det.competencia,
            plan_clase_det.indicadores,
            plan_clase_det.contenido,
            plan_clase_det.actividad,
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
            FROM plan_clase_det
            JOIN plan_clase_cab ON plan_clase_det.plan_clase_cab_id = plan_clase_cab.id_plan_clase
            JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente
        ORDER by id_plan_clase_det LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
        }
        if (pg_num_rows($resultado) > 0) {
            if ($fecha_p != "" && $cab = pg_fetch_assoc($cabecera)) {
                echo "<!-- cabecera -->";
                echo "<div class='row g-3'>";
                echo "<div class='col-md-6'>";
                echo "<label>Fecha inicio</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha_ini'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Fecha fin</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['fecha_fin'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Curso</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['curso'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Materia</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['materia'] . "'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<label>Docente</label>";
                echo "<input type='text' class='form-control' disabled value='" . $cab['nombre'] . " " . $cab['apellido'] . "'>";
                echo "</div>";
                if ($cab['docente_reemplazo'] != null) {
                    echo "<div class='col-md-6'>";
                    echo "<label>Docente</label>";
                    echo "<input type='text' class='form-control' disabled value='" . $cab['nombrer'] . " " . $cab['apellidor'] . "'>";
                    echo "</div>";
                }
                echo "</div>";
                echo "</br>";
            }
            echo "<table class='table table-hover table-dark' ;  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Competencia</th>"
                . "<th>Indicadores</th>"
                . "<th>Contenido</th>"
                . "<th>Actividad</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_clase_det'] . "</td>";
                echo "<td class='idCab' style='display:none;'>" . $fila['id_plan_clase'] . "</td>";
                echo "<td class='idProc' style='display:none;'>" . $fila['proceso_clase_cab_id'] . "</td>";
                echo "<td class='competencia'>" . $fila['competencia'] . "</td>";
                echo "<td class='indicadores'>" . $fila['indicadores'] . "</td>";
                echo "<td class='contenido'>" . $fila['contenido'] . "</td>";
                echo "<td class='actividad'>" . $fila['actividad'] . "</td>";
                echo "<td class='id_materia' style='display:none;'>" . $fila['id_materia'] . "</td>";
                echo "<td class='fecha_ini' style='display:none;'>" . $fila['fecha_ini'] . "</td>";
                echo "<td class='fecha_fin' style='display:none;'>" . $fila['fecha_fin'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_plan_clase_det'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_plan_clase_det"] . "'><i class='bi bi-trash'></i></button>|
        <button class='btn btn-dark btn-editar-cab btn-sm' data-id='" . $fila['id_plan_clase'] . "' data-bs-toggle='modal' data-bs-target='#modalEditarCab'><i class='bi bi-pencil'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM plan_clase_det
            JOIN plan_clase_cab ON plan_clase_det.plan_clase_cab_id = plan_clase_cab.id_plan_clase
            JOIN materias ON plan_clase_cab.materia_id = materias.id_materia
            JOIN cursos ON materias.curso_id = cursos.id_curso
            JOIN docentes ON materias.docente_id = docentes.id_docente";
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
