<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $descri = $_POST['descri'];
        $id_curso = $_POST['id_curso'];
        $id_docente = $_POST['id_docente'];

        $sql = "INSERT INTO materias (descri, id_curso, id_docente) "
            . "VALUES ('$descri', '$id_curso','$id_docente')";
        if (pg_query($conn, $sql)) {
            echo "<script>
                Swal.fire(
                'Agregado!',
                'Ha agregado el registro con exito!',
                'success')
                .then((value) =>{
                    $('.sweetAlerts').empty();
                });
                </script>";
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

        $sql = "DELETE FROM materias WHERE id_materia='$id'";
        $sql = "DELETE FROM alumnos WHERE id_alumno='$id'";
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
        } else if (!pg_query($conn, $sql)){
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
        $descri = $_POST['descri'];
        $id_curso = $_POST['id_curso'];
        $id_docente = $_POST['id_docente'];


        $sql = "UPDATE materias SET descri='$descri', id_curso='$id_curso', id_docente='$id_docente' WHERE id_materia='$id'";
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
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT modulos.id_modulo, 
        modulos.descri as modulo, 
        pensum_det.id_pensum_det,
        pensum_det.horas_t,
        pensum_det.horas_p,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        personas.id_persona, 
        personas.nombre, 
        personas.apellido 
        FROM modulos 
        JOIN pensum_det ON modulos.pensum_det_id = pensum_det.id_pensum_det
        JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum
        JOIN personas on modulos.docente_id = personas.id_persona
        WHERE personas.rol_id = 3 
        LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Materia</th>"
                . "<th>Horas</th>"
                . "<th>Curso</th>"
                . "<th>Docente</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_modulo'] . "</td>";
                echo "<td class='id_pensum_det' style='display:none;'>" . $fila['id_pensum_det'] . "</td>";
                echo "<td class='descri'>" . $fila['modulo'] . "</td>";
                echo "<td class='horas'>Total horas: " . $fila['horas_t'] + $fila['horas_p'] . "</td>";
                echo "<td class='id_pensum' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='id_persona' style='display:none;'>" . $fila['id_persona'] . "</td>";
                echo "<td class='docente'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_modulo'] . "' 
            data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_modulo"] . "'><i class='bi bi-trash'></i></button></td>";
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

            echo "<div style='width:100%';  margin-left: auto; margin-right: auto;' class='paginacion'>";
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
    if ($action == 'buscar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $buscar = $_POST['buscar'];

        // Consulta para obtener los alumnos
        $sql = "SELECT modulos.id_modulo, 
        modulos.descri as modulo, 
        pensum_det.id_pensum_det,
        pensum_det.horas_t,
        pensum_det.horas_p,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        personas.id_persona, 
        personas.nombre, 
        personas.apellido 
        FROM modulos 
        JOIN pensum_det ON modulos.pensum_det_id = pensum_det.id_pensum_det
        JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum
        JOIN personas on modulos.docente_id = personas.id_persona
        WHERE personas.rol_id = 3 
        LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Materia</th>"
                . "<th>Horas</th>"
                . "<th>Curso</th>"
                . "<th>Docente</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_modulo'] . "</td>";
                echo "<td class='id_pensum_det' style='display:none;'>" . $fila['id_pensum_det'] . "</td>";
                echo "<td class='descri'>" . $fila['modulo'] . "</td>";
                echo "<td class='horas'>Total horas: " . $fila['horas_t'] + $fila['horas_p'] . "</td>";
                echo "<td class='id_pensum' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='id_persona' style='display:none;'>" . $fila['id_persona'] . "</td>";
                echo "<td class='docente'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_modulo'] . "' 
                    data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_modulo"] . "'><i class='bi bi-trash'></i></button></td>";
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

            echo "<div style='width:100%';  margin-left: auto; margin-right: auto;' class='paginacion'>";
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
    if ($action == 'buscarMateria') {
        include '../db_connect.php';
        $pensumCabId = $_POST['pensumCabId'];

        // Consulta para obtener las materias relacionadas con el curso seleccionado
        $sql = "SELECT * FROM pensum_det WHERE pensum_cab_id = $pensumCabId";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<option selected disabled>Seleccione modulo</option>";

            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<option value='" . $fila['id_pensum_det'] . "'>" . $fila['descri'] . "</option>";
            }
        } else {
            echo "<option selected disabled>No hay modulos disponibles</option>";
        }
    }
}
