<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nac = $_POST['fecha_nac'];
        $sexo = $_POST['sexo'];
        $correo = $_POST['correo'];
        $nacionalidad = $_POST['nacionalidad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql2 = "INSERT INTO docentes(persona_id) VALUES (SELECT insertar_personas(nombre, apellido, ci, fecha_nac, sexo, telefono, correo, nacionalidad, direccion))";
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
    }
    
    if ($action == 'agregarExistente') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "INSERT INTO docentes(persona_id) VALUES ($id);";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
            </script>
            ";
        }


    }
    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM docentes WHERE id_docente='$id'";
        if (@pg_query($conn, $sql)) {
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


    }

    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nac = $_POST['fecha_nac'];
        $sexo = $_POST['sexo'];
        $correo = $_POST['correo'];
        $nacionalidad = $_POST['nacionalidad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql = "UPDATE personas SET ci='$ci', nombre='$nombre', apellido='$apellido', fecha_nac=$fecha_nac, sexo='$sexo', correo='$correo', nacionalidad='$nacionalidad', direccion='$direccion', telefono='$telefono' WHERE id_persona='$id'";
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

    // Obtener la lista de registros
    if ($action == 'listar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT * FROM docente_v
        WHERE estado = true
        AND nombre ILIKE '$buscar%' 
        OR apellido ILIKE '$buscar%' 
        OR ci ILIKE '$buscar%' 
        ORDER by id_docente DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Ci</th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Fecha de nacimiento</th>"
                . "<th>Sexo</th>"
                . "<th>Correo</th>"
                . "<th>Nacionalidad</th>"
                . "<th>Direccion</th>"
                . "<th>Telefono</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td scope='row' class='id'>" . $fila['id_docente'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='fecha_nac'>" . $fila['fecha_nac'] . "</td>";
                echo "<td class='fecha_no_form' style='display:none;'>" . $fila['fecha_no_form'] . "</td>";
                echo "<td class='sexo'>" . $fila['sexo'] . "</td>";
                echo "<td class='correo'>" . $fila['correo'] . "</td>";
                echo "<td class='nacionalidad'>" . $fila['nacionalidad'] . "</td>";
                echo "<td class='direccion'>" . $fila['direccion'] . "</td>";
                echo "<td class='telefono'>" . $fila['telefono'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar-persona btn-sm'  
        data-bs-toggle='modal' data-bs-target='#modalEditarPersona'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar-docente btn-sm' ><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM docente_v WHERE estado = true";
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
    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];

        // Realizar la consulta a la base de datos
        $sql = "SELECT id_persona, nombre, apellido FROM personas WHERE ci LIKE '$query%'";
        $resultados = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultados) > 0) {
            while ($row = pg_fetch_assoc($resultados)) {
                $id = $row['id_persona'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                echo '<div class="suggest-element" data-id-persona="' . $id . '">' . $nombre . ' ' . $apellido . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }
}
