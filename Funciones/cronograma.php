<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';


        $pensum = $_POST['id_pensum'];
        $periodo = $_POST['id_periodo'];
        $turno = $_POST['id_turno'];

        $sql = "INSERT INTO cursos(id_curso,
            pensum_id,
            periodo_id,
            turno_id,
            descri)
            SELECT
            COALESCE((SELECT MAX(id_curso) + 1 FROM cursos), 1),
                '$pensum',
                '$periodo',
                '$turno',
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
                <strong>Error!</strong> Fecha de inicio no puede ser despues de la fecha de finalización.
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
        $tipo = $_POST['tipo'];
        $estado = $_POST['estado'];

        $sql = "UPDATE
            cursos
        SET
            pensum_id = '$pensum_id',
            periodo_id = '$periodo_id',
            turno_id = '$turno_id',
            modalidad_id = '$modalidad_id',
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
        $sql = "SELECT * from cronograma_v WHERE tipo_evento ILIKE '%$buscar%' ORDER by id_evento DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;''>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Evento</th>"
                . "<th>Inicio</th>"
                . "<th>Fin</th>"
                . "<th>Curso</th>"
                . "<th>Modulo</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_evento'] . "</td>";
                echo "<td class='cronograma_id' style='display:none;'>" . $fila['cronograma_id'] . "</td>";
                echo "<td class='curso_id' style='display:none;'>" . $fila['curso_id'] . "</td>";
                echo "<td class='modulo_id' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                echo "<td class='tipo_evento'>" . $fila['tipo_evento'] . "</td>";
                echo "<td class='fecha_inicio'>" . $fila['fecha_inicio'] . "</td>";
                echo "<td class='fecha_fin'>" . $fila['fecha_fin'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='modulo'>" . $fila['modulo'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM cronograma_v";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;'' class='paginacion' data-bs-theme='dark'>";
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
    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];

        // Realizar la consulta a la base de datos
        $sql = "SELECT id_modulo, descri FROM modulos WHERE descri ILIKE '$query%'";
        $resultado = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultado) > 0) {
            while ($row = pg_fetch_assoc($resultado)) {
                $id = $row['id_modulo'];
                $descri = $row['descri'];
                echo '<div class="suggest-element" data-id-modulo="' . $id . '">' . $descri . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }
}
