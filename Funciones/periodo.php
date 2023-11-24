<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $ano = $_POST['ano'];
        $descri = $_POST['descri'];

        $sql = "INSERT INTO periodo (ano, descripcion) "
            . "VALUES ('$ano', '$descri')";
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

        $sql = "DELETE FROM periodo WHERE id_periodo='$id'";
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
        $ano = $_POST['ano'];
        $descri = $_POST['descri'];
        $estado = $_POST['estado'];

        $sql = "UPDATE periodo SET ano='$ano', descripcion='$descri', estado='$estado' WHERE id_periodo='$id'";
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
        $sql = "SELECT * FROM periodo ORDER by id_periodo DESC LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Año</th>"
                . "<th>Descripción</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_periodo'] . "</td>";
                echo "<td class='ano'>" . $fila['ano'] . "</td>";
                echo "<td class='descri'>" . $fila['descripcion'] . "</td>";
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
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_periodo'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_periodo"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM periodo";
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
        $sql = "SELECT * FROM periodo WHERE ano LIKE '%$buscar%' ORDER by id_periodo DESC LIMIT $offset, $registros_por_pagina";
        $resultado = pg_query($conn, $sql);

        if (pg_num_rows($resultado) > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th>Id</th>"
                . "<th>Año</th>"
                . "<th>Descripción</th>"
                . "<th>Estado</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_periodo'] . "</td>";
                echo "<td class='ano'>" . $fila['ano'] . "</td>";
                echo "<td class='descri'>" . $fila['descripcion'] . "</td>";
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
                echo "<td><button class='btn btn-secondary btn-editar btn-sm' data-id='" . $fila['id_periodo'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_periodo"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM periodo";
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
}
