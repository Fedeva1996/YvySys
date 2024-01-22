<?php
session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'login') {
        // Connection data
        $hostname = 'localhost';
        $dbname = 'yvysys';
        $username = 'postgres';
        $password = 'Maitei.pg96';

        // Create connection string
        $conn_string = "host=$hostname dbname=$dbname user=$username password=$password";

        // Create connection
        $conexion = pg_connect($conn_string);

        // Check connection
        if (!$conexion) {
            die("Error in connection: " . pg_last_error());
        }

        // Escapa los datos del usuario para prevenir inyección de SQL
        $usuario = pg_escape_string($conexion, $_POST['username']);
        $contraseña = pg_escape_string($conexion, $_POST['password']);

        // Busca el usuario en la base de datos
        $consulta = "SELECT * FROM usuario_v WHERE usuario='$usuario' AND estado = 1";
        $resultado = pg_query($conexion, $consulta);

        // Verifica si el usuario existe y la contraseña es correcta
        if (pg_num_rows($resultado) > 0) {
            // El usuario existe, verificar la contraseña
            $fila = pg_fetch_assoc($resultado);
            if ($usuario == $fila['usuario'] && md5($contraseña) == $fila['contrasena']) {
                // La contraseña es correcta, iniciar sesión
                // Evitar la posibilidad de fijación de sesión
                session_regenerate_id(true);

                $_SESSION['usuario'] = $usuario;
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['apellido'] = $fila['apellido'];
                $_SESSION['rol_id'] = $fila['rol_id'];
                $_SESSION['rol'] = $fila['descri'];

                // Reiniciar el contador de intentos fallidos de una manera más segura
                $sql = "UPDATE usuarios SET intentos_fallidos = 0 WHERE usuario = '$usuario'";
                pg_query($conexion, $sql);

                // Mostrar mensaje de bienvenida
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='alert'>
                        <strong>Bienvenido!</strong> " . $fila['nombre'] . " " . $fila['apellido'] . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        <div class='spinner-border' role='status'>
                        <span class='visually-hidden'>Iniciando...</span>
                        </div>";
                exit();
            } else {
                // La contraseña es incorrecta, aumentar el contador de intentos fallidos
                $intentos_fallidos = $fila['intentos_fallidos'] + 1;
                $sql = "UPDATE usuarios SET intentos_fallidos = '$intentos_fallidos' WHERE usuario = '$usuario'";
                pg_query($conexion, $sql);
                // Verificar si se ha superado el límite de intentos fallidos
                if ($intentos_fallidos >= 3) {
                    // El usuario ha superado el límite de intentos fallidos, cambiar su estado a inactivo
                    $sql = "UPDATE usuarios SET estado = 0 WHERE usuario = '$usuario'";
                    pg_query($conexion, $sql);
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Contraseña incorrecta!</strong> Has superado el límite de intentos fallidos. Tu cuenta ha sido bloqueada.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    exit();
                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
                    <strong>Contraseña incorrecta!</strong> Intento $intentos_fallidos de 3.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                }
            }
        } else {
            // El usuario no existe en la base de datos
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='alert'>
            <strong>Error!</strong> El usuario no existe o ha sido bloqueado.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    }
}
