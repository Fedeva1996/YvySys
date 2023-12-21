<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';


        $pensum = $_POST['id_pensum'];
        $periodo = $_POST['id_periodo'];
        $turno = $_POST['id_turno'];
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];

        $sql = "INSERT INTO cursos(id_curso,
            pensum_id,
            periodo_id,
            turno_id,
            fecha_ini,
            fecha_fin,
            descri)
            SELECT
            COALESCE((SELECT MAX(id_curso) + 1 FROM cursos), 1),
                '$pensum',
                '$periodo',
                '$turno',
                '$fecha_ini',
                '$fecha_fin',
                curso
            FROM
                pensum_cab
            WHERE
                id_pensum = '$pensum'";
        if ($fecha_ini < $fecha_fin) {
            if (@pg_query($conn, $sql)) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Exito!</strong> Campo agregado.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            } else if (@!pg_query($conn, $sql)) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Error!</strong> " . pg_last_error($conn) . ".
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> Fecha de finalización debe ser mayor.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        pg_close($conn);
    }

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM cursos WHERE id_curso='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo eliminado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
            echo "<script>
            swal.fire('Error al eliminar: puede que haya inscripciones dependiendo de este alumno, primero borre las matriculaciones! . pg_last_error($conn)', 
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
        $pensum_id = $_POST['id_pensum'];
        $periodo_id = $_POST['id_periodo'];
        $turno_id = $_POST['id_turno'];
        $modalidad_id = $_POST['id_modalidad'];
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $tipo = $_POST['tipo'];
        $estado = $_POST['estado'];

        $sql = "UPDATE
            cursos
        SET
            pensum_id = '$pensum_id',
            periodo_id = '$periodo_id',
            turno_id = '$turno_id',
            modalidad_id = '$modalidad_id',
            fecha_ini = '$fecha_ini',
            fecha_fin = '$fecha_fin',
            descri = (SELECT curso FROM pensum_cab WHERE id_pensum = cursos.pensum_id),
            tipo = '$tipo',
            estado = '$estado'
        WHERE
            id_curso='$id'";
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

        pg_close($conn);
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
        $sql = "SELECT * from curso_v WHERE curso ILIKE '%$buscar%' ORDER by id_curso DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark table-sm' ;  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Curso</th>"
                . "<th>Año</th>"
                . "<th>Periodo</th>"
                . "<th>Turno</th>"
                . "<th>Horario</th>"
                . "<th>Inicio</th>"
                . "<th>Fin</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_curso'] . "</td>";
                echo "<td class='id_pensum' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='id_turno' style='display:none;'>" . $fila['id_turno'] . "</td>";
                echo "<td class='id_periodo' style='display:none;'>" . $fila['id_periodo'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='ano'>" . $fila['ano'] . "</td>";
                echo "<td class='periodo'>" . $fila['descripcion'] . "</td>";
                echo "<td class='turno'>" . $fila['turno'] . "</td>";
                echo "<td class='hoario'>" . $fila['horario'] . "</td>";
                echo "<td class='fecha_ini'>" . $fila['fecha_ini'] . "</td>";
                echo "<td class='fecha_fin'>" . $fila['fecha_fin'] . "</td>";
                if ($fila['estado'] === "S") {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Sin iniciar</td>";
                } else if ($fila['estado'] === "C") {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#ffcc00'>En curso</td>";
                } else if ($fila['estado'] === "F") {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Finalizado</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_curso'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_curso"] . "'><i class='bi bi-trash'></i></button></td>";
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
