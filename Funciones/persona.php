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
        $rol = $_POST['rol'];

        $sql = "INSERT INTO personas(id_persona, nombre, apellido, ci, fecha_nac, sexo, telefono, correo, estado, nacionalidad, direccion) 
        VALUES ((SELECT max(id_persona) + 1 FROM personas),'$nombre', '$apellido','$ci', '$fecha_nac', '$sexo', '$telefono', '$correo', 1, '$nacionalidad', '$direccion')";
        $sql2 = "INSERT INTO $rol(persona_id) VALUES ((SELECT max(id_persona) FROM personas))";
        if (pg_query($conn, $sql)) {
            if (pg_query($conn, $sql2)) {
                echo "<script>
                    Swal.fire(
                    'Agregado!',
                    'Ha agregado el registro con exito!',
                    'success')
                    .then((value) =>{
                        $('.sweetAlerts').empty();
                    });
                    </script>";
            }
        } else if (!pg_query($conn, $sql)) {
            echo "<script>
            swal.fire('Error al registrar! . pg_last_error($conn)', 
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
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

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "UPDATE personas SET estado = 0 WHERE id_persona='$id'";
        if (pg_query($conn, $sql)) {
            echo "<script>
            Swal.fire(
            'Eliminado!',
            'Ha eliminado el registro con exito!',
            'success')
            .then((value) =>{
                $('.sweetAlerts').empty();
            });
            </script>";
        } else if (!pg_query($conn, $sql)) {
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
        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nac = $_POST['fecha_nac'];
        $sexo = $_POST['sexo'];
        $correo = $_POST['correo'];
        $nacionalidad = $_POST['nacionalidad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql = "UPDATE personas SET ci='$ci', nombre='$nombre', apellido='$apellido', fecha_nac='$fecha_nac', sexo='$sexo', correo='$correo', nacionalidad='$nacionalidad', direccion='$direccion', telefono='$telefono' WHERE id_persona='$id'";
        if (pg_query($conn, $sql)) {
            echo "<script>
                Swal.fire(
                'Editado!',
                'Se edito el registro!',
                'success')
                .then((value) =>{
                    $('.sweetAlerts').empty();
                });
                </script>";
        } else if (!pg_query($conn, $sql)) {
            echo "echo <script>
            swal.fire('Error al editar! . pg_last_error($conn)', 
            {
                icon: 'error',
            });
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
        $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT * FROM persona_v 
        WHERE estado = 1 
        AND nombre ILIKE '%$buscar%' 
        OR apellido ILIKE '%$buscar%' 
        OR ci ILIKE '%$buscar%'  
        ORDER by id_persona DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th scope='col'>ID</th>"
                . "<th scope='col'>Ci</th>"
                . "<th scope='col'>Nombre</th>"
                . "<th scope='col'>Apellido</th>"
                . "<th scope='col'>Fecha de nacimiento</th>"
                . "<th scope='col'>Sexo</th>"
                . "<th scope='col'>Correo</th>"
                . "<th scope='col'>Nacionalidad</th>"
                . "<th scope='col'>Direccion</th>"
                . "<th scope='col'>Telefono</th>"
                . "<th scope='col'>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td scope='row' class='id'>" . $fila['id_persona'] . "</td>";
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
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_persona'] . "' 
            data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-secondary btn-inscripciones btn-sm' data-id='" . $fila["id_persona"] . "' data-bs-toggle='modal' data-bs-target='#modalInscripciones'><i class='bi bi-journals'> </i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_persona"] . "'><i class='bi bi-trash'></i> </button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM persona_v WHERE estado = 1";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;' class='paginacion'  data-bs-theme='dark'>";
            echo "<nav aria-label='Page navigation example'>";
            echo "<ul class='pagination justify-content-center'>";
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item'><a class='page-link'><button class='btn-pagina' style='border: none;padding: 0;background: none;' data-pagina='$i'>$i</button></a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            echo "</div>";
        } else {
            echo "No se encontraron registros.";
        }
        pg_close($conn);
    }
    if ($action == 'inscripciones') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 5;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $id = $_POST['id'];

        // Consulta para obtener los alumnos
        $sql = "SELECT inscripciones.id_inscripcion,
        alumnos.nombre,
        alumnos.apellido,
        alumnos.ci,
        cursos.descri FROM inscripciones JOIN alumnos ON inscripciones.id_alumno = alumnos.id_alumno
        JOIN cursos ON inscripciones.id_curso = cursos.id_curso
        WHERE inscripciones.id_alumno LIKE '%$id%'  ORDER by id_inscripcion DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Ci</th>"
                . "<th>Curso</th>"
                . "</tr>";
            echo "</thead>";
            echo "<tbody class='table-group-divider'>";
            ;
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='curso'>" . $fila['descri'] . "</td>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM personas WHERE rol_id = 1";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div  class='paginacion' data-bs-theme='dark'>";
            echo "<nav aria-label='Page navigation example'>";
            echo "<ul class='pagination'>";
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item'><a class='page-link'><button class='btn-pagina' style='border: none;padding: 0;background: none;' data-pagina='$i'>$i</button></a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            echo "<p style='font-size: 0.875em;'>* Para editar la inscripción, ir a <a href='inscripciones.php'>Incripciones</a></p>";
            echo "</div>";
        } else {
            echo "No se encontraron registros.";
        }
        pg_close($conn);
    }
}
