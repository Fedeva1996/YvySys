<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'generar') {
        include '../db_connect.php';

        $id_pensum = $_POST['pensum'];

        $sql = "SELECT generar_modulos($id_pensum)";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Materias generadas.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        pg_close($conn);
    }

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM modulos WHERE id_modulo='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo eliminado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)){
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> " . pg_last_error($conn) . ".
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

        pg_close($conn);
    }

    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $id_docente = $_POST['docente'];


        $sql = "UPDATE modulos SET docente_id='$id_docente' WHERE id_modulo='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Docente asignado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
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
        $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT * FROM modulo_v 
        WHERE modulo ILIKE '%$buscar%' 
        OR curso ILIKE '%$buscar%' 
        ORDER BY id_modulo  
        LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;''>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Materia</th>"
                . "<th>Curso</th>"
                . "<th>Docente</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_modulo'] . "</td>";
                echo "<td class='id_pensum_det' style='display:none;'>" . $fila['pensum_det_id'] . "</td>";
                echo "<td class='modulo'>" . $fila['modulo'] . "</td>";
                echo "<td class='pensum_cab_id' style='display:none;'>" . $fila['pensum_cab_id'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='docente_id' style='display:none;'>" . $fila['docente_id'] . "</td>";
                echo "<td class='docente_ci' style='display:none;'>" . $fila['ci'] . "</td>";
                if($fila['docente_id']==null){
                    echo "<td class='docente' style='color:#cc3300'>Aun no asignado</td>";
                }else {
                    echo "<td class='docente'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
            data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-person-plus'></i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM modulos";
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
        $sql = "SELECT id_docente, nombre, apellido FROM docente_v WHERE ci LIKE '$query%'";
        $resultado = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultado) > 0) {
            while ($row = pg_fetch_assoc($resultado)) {
                $id = $row['id_docente'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                echo '<div class="suggest-element" data-id-persona="' . $id . '">' . $nombre . ' ' . $apellido . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }
}
