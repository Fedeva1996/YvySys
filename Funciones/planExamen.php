<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $examen_nombre = $_FILES['examen']['name'];
        $carpeta = "../Examenes/";
        // Reemplazar espacios con guiones bajos
        $examen_nombre = str_replace(' ', '_', $examen_nombre);

        $directorio_archivo = $carpeta . basename($examen_nombre);

        // Verificar si ya existe un archivo con el mismo nombre
        $counter = 1;
        while (file_exists($directorio_archivo)) {
            $nombre_sin_extension = pathinfo($examen_nombre, PATHINFO_FILENAME);
            $nuevo_nombre = $nombre_sin_extension . '_' . $counter . '.' . pathinfo($examen_nombre, PATHINFO_EXTENSION);
            $directorio_archivo = $carpeta . basename($nuevo_nombre);
            $counter++;
        }

        $modulo = $_POST['modulo'];
        $fecha = $_POST['fecha'];
        $fecha_recuperatorio = $_POST['fecha_recuperatorio'];
        $puntaje = $_POST['puntaje'];
        $obs = $_POST['obs'];
        $tipo = $_POST['tipo'];

        // Select file type
        $fileType = strtolower(pathinfo($directorio_archivo, PATHINFO_EXTENSION));
        // Valid file extensions
        $extensions_arr = array("pdf");

        // Check extension
        if (in_array($fileType, $extensions_arr)) {
            if ($fecha_recuperatorio > $fecha) {
                // Upload file
                if (move_uploaded_file($_FILES['examen']['tmp_name'], $directorio_archivo)) {
                    // Elimina los '../' de la variable $directorio_archivo
                    $ruta_sin_puntos = str_replace('../', '', $directorio_archivo);

                    // Insert record
                    $sql = "INSERT INTO plan_examen (
                id_plan_examen, examen_id, modulo_id, fecha, recuperatorio, obs, tipo, puntaje)
                VALUES (
                COALESCE((SELECT MAX(id_plan_examen) + 1 FROM plan_examen), 1),
                (SELECT insertar_examen('$examen_nombre', '$ruta_sin_puntos')),
                $modulo, '$fecha', '$fecha_recuperatorio', '$obs', '$tipo', '$puntaje')";

                    $result = pg_query($conn, $sql);

                    if ($result) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Éxito!</strong> Campo agregado.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Error!</strong> " . pg_last_error($conn) . ".
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    }
                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> No se pudo cargar el archivo.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                }
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> La fecha recuperatoria no puede ser anterior a la fecha original.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
        <strong>Error!</strong> El archivo debe ser un PDF.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
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
        pg_close($conn);
    }

    // Obtener la lista de registros
    if ($action == 'listar') {
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
            *
            FROM plan_examen_v
            WHERE fecha BETWEEN '$fecha_p' AND '$fecha_p'
            AND descri = '$materia'
            ORDER by id_plan_examen LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
        } else {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
            *
            FROM plan_examen_v
            ORDER by id_plan_examen LIMIT $registros_por_pagina OFFSET $offset";
            $resultado = pg_query($conn, $sql);
        }
        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark table-sm'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Directorio</th>"
                . "<th>Modulo</th>"
                . "<th>Fecha</th>"
                . "<th>Fecha recuperatorio</th>"
                . "<th>Tipo</th>"
                . "<th>Puntaje</th>"
                . "<th>Obs</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_examen'] . "</td>";
                echo "<td class='examen' style='display:none;'>" . $fila['examen_id'] . "</td>";
                echo "<td class='directorio' style='display:none;'>" . $fila['directorio'] . "</td>";
                echo "<td class='directorio'><a target='_blank' href='" . $fila['directorio'] . "' >" . $fila['directorio'] . "</a></td>";
                echo "<td class='modulo' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                echo "<td class='fecha' style='display:none;'>" . $fila['fecha'] . "</td>";
                echo "<td class='recuperatorio' style='display:none;'>" . $fila['recuperatorio'] . "</td>";
                echo "<td class='fecha_f'>" . $fila['fecha_f'] . "</td>";
                echo "<td class='recuperatorio_f'>" . $fila['recuperatorio_f'] . "</td>";
                echo "<td class='puntaje'>" . $fila['puntaje'] . "</td>";
                echo "<td class='obs'>" . $fila['obs'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button>|
        <button class='btn btn-secondary btn-editar-cab btn-sm'  data-bs-toggle='modal' data-bs-target='#modalEditarCab'><i class='bi bi-pencil'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM plan_examen_v";
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
        pg_close($conn);
    }
}
