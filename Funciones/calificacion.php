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
        if (@pg_query($conn, $sql)) {
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
    if ($action == 'generar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "SELECT generar_escala($id)";

        $resultado = pg_query($conn, $sql);
        if ($resultado) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Escala generada.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }

    if ($action == 'listar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 2;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $sql = "SELECT * FROM calificacion_cab_v
                ORDER by id_calificacion_cab DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Materia</th>"
                . "<th>Curso</th>"
                . "<th>Sumatoria Procesos</th>"
                . "<th>Sumatoria Exámenes</th>"
                . "<th>Total</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_calificacion_cab'] . "</td>";
                echo "<td class='modulo_id' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                echo "<td class='modulo'>" . $fila['modulo'] . "</td>";
                echo "<td class='curso_id' style='display:none;'>" . $fila['curso_id'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='sumatoria_procesos'>" . $fila['sumatoria_procesos'] . "</td>";
                echo "<td class='sumatoria_examenes'>" . $fila['sumatoria_examenes'] . "</td>";
                if ($fila['total'] == null) {
                    echo "<td class='total'><span class='badge text-bg-warning'>Falta generar escala</span></td>";
                } else {
                    if ($fila['estado'] === 'f') {
                        echo "<td class='total'>" . $fila['total'] . " puntos <span class='badge text-bg-warning'>Volver a generar escala</span></td>";
                        echo "<td>
                        <button class='btn btn-secondary btn-generar btn-sm'  
                        data-bs-toggle='modal'><i class='bi bi-node-plus'></i> Generar escala</button>
                        <button class='btn btn-secondary btn-editar btn-sm'  
                        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                        <button class='btn btn-secondary btn-sm'  
                        data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_calificacion_cab'] . ")'><i class='bi bi-postcard'></i></button>
                        <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                    } else {
                        echo "<td class='total'>" . $fila['total'] . " puntos</td>";
                        echo "<td>
                        <button class='btn btn-secondary btn-editar btn-sm'  
                        data-bs-toggle='modal' data-bs-target='#modalAsignar'><i class='bi bi-calendar3'></i> Calificar</button>
                        <button class='btn btn-secondary btn-editar btn-sm'  
                        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                        <button class='btn btn-secondary btn-sm'  
                        data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_calificacion_cab'] . ")'><i class='bi bi-postcard'></i></button>
                        <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                    }
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM calificacion_cab_v";

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
}
