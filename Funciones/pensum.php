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
        pensum_cab(id_pensum, curso, resolucion, fecha_res, modalidad, obs) 
        VALUES 
        (COALESCE((SELECT MAX(id_pensum) + 1 FROM pensum_cab), 1), 
         '$curso', 
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

        pg_close($conn);
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
                id_pensum_det,
                pensum_cab_id,
                descri,
                horas_t,
                horas_p
            )
            SELECT
                COALESCE((SELECT MAX(id_pensum_det) + 1 FROM pensum_det), 1),
                id_pensum,
                '$modulo',
                '$horast',
                '$horasp'
            FROM
                pensum_cab
            ORDER BY
                id_pensum DESC
            LIMIT 1;
            ";
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

        pg_close($conn);
    }
    //Editar un registro
    if ($action == 'editarDet') {
        include '../db_connect.php';

        $id = $_POST['id'];
        $id_pensum = $_POST['id_pensum'];
        $modulo = $_POST['modulo'];
        $horas_t = $_POST['horast'];
        $horas_p = $_POST['horasp'];

        $sql = "UPDATE pensum_det SET pensum_cab_id='$id_pensum', descri='$modulo', horas_t='$horas_t', horas_p='$horas_p' WHERE id_pensum_det ='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado, se actualizara la página.
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
    //Editar un registro
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $curso = $_POST['curso'];
        $resolucion = $_POST['resolucion'];
        $fecha_res = $_POST['fecha_res'];
        $modalidad = $_POST['modalidad'];
        $obs = $_POST['obs'];

        $sql = "UPDATE pensum_cab SET curso='$curso', resolucion='$resolucion',fecha_res='$fecha_res',modalidad='$modalidad',obs='$obs' WHERE id_pensum ='$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado, se actualizara la página.
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
    if ($action == 'buscarPensum') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;
        // Consulta para obtener datos
        $sql = "SELECT * FROM public.pensum_v
        WHERE id_pensum = '$id'
        ORDER by id_pensum_det LIMIT $registros_por_pagina OFFSET $offset";
        $resultado = pg_query($conn, $sql);
        $cabecera = pg_query($conn, $sql);
        if (pg_num_rows($resultado) > 0) {
            if ($cab = pg_fetch_assoc($cabecera)) {
                echo "<!-- cabecera -->";
                echo "<div class='head' data-bs-theme='dark'>";
                echo "<div class='row'>";
                echo "<label>Curso</label>";
                echo "<div class='input-group mb-3'>
                <input readonly type='text' class='form-control curso' value='" . $cab['curso'] . "' aria-describedby='button-addon2'>
                <button class='btn btn-outline-secondary btn-editar-cab' data-id='" . $cab['id_pensum'] . "' type='button' id='button-addon2' data-bs-toggle='modal' data-bs-target='#modalEditarCab'>Editar</button>
                </div>";
                echo "</div>";
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<label>Resolución</label>";
                echo "<input readonly type='text' class='form-control resolucion' value='" . $cab['resolucion'] . "'>";
                echo "</div>";
                echo "<div class='col'>";
                echo "<label>Fecha resolución</label>";
                echo "<input readonly type='text' class='form-control fecha_res' value='" . $cab['fecha_res'] . "'>";
                echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<label>Modalidad</label>";
                if($cab['modalidad'] == "virtual"){
                    echo "<input type='hidden' class='modalidad' value='virtual'>";
                    echo "<input readonly type='text' class='form-control ' value='Virtual'>";
                }
                else if($cab['modalidad'] == "presencial"){
                    echo "<input type='hidden' class=' modalidad' value='presencial'>";
                    echo "<input readonly type='text' class='form-control ' value='Presencial'>";
                }
                else{
                    echo "<input type='hidden' class=' modalidad' value='semi'>";
                    echo "<input readonly type='text' class='form-control ' value='Semi presencial'>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<label>Obs</label>";
                echo "<input readonly type='text' class='form-control obs' value='" . $cab['obs'] . "'>";
                echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<label>Total horas teoricas</label>";
                echo "<input readonly type='text' class='form-control' value='" . $cab['total_horas_t'] . "'>";
                echo "</div>";
                echo "<div class='col'>";
                echo "<label>Total horas practicas</label>";
                echo "<input readonly type='text' class='form-control' value='" . $cab['total_horas_p'] . "'>";
                echo "</div>";
                echo "</div>";
                echo "</br>";
            }
            echo "<table class='table table-hover table-dark' style='width:100%;  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Materia / Modulo</th>"
                . "<th>Horas teoricas</th>"
                . "<th>Horas practicas</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_pensum_det'] . "</td>";
                echo "<td class='id_cab' style='display:none;'>" . $fila['id_pensum'] . "</td>";
                echo "<td class='descri'>" . $fila['descri'] . "</td>";
                echo "<td class='horas_t'>" . $fila['horas_t'] . "</td>";
                echo "<td class='horas_p'>" . $fila['horas_p'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_pensum_det'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_pensum_det"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM pensum_det
            JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum
            WHERE id_pensum = '$id'";
            $resultado_total = pg_query($conn, $sql_total);
            $fila_total = pg_fetch_assoc($resultado_total);
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div ;  margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
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
