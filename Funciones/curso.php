<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $pensum = $_POST['id_pensum'];
        $periodo = $_POST['id_periodo'];
        $turno = $_POST['id_turno'];
        $modalidad = $_POST['id_modalidad'];
        $fecha_ini = $_POST['fecha_ini'];
        $tipo = $_POST['tipo'];

        $sql = "INSERT INTO `cursos`(
            pensum_id,
            periodo_id,
            turno_id,
            modalidad_id,
            fecha_ini,
            descri, 
            tipo)
            SELECT
                '$pensum',
                '$periodo',
                '$turno',
                '$modalidad',
                '$fecha_ini',
                curso,
                '$tipo'
            FROM
                pensum_cab
            WHERE
                id_pensum = '$pensum'
            ";
        if ($conn->query($sql) === TRUE) {
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
            swal.fire('Error al registrar! . $conn->error', 
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
            </script>
            ";
        }

        $conn->close();
    }

    // Eliminar un registro
    if ($action == 'eliminar') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "DELETE FROM cursos WHERE id_curso='$id'";
        if ($conn->query($sql) === TRUE) {
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
            swal.fire('Error al eliminar: ! . $conn->error', 
            {
                icon: 'error',
            }).then((value) =>{
                $('.sweetAlerts').empty();
            });;
            </script>
            ";
        }
        $conn->close();
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
        if ($conn->query($sql) === TRUE) {
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
            echo "echo <script>
            swal.fire('Error al editar! . $conn->error', 
            {
                icon: 'error',
            });
            </script>
            ";
        }

        $conn->close();
    }

    // Obtener la lista de registros
    if ($action == 'listar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para obtener los alumnos
        $sql = "SELECT
        cursos.id_curso,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        periodo.id_periodo,
        periodo.ano,
        periodo.descripcion,
        turno.id_turno,
        turno.descri AS turno,
        turno.horario,
        modalidad.id_modalidad,
        modalidad.descri AS modalidad,
        cursos.fecha_ini,
        cursos.fecha_fin,
        cursos.tipo,
        cursos.estado
        FROM
        cursos
        JOIN pensum_cab ON cursos.pensum_id = pensum_cab.id_pensum
        JOIN periodo ON cursos.periodo_id = periodo.id_periodo
        JOIN turno ON cursos.turno_id = turno.id_turno
        JOIN modalidad ON cursos.modalidad_id = modalidad.id_modalidad ORDER by id_curso DESC LIMIT $offset, $registros_por_pagina";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Curso</th>"
                . "<th>Año</th>"
                . "<th>Periodo</th>"
                . "<th>Turno</th>"
                . "<th>Horario</th>"
                . "<th>Modalidad</th>"
                . "<th>Inicio</th>"
                . "<th>Fin</th>"
                . "<th>Tipo</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_curso'] . "</td>";
                echo "<td class='id_pensum' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='id_turno' style='display:none;'>" . $fila['id_turno'] . "</td>";
                echo "<td class='id_modalidad' style='display:none;'>" . $fila['id_modalidad'] . "</td>";
                echo "<td class='id_periodo' style='display:none;'>" . $fila['id_periodo'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='ano'>" . $fila['ano'] . "</td>";
                echo "<td class='periodo'>" . $fila['descripcion'] . "</td>";
                echo "<td class='turno'>" . $fila['turno'] . "</td>";
                echo "<td class='hoario'>" . $fila['horario'] . "</td>";
                echo "<td class='modalidad'>" . $fila['modalidad'] . "</td>";
                echo "<td class='fecha_ini'>" . $fila['fecha_ini'] . "</td>";
                echo "<td class='fecha_fin'>" . $fila['fecha_fin'] . "</td>";
                echo "<td class='tipo'>" . $fila['tipo'] . "</td>";
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
            $resultado_total = $conn->query($sql_total);
            $fila_total = $resultado_total->fetch_assoc();
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

        $conn->close();
    }
    if ($action == 'buscar') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $offset = ($pagina - 1) * $registros_por_pagina;

        $buscar = $_POST['buscar'];

        // Consulta para obtener los alumnos
        $sql = "SELECT
        cursos.id_curso,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        periodo.id_periodo,
        periodo.ano,
        periodo.descripcion,
        turno.id_turno,
        turno.descri AS turno,
        turno.horario,
        modalidad.id_modalidad,
        modalidad.descri AS modalidad,
        cursos.fecha_ini,
        cursos.fecha_fin,
        cursos.tipo,
        cursos.estado
        FROM
        cursos
        JOIN pensum_cab ON cursos.pensum_id = pensum_cab.id_pensum
        JOIN periodo ON cursos.periodo_id = periodo.id_periodo
        JOIN turno ON cursos.turno_id = turno.id_turno
        JOIN modalidad ON cursos.modalidad_id = modalidad.id_modalidad ORDER by id_curso DESC LIMIT $offset, $registros_por_pagina";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Curso</th>"
                . "<th>Año</th>"
                . "<th>Periodo</th>"
                . "<th>Turno</th>"
                . "<th>Horario</th>"
                . "<th>Modalidad</th>"
                . "<th>Inicio</th>"
                . "<th>Fin</th>"
                . "<th>Tipo</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_curso'] . "</td>";
                echo "<td class='id_pensum' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='id_turno' style='display:none;'>" . $fila['id_turno'] . "</td>";
                echo "<td class='id_modalidad' style='display:none;'>" . $fila['id_modalidad'] . "</td>";
                echo "<td class='id_periodo' style='display:none;'>" . $fila['id_periodo'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='ano'>" . $fila['ano'] . "</td>";
                echo "<td class='periodo'>" . $fila['descripcion'] . "</td>";
                echo "<td class='turno'>" . $fila['turno'] . "</td>";
                echo "<td class='hoario'>" . $fila['horario'] . "</td>";
                echo "<td class='modalidad'>" . $fila['modalidad'] . "</td>";
                echo "<td class='fecha_ini'>" . $fila['fecha_ini'] . "</td>";
                echo "<td class='fecha_fin'>" . $fila['fecha_fin'] . "</td>";
                echo "<td class='tipo'>" . $fila['tipo'] . "</td>";
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
            $resultado_total = $conn->query($sql_total);
            $fila_total = $resultado_total->fetch_assoc();
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

        $conn->close();
    }
}
