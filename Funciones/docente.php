<?php
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    function calcularEdad($fecha_nacimiento)
    {
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        return $diferencia->format("%y");
    }

    // Agregar un nuevo registro
    if ($action == 'agregar') {
        include '../db_connect.php';

        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $edad = $_POST['edad'];
        $sexo = $_POST['sexo'];
        $correo = $_POST['correo'];
        $nacionalidad = $_POST['nacionalidad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql = "INSERT INTO docentes (ci, nombre, apellido, edad, sexo, correo, nacionalidad, direccion, telefono) "
            . "VALUES ('$ci','$nombre', '$apellido', '$edad', '$sexo', '$correo', '$nacionalidad', '$direccion', '$telefono')";
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

        $sql = "DELETE FROM docentes WHERE id_docente='$id'";
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
        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $edad = $_POST['edad'];
        $sexo = $_POST['sexo'];
        $correo = $_POST['correo'];
        $nacionalidad = $_POST['nacionalidad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql = "UPDATE docentes SET ci='$ci', nombre='$nombre', apellido='$apellido', edad=$edad, sexo='$sexo', correo='$correo', nacionalidad='$nacionalidad', direccion='$direccion', telefono='$telefono' WHERE id_docente='$id'";
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
        $sql = "SELECT * FROM docentes ORDER by id_docente LIMIT $offset, $registros_por_pagina";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th></th>"
                . "<th>Ci</th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Edad</th>"
                . "<th>Sexo</th>"
                . "<th>Correo</th>"
                . "<th>Nacionalidad</th>"
                . "<th>Direccion</th>"
                . "<th>Telefono</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_docente'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='edad'>" . calcularEdad($fila['fecha_nac'])  . "</td>";
                echo "<td class='sexo'>" . $fila['sexo'] . "</td>";
                echo "<td class='correo'>" . $fila['correo'] . "</td>";
                echo "<td class='nacionalidad'>" . $fila['nacionalidad'] . "</td>";
                echo "<td class='direccion'>" . $fila['direccion'] . "</td>";
                echo "<td class='telefono'>" . $fila['telefono'] . "</td>";
                echo "<td><button class='btn btn-dark btn-editar btn-sm' data-id='" . $fila['id_docente'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_docente"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";

            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM docentes";
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

        $sql = "SELECT * FROM docentes WHERE nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR ci LIKE '%$buscar%' ORDER by id_docente LIMIT $offset, $registros_por_pagina";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<table class='table table-hover table-dark' style='width:100%';  margin-left: auto; margin-right: auto;'>";
            echo "<thead class='table-dark'>"
                . "<tr>"
                . "<th></th>"
                . "<th>Ci</th>"
                . "<th>Nombre</th>"
                . "<th>Apellido</th>"
                . "<th>Edad</th>"
                . "<th>Sexo</th>"
                . "<th>Correo</th>"
                . "<th>Nacionalidad</th>"
                . "<th>Direccion</th>"
                . "<th>Telefono</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead>";
            echo "<tbody class='table-group-divider'>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='id'>" . $fila['id_docente'] . "</td>";
                echo "<td class='ci'>" . $fila['ci'] . "</td>";
                echo "<td class='nombre'>" . $fila['nombre'] . "</td>";
                echo "<td class='apellido'>" . $fila['apellido'] . "</td>";
                echo "<td class='edad'>" . calcularEdad($fila['fecha_nac']) . "</td>";
                echo "<td class='sexo'>" . $fila['sexo'] . "</td>";
                echo "<td class='correo'>" . $fila['correo'] . "</td>";
                echo "<td class='nacionalidad'>" . $fila['nacionalidad'] . "</td>";
                echo "<td class='direccion'>" . $fila['direccion'] . "</td>";
                echo "<td class='telefono'>" . $fila['telefono'] . "</td>";
                echo "<td><button class='btn btn-dark btn-editar btn-sm' data-id='" . $fila['id_docente'] . "' 
        data-bs-toggle='modal' data-bs-target='#modalEditar'><i class='bi bi-pencil'></i></button>| 
        <button class='btn btn-danger btn-eliminar btn-sm' data-id='" . $fila["id_docente"] . "'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            // Paginación
            $sql_total = "SELECT COUNT(*) as total FROM alumnos";
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
