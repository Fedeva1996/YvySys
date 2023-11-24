<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];

        // Realizar la consulta a la base de datos
        $sql = "SELECT id_alumno, nombre, apellido FROM alumnos WHERE ci LIKE '%$query%'";
        $resultado = pg_query($conn, $sql);

        // Generar la lista de sugerencias
        if (pg_num_rows($resultado) > 0) {
            while ($row = pg_fetch_assoc($resultado)) {
                $id = $row['id_alumno'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                echo '<div class="suggest-element" data-id_alumno="' . $id . '">' . $nombre . ' ' . $apellido . '</div>';
            }
        } else {
            echo '<div class="suggest-element">No se encontraron sugerencias</div>';
        }
    }

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $id_alumno = $_POST['id_alumno'];
        $id_curso = $_POST['id_curso'];
        $fecha = $_POST['fecha'];

        $sql = "INSERT INTO inscripciones(alumno_id, curso_id, fecha_inscri) VALUES ('$id_alumno','$id_curso','$fecha')";
        if (pg_query($conn, $sql) === TRUE) {
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

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM inscripciones WHERE id_inscripcion='$id'";
        if (pg_query($conn, $sql) === TRUE) {
            echo "<script>
            Swal.fire(
            'Eliminado!',
            'Ha eliminado el registro con exito!',
            'success')
            .then((value) =>{
                $('.sweetAlerts').empty();
            });
            </script>";
        } else {
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
        $id_curso = $_POST['id_curso'];
        $estado = $_POST['estado'];

        $sql = "UPDATE inscripciones SET curso_id='$id_curso', estado=$estado WHERE id_inscripcion ='$id'";
        if (pg_query($conn, $sql) === TRUE) {
            echo "<script>
                Swal.fire(
                'Editado!',
                'Se edito el registro!',
                'success')
                .then((value) =>{
                    $('.sweetAlerts').empty();
                });
                </script>";
        } else {
            echo "<script>
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
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT inscripciones.id_inscripcion, 
        alumnos.id_alumno, 
        alumnos.nombre, 
        alumnos.apellido, 
        alumnos.ci, 
        cursos.id_curso, 
        cursos.tipo, 
        cursos.descri,
        inscripciones.estado
        FROM inscripciones 
        JOIN alumnos on inscripciones.alumno_id = alumnos.id_alumno 
        JOIN cursos on inscripciones.curso_id = cursos.id_curso 
        ORDER by id_inscripcion DESC LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th></th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Ci</th>"
                . "<th>Tipo curso</th>"
                . "<th>Curso</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_inscripcion'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='id_curso' style='display:none;'>" . $fila['id_curso'] . "</td>";
                echo "<td class='tipo'>" . $fila['tipo'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Inactivo</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Activo</td>";
                }
                echo "<td><button class='btn btn-dark btn-editar btn-sm' data-id='" . $fila['id_inscripcion'] . "' 
            data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_inscripcion"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM alumnos";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='width:100%';  margin-left: auto; margin-right: auto;' class='paginacion'>";
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
    if ($action == 'buscar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $buscar = $_POST['buscar'];

        // Consulta para obtener los alumnos
        $sql = "SELECT inscripciones.id_inscripcion, 
       alumnos.id_alumno, 
       alumnos.nombre, 
       alumnos.apellido, 
       alumnos.ci, 
       cursos.id_curso, 
       cursos.tipo, 
       cursos.descri,
       inscripciones.estado
       FROM inscripciones 
       JOIN alumnos on inscripciones.id_alumno = alumnos.id_alumno 
       JOIN cursos on inscripciones.id_curso = cursos.id_curso 
       WHERE nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR ci LIKE '%$buscar%'
       ORDER by id_inscripcion DESC LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th></th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Ci</th>"
                . "<th>Tipo curso</th>"
                . "<th>Curso</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_inscripcion'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='id_curso' style='display:none;'>" . $fila['id_curso'] . "</td>";
                echo "<td class='tipo'>" . $fila['tipo'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Inactivo</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Activo</td>";
                }
                echo "<td><button class='btn btn-dark btn-editar btn-sm' data-id='" . $fila['id_inscripcion'] . "' 
            data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_inscripcion"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM alumnos";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='width:100%';  margin-left: auto; margin-right: auto;' class='paginacion'>";
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
}
