<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $curso = $_POST['curso'];

        $sql = "INSERT INTO pensum_cab(curso) 
        VALUES ('$curso')";
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
                horas_p) 
            SELECT 
                id_pensum, 
                '$modulo', 
                '$horast', 
                '$horasp' 
            FROM 
                pensum_cab 
            ORDER BY id_pensum DESC LIMIT 1;
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
        }

        $conn->close();
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
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                Swal.fire(
                'Agregado!',
                'Ha editado el registro con exito!',
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
    //Editar un registro
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $curso = $_POST['curso'];

        $sql = "UPDATE pensum_cab SET curso='$curso' WHERE id_pensum ='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                Swal.fire(
                'Agregado!',
                'Ha editado el registro con exito!',
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


    // Obtener la lista de registros
    if ($action == 'buscarPensum') {
        include '../db_connect.php';

        // Paginación
        $registros_por_pagina = 10;
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;
        $id_curso = isset($_POST['curso']) ? $_POST['curso'] : "";
        $offset = ($pagina - 1) * $registros_por_pagina;

        $curso = isset($_POST['id_curso']) ? $_POST['id_curso'] : $id_curso;
        if (isset($_POST['id_curso'])) {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
        pensum_det.id_pensum_det,
        pensum_det.descri,
        pensum_det.horas_t,
        pensum_det.horas_p,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        pensum_cab.total_horas_t,
        pensum_cab.total_horas_p,
        pensum_cab.estado
        FROM pensum_det
        JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum 
        WHERE pensum_cab.curso LIKE '%$curso%'
        ORDER by id_pensum_det LIMIT $offset, $registros_por_pagina";
            $resultado = $conn->query($sql);
            $cabecera = $conn->query($sql);
        } else {
            // Consulta para obtener los alumnos
            $sql = "SELECT 
        pensum_det.id_pensum_det,
        pensum_det.descri,
        pensum_det.horas_t,
        pensum_det.horas_p,
        pensum_cab.id_pensum,
        pensum_cab.curso,
        pensum_cab.total_horas_t,
        pensum_cab.total_horas_p,
        pensum_cab.estado
        FROM pensum_det
        JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum 
        WHERE pensum_cab.curso LIKE '%$id_curso'
        ORDER by id_pensum_det LIMIT $offset, $registros_por_pagina";
            $resultado = $conn->query($sql);
            $cabecera = $conn->query($sql);
        }
        if ($resultado->num_rows > 0) {
            if ($cab = $cabecera->fetch_assoc()) {
                echo "<!-- cabecera -->";
                echo "<div class='row' data-bs-theme='dark'>";
                echo "<div class='row'>";
                echo "<label>Curso</label>";
                echo "<div class='input-group mb-3'>
                <input readonly type='text' class='form-control' value='" . $cab['curso'] . "' aria-describedby='button-addon2'>
                <button class='btn btn-outline-secondary btn-editar-cab' data-id='" . $cab['id_pensum'] . "' type='button' id='button-addon2' data-bs-toggle='modal' data-bs-target='#modalEditarCab'>Editar</button>
                </div>";
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
            while ($fila = $resultado->fetch_assoc()) {
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
            JOIN pensum_cab ON pensum_det.pensum_cab_id = pensum_cab.id_pensum";
            $resultado_total = $conn->query($sql_total);
            $fila_total = $resultado_total->fetch_assoc();
            $total_registros = $fila_total['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            echo "<div style='width:100%';  margin-left: auto; margin-right: auto;' class='paginacion' data-bs-theme='dark'>";
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
