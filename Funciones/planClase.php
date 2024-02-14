<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $materia_id = $_POST['id_materia'];
        $cronograma_id = $_POST['id_cronograma'];
        $obs = $_POST['obs'];
        $docente_reemplazo = $_POST['docente_r'];

        $sql = "INSERT INTO plan_clase_cab(fecha_ini, fecha_fin, materia_id, cronograma_id, obs, docente_reemplazo) 
        VALUES ('$fecha_ini','$fecha_fin','$materia_id','$cronograma_id','$obs','$docente_reemplazo')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }

        
    }
    if ($action == 'agregarDet') {
        include '../db_connect.php';

        $id_plan_clase = $_POST['id_plan_clase'];
        $procesoClase = $_POST['procesoClase'];
        $competencia = $_POST['competencia'];
        $indicadores = $_POST['indicadores'];
        $contenido = $_POST['contenido'];
        $actividad = $_POST['actividad'];

        $sql = "INSERT INTO plan_clase_det(plan_clase_cab_id, proceso_clase_cab_id, competencia, indicadores, contenido, actividad) 
        VALUES ('$id_plan_clase','$procesoClase','$competencia','$indicadores','$contenido','$actividad')";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo agregado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
            </script>
            ";
        }

        
    }
    //Editar un registro
    if ($action == 'editarCab') {
        include '../db_connect.php';

        $id = $_POST['idCab'];
        $fecha_ini = $_POST['fecha_ini'];
        $id_materia = $_POST['id_materia'];
        $fecha_fin = $_POST['fecha_fin'];

        $sql = "UPDATE plan_clase_cab SET fecha_ini = '$fecha_ini', fecha_fin = '$fecha_fin', materia_id = '$id_materia' WHERE id_plan_clase = '$id'";
        if (@pg_query($conn, $sql)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                <strong>Exito!</strong> Campo editado.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                <strong>Error!</strong> " . pg_last_error($conn) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>;
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
        $sql = "SELECT * FROM plan_clase_cab_v ORDER by id_plan_clase LIMIT $registros_por_pagina OFFSET $offset";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Evento</th>"
                . "<th>Docente de reemplazo</th>"
                . "<th>Modulo</th>"
                . "<th>Obs</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_clase'] . "</td>";
                echo "<td class='evento_id' style='display:none;'>" . $fila['evento_id'] . "</td>";
                echo "<td class='evento'>" . $fila['fecha'] . " > " . $fila['tipo'] . "</td>";
                if ($fila['docente_id'] == null) {
                    echo "<td> <span class='badge text-bg-warning'>Aún no asignado</span></td>";
                } else {
                    echo "<td class='docente_id' style='display:none;'>" . $fila['docente_id'] . "</td>";
                    echo "<td class='docente'>" . $fila['nombre'] . " " . $fila['apellido'] . "</td>";
                }
                if ($fila['modulo_id'] == null) {
                    echo "<td> <span class='badge text-bg-warning'>Aún no asignado</span> <i>(Asignar en cronograma)</i></td>";
                } else {
                    echo "<td class='modulo_id' style='display:none;'>" . $fila['modulo_id'] . "</td>";
                    echo "<td class='modulo'>" . $fila['descri'] . "</td>";
                }
                echo "<td class='obs'>" . $fila['obs'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button>
                <button class='btn btn-secondary btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalDetalle' onclick='loadDetalle(" . $fila['id_plan_clase'] . ",1)'><i class='bi bi-postcard'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM plan_clase_cab_v";
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
    // Obtener la lista de registros
    if ($action == 'verDetalle') {
        include '../db_connect.php';

        // Consulta para obtener los alumnos
        $sql = "SELECT * FROM plan_clase_det_v ORDER by id_plan_clase_det";
        $resultados = pg_query($conn, $sql);

        if (pg_num_rows($resultados) > 0) {
            echo "<table class='table table-hover table-dark table-sm' style='margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>ID</th>"
                . "<th>Proceso de clase</th>"
                . "<th>Competencia</th>"
                . "<th>Indicadores</th>"
                . "<th>Contenido</th>"
                . "<th>Actividad</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultados)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_plan_clase_det'] . "</td>";
                echo "<td class='plan_clase_cab_id' style='display:none;'>" . $fila['plan_clase_cab_id'] . "</td>";
                echo "<td class='proceso_clase_cab_id' style='display:none;'>" . $fila['proceso_clase_cab_id'] . "</td>";
                if($fila['proceso_clase_cab_id']==null){
                    echo "<td class='proceso_clase'><span class='badge text-bg-warning'>Aun no asignado</span></td>";
                }else {
                    echo "<td class='proceso_clase'>" . $fila['descripcion'] . " > " . $fila['fecha_entrega'] . "</td>";
                }
                echo "<td class='competencia'>" . $fila['competencia'] . "</td>";
                echo "<td class='indicadores'>" . $fila['indicadores'] . "</td>";
                echo "<td class='contenido'>" . $fila['contenido'] . "</td>";
                echo "<td class='actividad'>" . $fila['actividad'] . "</td>";
                echo "<td><button class='btn btn-secondary btn-editar btn-sm'  
                data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
                <button class='btn btn-danger btn-eliminar btn-sm' ><i class='bi bi-trash'></i></button>
                </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No se encontraron registros.";
        }
        
    }
}
