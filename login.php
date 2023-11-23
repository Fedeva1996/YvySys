<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Establece la conexión con la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'yvysys');

    // Verifica si hay errores de conexión
    if ($conexion->connect_error) {
        die('Error de conexión: ' . $conexion->connect_error);
    }

    // Escapa los datos del usuario para prevenir inyección de SQL
    $usuario = $conexion->real_escape_string($_POST['username']);
    $contraseña = $conexion->real_escape_string($_POST['password']);

    // Busca el usuario en la base de datos
    $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND estado = 1";
    $resultado = $conexion->query($consulta);

    // Verifica si el usuario existe y la contraseña es correcta
    if ($resultado->num_rows > 0) {
        // El usuario existe, verificar la contraseña
        $fila = $resultado->fetch_assoc();
        if ($usuario == $fila['usuario'] && md5($contraseña) == $fila['contrasena']) {
            // La contraseña es correcta, iniciar sesión
            session_start();
            $_SESSION['usuario'] = $usuario;            
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['apellido'] = $fila['apellido'];
            $_SESSION['rol_id'] = $fila['rol_id'];

            // Reiniciar el contador de intentos fallidos
            $sql = "UPDATE usuarios SET intentos_fallidos = 0 WHERE usuario = '$usuario'";
            $conexion->query($sql);
            // Redirigir al usuario a la página principal
            header("Location: dashboard.php");
            exit();
        } else {
            // La contraseña es incorrecta, aumentar el contador de intentos fallidos
            $intentos_fallidos = $fila['intentos_fallidos'] + 1;
            $sql = "UPDATE usuarios SET intentos_fallidos = '$intentos_fallidos' WHERE usuario = '$usuario'";
            $conexion->query($sql);
            // Verificar si se ha superado el límite de intentos fallidos
            if ($intentos_fallidos >= 3) {
                // El usuario ha superado el límite de intentos fallidos, cambiar su estado a inactivo
                $sql = "UPDATE usuarios SET estado = 0 WHERE usuario = '$usuario'";
                $conexion->query($sql);
                echo "Has superado el límite de intentos fallidos. Tu cuenta ha sido desactivada.";
                exit();
            } else {
                echo "Contraseña incorrecta. Intento $intentos_fallidos de 3.";
            }
        }
    } else {
        // El usuario no existe en la base de datos
        echo "El usuario no existe o se encuentra bloqueado.";
    }
}
?>
