<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $curso = $_POST['curso'];
        $resolucion = $_POST['resolucion'];
        $fecha_res = $_POST['fecha_res'];
        $modalidad = $_POST['modalidad'];
        $obs = $_POST['obs'];

        $sql = "INSERT INTO 
        pensum_cab(curso, resolucion, fecha_res, modalidad, obs) 
        VALUES 
        ('$curso', 
         '$resolucion',
         '$fecha_res', 
         '$modalidad',
         '$obs')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
    // Agregar un nuevo registro
    if ($action == 'agregarDet') {
        include '../db_connect.php';

        // Procesa los detalles
        $datosJSON = $_POST['datos'];
        $datos = json_decode($datosJSON, true);
        // Ahora $datos es un array asociativo con la información de cada fila de la tabla
        // Puedes hacer lo que necesites con estos datos
        foreach ($datos as $detalle) {
            $modulo = $detalle['modulo'];
            $horast = $detalle['horast'];
            $horasp = $detalle['horasp'];

            $sql = "INSERT INTO pensum_det (
                pensum_cab_id,
                descri,
                horas_t,
                horas_p
            )
            SELECT
                id_pensum,
                '$modulo',
                '$horast',
                '$horasp'
            FROM
                pensum_cab
            ORDER BY
                id_pensum DESC
            LIMIT 1;";
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
    }
    // Agregar un nuevo detalle
    if ($action == 'agregarDetIndividual') {
        include '../db_connect.php';

        $id_cab = $_POST['id'];
        $modulo = $_POST['modulo'];
        $horast = $_POST['horast'];
        $horasp = $_POST['horasp'];

        $sql = "INSERT INTO 
        pensum_det(pensum_cab_id, descri, horas_t, horas_p) 
        VALUES 
        ('$id_cab', 
         '$modulo',
         '$horast', 
         '$horasp')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
    //Editar un registro
    if ($action == 'editarDet') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $modulo = $_POST['modulo'];
        $horas_t = $_POST['horast'];
        $horas_p = $_POST['horasp'];

        $sql = "UPDATE pensum_det SET descri='$modulo', horas_t='$horas_t', horas_p='$horas_p' WHERE id_pensum_det ='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
    //Editar un cab
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $curso = $_POST['curso'];
        $resolucion = $_POST['resolucion'];
        $fecha_res = $_POST['fecha_res'];
        $modalidad = $_POST['modalidad'];
        $obs = $_POST['obs'];
        $estado = $_POST['estado'];

        $sql = "UPDATE pensum_cab SET curso='$curso', resolucion='$resolucion',fecha_res='$fecha_res',modalidad='$modalidad',obs='$obs', estado='$estado' WHERE id_pensum ='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else if (@!pg_query($conn, $sql)) {
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
        $offset = ($pagina - 1) * $registros_por_pagina;
        // Consulta para obtener datos
        $sql = "SELECT * FROM public.pensum_v
        ORDER by id_pensum LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);
        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='width:100%style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Curso</th>"
                . "<th>Total horas teoricas</th>"
                . "<th>Total horas practicas</th>"
                . "<th>Resolución</th>"
                . "<th>Fecha resolución</th>"
                . "<th>Modalidad</th>"
                . "<th>Obs</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='curso'>" . $fila['curso'] . "</td>";
                echo "<td class='total_horas_t'>" . $fila['total_horas_t'] . "</td>";
                echo "<td class='total_horas_p'>" . $fila['total_horas_p'] . "</td>";
                echo "<td class='resolucion'>" . $fila['resolucion'] . "</td>";
                echo "<td class='fecha_res_sf' style='display:none;'>" . $fila['fecha_res_sf'] . "</td>";
                if ($fila['fecha_res'] > date('Y-m-d')) {
                    echo "<td class='fecha_res'>" . $fila['fecha_res'] . "</td>";
                } else {
                    echo "<td class='fecha_res' style='color:#DC3545;'>" . $fila['fecha_res'] . "</td>";
                }
                echo "<td class='modalidad'>" . $fila['modalidad'] . "</td>";
                echo "<td class='obs'>" . $fila['obs'] . "</td>";
                echo "<td class='estado' style='display:none;'>" . $fila['estado'] . "</td>";
                if ($fila['estado'] == false) {
                    echo "<td style = 'color:#cc3300'>Inactivo</td>";
                } else if ($fila['estado'] == true) {
                    echo "<td style = 'color:#99cc33'>Activo</td>";
                }
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                <button class='btn btn-danger btn-eliminar-pensum btn-sm' ><i class='bi bi-trash'></i></button>
                <button class='btn btn-secondary btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_pensum'] . ")'><i class='bi bi-postcard'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM pensum_v";
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
    if ($action == 'verDetalle') {
        include '../db_connect.php';

        $id = $_POST['id'];

        $sql = "SELECT *
            FROM 
            pensum_det_v WHERE pensum_cab_id = $id ORDER by id_pensum_det";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>";
            echo "<tr>"
                . "<th>ID</th>"
                . "<th>Modulo</th>"
                . "<th>Horas teoricas</th>"
                . "<th>Horas practicas</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_pensum_det'] . "</td>";
                echo "<td class='pensum_cab_id' style='display:none;'>" . $fila['pensum_cab_id'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                echo "<td class='horas_t'>" . $fila['horas_t'] . "</td>";
                echo "<td class='horas_p'>" . $fila['horas_p'] . "</td>";
                echo "<td>
                    <button class='btn btn-secondary btn-editar-detalle btn-sm'  
                    data-bs-toggle='modal' data-bs-target='#modalEditarDetalle'><i class='bi bi-pencil'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No se encontraron registros.";
        }

    }
}
