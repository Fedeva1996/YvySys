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
        if (@pg_query($conn, $sql)) {
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
        if (@pg_query($conn, $sql)) {
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
        if (@pg_query($conn, $sql)) {
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
    //Generar
    if ($action == 'generar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $modulo_id = $_POST['modulo_id'];

        $sql = "SELECT id_inscripcion_det FROM inscripciones_det WHERE modulo_id = $modulo_id";

        $resultados = pg_query($conn, $sql);

        if ($resultados) {
            if (pg_num_rows($resultados) > 0) {
                while ($fila = pg_fetch_assoc($resultados)) {
                    $inscripcion = $fila['id_inscripcion_det'];
                    $sql2 = "INSERT INTO procesos_clase_det(proceso_clase_cab_id, inscripcion_det_id) VALUES ($id, $inscripcion)";
                    if (@pg_query($conn, $sql2)) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Exito!</strong> Asistencias generadas.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Error!</strong> " . pg_last_error($conn) . ".
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    }
                }
            }
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> " . pg_last_error($conn) . ".
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

    }

    if ($action == 'listar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";
        $id_curso = isset($_POST['curso']) ? $_POST['curso'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $fecha_p = isset($_POST['fecha_p']) ? $_POST['fecha_p'] : $fecha;
        $curso = isset($_POST['id_curso']) ? $_POST['id_curso'] : $id_curso;
        $sql = "SELECT *
            FROM 
            proceso_clase_cab_v
            ORDER by id_proceso_clase DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Fecha entrega</th>"
                . "<th>Puntaje total</th>"
                . "<th>descripción</th>"
                . "<th>Modulo</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_proceso_clase'] . "</td>";
                echo "<td class='fecha_entrega' style='display:none;'>" . $fila['fecha_entrega'] . "</td>";
                echo "<td class='fecha_entrega_f'>" . $fila['fecha_entrega_f'] . "</td>";
                echo "<td class='puntaje'>" . $fila['puntaje'] . "</td>";
                echo "<td class='descripcion'>" . $fila['descripcion'] . "</td>";
                echo "<td class='modulo_id' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                if ($fila['modulo_id'] == null) {
                    echo "<td class='docente'><span class='badge text-bg-warning'>Aun no asignado</span></td>";
                } else {
                    echo "<td class='docente'>" . $fila['descri'] . " > " . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                }
                if ($fila['estado'] == "f") {
                    echo "<td>
                    <button class='btn btn-secondary btn-generar btn-sm'  
                    data-bs-toggle='modal'><i class='bi bi-node-plus'></i> Generar</button>
                    <button class='btn btn-secondary btn-editar btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                    <button class='btn btn-secondary btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_proceso_clase'] . ")'><i class='bi bi-postcard'></i></button>
                    <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                } else {
                    echo "<td>
                    <button class='btn btn-secondary btn-editar btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalAsignar'><i class='bi bi-calendar3'></i> Puntuar</button>
                    <button class='btn btn-secondary btn-editar btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                    <button class='btn btn-secondary btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_proceso_clase'] . ")'><i class='bi bi-postcard'></i></button>
                    <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT
            COUNT(*) AS total FROM 
            proceso_clase_cab_v";
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
    if ($action == 'verDetalle') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "SELECT *
            FROM 
            proceso_clase_det_v WHERE proceso_clase_cab_id = $id";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Alumno</th>"
                . "<th>Fecha entrega</th>"
                . "<th>Puntaje hecho</th>"
                . "<th>estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_procesos_clase_det'] . "</td>";
                echo "<td class='proceso_clase_cab_id' style='display:none;'>" . $fila['proceso_clase_cab_id'] . "</td>";
                echo "<td class='alumno'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td class='fecha_entrega_f'>" . $fila['fecha_entrega_f'] . "</td>";
                echo "<td class='puntaje_hecho'>" . $fila['puntaje_hecho'] . "</td>";
                echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                if ($fila['estado'] === "f") {
                    echo "<td><span class='badge text-bg-danger'>No entregado</span></td>";
                } else {
                    echo "<td><span class='badge text-bg-success'>Entregado</span></td>";
                }
                echo "<td>
                    <button class='btn btn-secondary btn-editar btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No se encontraron registros.";
        }

    }
}
