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
        $cronograma = $_POST['cronograma'];
        $fecha = $_POST['fecha'];
        $fecha_recuperatorio = $_POST['fecha_recuperatorio'];
        $puntaje = $_POST['puntaje'];
        $obs = $_POST['obs'];
        $tipo = $_POST['tipo'];

        // Select file type
        $fileType = strtolower(pathinfo($directorio_archivo, PATHINFO_EXTENSION));
        // Valid file extensions
        $extensions_arr = array("pdf", "doc", "docx");

        // Check extension
        if (in_array($fileType, $extensions_arr)) {
            if ($fecha_recuperatorio > $fecha) {
                // Elimina los '../' de la variable $directorio_archivo
                $ruta_sin_puntos = str_replace('../', '', $directorio_archivo);

                // Insert record
                $sql = "INSERT INTO plan_examen (
                    id_plan_examen, examen_id, modulo_id, cronograma_id, fecha, recuperatorio, obs, tipo, puntaje)
                    VALUES (
                    COALESCE((SELECT MAX(id_plan_examen) + 1 FROM plan_examen), 1),
                    (SELECT insertar_examen('$examen_nombre', '$ruta_sin_puntos')),
                    $modulo, 
                    $cronograma,
                    '$fecha', 
                    '$fecha_recuperatorio', 
                    '$obs', 
                    '$tipo', 
                    '$puntaje')";

                $result = pg_query($conn, $sql);

                if ($result) {
                    // Upload file
                    if (move_uploaded_file($_FILES['examen']['tmp_name'], $directorio_archivo)) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Éxito!</strong> Campo agregado.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Error!</strong> No se pudo cargar el archivo.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    }
                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Error!</strong> Puede que el nombre del archivo contenga algun caracter no valido (ã, ẽ, ĩ, õ, ũ, ỹ, g̃, '), pruebe cambiar el nombre del archivo e intentelo de nuevo.
                    " . pg_last_error($conn) . ".
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

        
    }

    // Obtener la lista de registros
    if ($action == 'selectModulo') {
        include '../db_connect.php';

        $curso = $_POST['cronogramaId'];
        $sql = "SELECT * FROM modulo_v WHERE curso_id = (select curso_id from cronogramas where id_cronograma = $curso)";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<option selected disabled>Seleccione módulo</option>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<option value='" . $fila['id_modulo'] . "'>" . $fila['modulo'] . "</option>";
            }
        } else {
            echo "<option selected disabled>No hay módulos</option>";
        }
    }
    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        // Obtén la ruta del archivo antes de eliminar el registro de la base de datos
        $sql_select = "SELECT directorio FROM plan_examen_v WHERE id_plan_examen = $id";
        $resultado_select = pg_query($conn, $sql_select);

        if ($resultado_select) {
            $fila = pg_fetch_assoc($resultado_select);
            $ruta_archivo_a_eliminar = $fila['directorio'];

            // Elimina el archivo
            if (file_exists("../" . $ruta_archivo_a_eliminar)) {
                // Elimina el registro de la base de datos
                $sql = "DELETE FROM plan_examen WHERE id_plan_examen='$id'";
                if (@pg_query($conn, $sql)) {
                    unlink("../" . $ruta_archivo_a_eliminar);

                    //eliminar examen de base de datos
                    $sql_select2 = "DELETE FROM examen WHERE directorio = '$ruta_archivo_a_eliminar'";
                    $resultado_select2 = pg_query($conn, $sql_select2);
                    if ($resultado_select2) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Exito!</strong> Campo eliminado.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Error!</strong> " . pg_last_error($conn) . ".
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    }
                } else if (@!pg_query($conn, $sql)) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Error!</strong> " . pg_last_error($conn) . ".
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                }
                //eliminar examen de base de datos
                $sql_select2 = "DELETE FROM examen WHERE directorio = '$ruta_archivo_a_eliminar'";
                $resultado_select2 = pg_query($conn, $sql_select2);
                if ($resultado_select2) {

                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Error!</strong> " . pg_last_error($conn) . ".
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                }
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> no se pudo borrar el archivo.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> El archivo no existe.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

        
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
                . "<th>Cronograma</th>"
                . "<th>Modulo</th>"
                . "<th>Fecha</th>"
                . "<th>Fecha recuperatorio</th>"
                . "<th>Tipo</th>"
                . "<th>Puntaje</th>"
                . "<th>Obs</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_examen'] . "</td>";
                echo "<td class='examen' style='display:none;'>" . $fila['examen_id'] . "</td>";
                echo "<td class='directorio'><a target='_blank' href='" . $fila['directorio'] . "' >" . $fila['directorio'] . "</a></td>";
                echo "<td class='cronograma' style='display:none;'>" . $fila['cronograma_id'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . " > " . $fila['fecha_inicio'] . " al " . $fila['fecha_fin'] . "</td>";
                echo "<td class='modulo' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                echo "<td class='fecha' style='display:none;'>" . $fila['fecha'] . "</td>";
                echo "<td class='recuperatorio' style='display:none;'>" . $fila['recuperatorio'] . "</td>";
                echo "<td class='fecha_f'>" . $fila['fecha_f'] . "</td>";
                echo "<td class='recuperatorio_f'>" . $fila['recuperatorio_f'] . "</td>";
                echo "<td class='tipo' style='display:none;'>". $fila['tipo']."</td>";
                switch ($fila['tipo']) {
                    case 'P':
                        echo "<td>Parcial</td>";
                        break;
                    case 'R':
                        echo "<td>Recuperatorio</td>";
                        break;
                    default:
                        echo "<td>Final</td>";
                        break;
                }
                echo "<td class='puntaje'>" . $fila['puntaje'] . "</td>";
                echo "<td class='obs'>" . $fila['obs'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                    <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button>";
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
        
    }
}
