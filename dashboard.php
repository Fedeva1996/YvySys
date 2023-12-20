<!DOCTYPE html>
<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Redirige al formulario de inicio de sesión
    exit();
} else {
    if ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2) {
        header('Location: dashboard.php'); // Redirige al dashboard
        exit();
    }
}
?>
<html>

<head>
    <title>Dashboard</title>
    <?php include("head.php"); ?>
    <script>
        $(document).ready(function () {
            $("#alert").hide();
            $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                $("#alert").slideUp(500);
            });
        });
    </script>
</head>

<body class="dark-theme">
    <div class="mb-2">
        <?php
        include("navbar.php");
        ?>
    </div>
    <div id="respuesta">
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert">
            <strong>Holy guacamole!</strong> You should check in on some of those fields below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</body>

</html>