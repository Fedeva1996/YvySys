<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'autocompletar') {
        include '../db_connect.php';

        // Obtener el término de búsqueda del POST
        $query = $_POST['query'];

        // Realizar la consulta a la base de datos
        $sql = "SELECT id_alumno, nombre, apellido FROM alumno_v WHERE ci LIKE '$query%'";
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

    // Agregar una inscripción
    if ($action == 'agregar') {
        include '../db_connect.php';

        $id_alumno = $_POST['id_alumno'];
        $id_curso = $_POST['id_curso'];
        $fecha = $_POST['fecha'];

        $sql = "INSERT INTO inscripciones_cab(id_inscripcion, alumno_id, curso_id, fecha_inscripcion) VALUES (COALESCE((SELECT MAX(id_inscripcion) + 1 FROM inscripciones_cab), 1),'$id_alumno','$id_curso','$fecha')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        
    }

    // Agregar una matriculación
    if ($action == 'matricular') {
        include '../db_connect.php';

        $inscripcion_cab_id = $_POST['id'];
        $modulo_id = $_POST['modulo'];
        $fecha = $_POST['fecha'];
        $obs = $_POST['obs'];

        $sql = "INSERT INTO inscripciones_det(id_inscripcion_det, inscripcion_cab_id, modulo_id, fecha_inscripcion, obs) VALUES (COALESCE((SELECT MAX(id_inscripcion_det) + 1 FROM inscripciones_det), 1),'$inscripcion_cab_id','$modulo_id','$fecha', '$obs')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        
    }

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM inscripciones_cab WHERE id_inscripcion='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo eliminado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> Hay matriculaciones dependiendo de esta inscripción." . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        
    }
    // Eliminar un registro det
    if ($action == 'eliminarDet') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM inscripciones_det WHERE id_inscripcion_det='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo eliminado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> Hay matriculaciones dependiendo de esta inscripción." . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        
    }

    //Editar un registro
    if ($action == 'editar') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $id_curso = $_POST['id_curso'];
        $estado = $_POST['estado'];

        $sql = "UPDATE inscripciones SET curso_id='$id_curso', estado=$estado WHERE id_inscripcion ='$id'";
        if (@pg_query($conn, $sql) === TRUE) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<script>
            swal.fire('Error al editar! . pg_last_error($conn)', 
            {
                icon: 'error',
            });
            </script>
            ";
        }

        
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
        $sql = "SELECT *
        FROM inscripcion_curso_v
        WHERE  nombre LIKE '$buscar%' OR apellido LIKE '$buscar%' OR ci LIKE '$buscar%'
        ORDER by id_inscripcion DESC LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark table-sm accordion' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>#</th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Ci</th>"
                . "<th>Fecha de inscripción</th>"
                . "<th>Curso</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            $row = 0;
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_inscripcion'] . "</td>";
                echo "<td class='id_alumno' style='display:none;'>" . $fila['id_alumno'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='fecha_inscripcion'>" . $fila['fecha_inscripcion'] . "</td>";
                echo "<td class='id_curso' style='display:none;'>" . $fila['id_curso'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#cc3300'>Inactivo</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                    echo "<td style = 'color:#99cc33'>Activo</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                <button class='btn btn-secondary btn-matricular btn-sm' data-bs-toggle='collapse' data-bs-target='#r$row'><i class='bi bi-postcard'></i></button>
                <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
                echo "<tr class='collapse accordion-collapse' id='r$row' data-bs-parent='.table'>";
                echo "<td colspan='8'>";
                echo "<button style='margin:10px;' class='btn btn-secondary btn-sm' data-bs-toggle='modal' data-bs-target='#modalMatricular'><i class='bi bi-person-add'></i> Matricular</button>";
                $sqlDet = "SELECT *
                FROM inscripcion_modulo_v
                ORDER by id_inscripcion_det";
                $resultadoDet = pg_query($conn, $sqlDet);
                if (pg_num_rows($resultadoDet) > 0) {
                    echo "<table style='width: 98%; margin: 0 auto;' class='table table-hover table-secondary table-sm'>";
                    echo "<thead class='table-secondary'>"
                        . "<tr>"
                        . "<th scope='col'>ID</th>"
                        . "<th scope='col'>Modulo</th>"
                        . "<th scope='col'>Fecha inscripción</th>"
                        . "<th scope='col'>Obs</th>"
                        . "<th scope='col'>Estado</th>"
                        . "<th scope='col'>Acciones</th>"
                        . "</tr>"
                        . "</thead>";
                    echo "<tbody class='table-group-divider'>";
                    while ($filaDet = pg_fetch_assoc($resultadoDet)) {
                        echo "<tr>";
                        echo "<td scope='row' class='id table-secondary'>" . $filaDet['id_inscripcion_det'] . "</td>";
                        echo "<td class='inscripcion_cab_id table-' style='display:none;'>" . $filaDet['inscripcion_cab_id'] . "</td>";
                        echo "<td class='modulo_id table-secondary' style='display:none;'>" . $filaDet['modulo_id'] . "</td>";
                        echo "<td class='modulo table-secondary'>" . $filaDet['descri'] . "</td>";
                        echo "<td class='fecha_inscripcion table-'>" . $filaDet['fecha_inscripcion'] . "</td>";
                        echo "<td class='obs table-secondary'>" . $filaDet['obs'] . "</td>";
                        if ($filaDet['estado'] == false) {
                            echo "<td class='estado table-secondary' style='display:none;'> Inactivo</td>";
                            echo "<td style = 'color:#cc3300'>Inactivo</td>";
                        } else if ($filaDet['estado'] == true) {
                            echo "<td class='estado table-secondary' style='display:none;'> Activo</td>";
                            echo "<td style = 'color:#99cc33'>Activo</td>";
                        }
                        echo "<td><button class='btn btn-danger btn-eliminar-det btn-sm'><i class='bi bi-trash'></i></button></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
                "</td>
                </tr>";
                $row++;
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM inscripcion_curso_v";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
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
        
    }
}
