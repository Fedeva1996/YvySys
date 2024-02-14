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
    <title>Calificaciones</title>
    <?php include("head.php"); ?>

    <script>
        //buscar
        $(document).ready(function () {
            $('#formBuscarCurso').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/calificacion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#tablaCalificacion').html(response);
                    }
                });
            });

            // Editar
            $(document).on('click', '.btn-editar', function () {
                var id = $(this).closest('tr').find('.id').text();
                var alumno = $(this).closest('tr').find('.alumno').text();
                var puntaje_proceso = $(this).closest('tr').find('.puntaje_proceso').text();
                var puntaje_trabajo = $(this).closest('tr').find('.puntaje_trabajo').text();
                var puntaje_examen = $(this).closest('tr').find('.puntaje_examen').text();
                var calificacion = $(this).closest('tr').find('.calificacion').text();
                var paso = $(this).closest('tr').find('.paso').text();
                var obs = $(this).closest('tr').find('.obs').text();
                if (paso == 1) {
                    var color = "#81c784";
                } else {
                    var color = "#e57373";
                }
                $('#editId').val(id);
                $('#editNombre').val(alumno);
                $('#editProceso').val(puntaje_proceso);
                $('#editTrabajo').val(puntaje_trabajo);
                $('#editExamen').val(puntaje_examen);
                $('#editTotal').val(Number(puntaje_proceso) + Number(puntaje_trabajo) + Number(puntaje_examen));
                $('#editCalificacion').val(calificacion);
                $('#editPaso').val(paso);
                $('#selectPaso').val(paso);
                $("select.selectPaso selected").val(paso).change();
                changeColor(color);
                $('#editObs').val(obs);

                // Asignar el valor de action al formulario de edición
                $('#formEditarCalificacion').find('input[name="action"]').val('editar');
            });
            $('#formEditarCalificacion').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'funciones/calificacion.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#formBuscarCurso').submit();
                        $('#resultados').html(response);
                    },
                });
            });
            //generar
            $(document).on('click', '.btn-generar', function () {
                // Obtener el ID
                var id = $(this).closest('tr').find('.id').text();

                // Confirmar la generación de eventos
                swal.fire({
                    title: "Cuidado!",
                    text: "¿Estás seguro de que deseas generar la escala?",
                    icon: "warning",
                    showCancelButton: true,
                    background: "#212529",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Confirmar",
                    cancelButtonColor: '#6e7881',
                    cancelButtonText: "Cancelar"
                })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            $.ajax({
                                url: 'funciones/calificacion.php',
                                type: 'POST',
                                data: {
                                    action: 'generar',
                                    id: id,
                                },

                                success: function (response) {
                                    cargarPagina(1);
                                    $('#resultados').html(response);
                                }
                            });
                        } else {
                            swal.fire({
                                title: "No se generaran los eventos de este cronograma!",
                                background: "#212529"
                            })
                        }
                    });
            });
            //paginacion
            $(document).ready(function () {
                function cargarPagina(pagina) {
                    $.ajax({
                        url: 'funciones/calificacion.php',
                        type: 'POST',
                        data: {
                            action: 'listar',
                            pagina: pagina,
                        },
                        success: function (response) {
                            $('#tablaCalificacion').html(response);
                        }
                    });
                }
                cargarPagina(1);
                $(document).on('click', '.btn-pagina', function () {
                    var pagina = $(this).data('pagina');

                    cargarPagina(pagina);
                });

            });

            //actualiza select de materias
            $(document).ready(function () {
                $("#id_curso").change(function () {
                    var valorSelect1 = document.getElementById("id_curso").value;
                    $.ajax({
                        url: "funciones/calificacion.php",
                        type: "POST",
                        data: {
                            id_curso: valorSelect1,
                            action: "buscarCurso"
                        },
                        success: function (data) {
                            $("#id_materia").empty();
                            $("#id_materia").append(data);
                        }
                    });
                });
            });
            $(document).ready(function () {
                $(".input-number").on("input", function () {
                    var num1 = parseInt($("#editProceso").val()) || 0;
                    var num2 = parseInt($("#editTrabajo").val()) || 0;
                    var num3 = parseInt($("#editExamen").val()) || 0;

                    $.ajax({
                        type: "POST",
                        url: "funciones/calificacion.php",
                        dataType: 'json',
                        data: {
                            num1: num1,
                            num2: num2,
                            num3: num3,
                            action: "sumar"
                        },
                        success: function (data) {
                            $("#editTotal").val(data[0]);
                            $("#editCalificacion").val(data[1]);
                            $('#editPaso').val(data[2]);
                            $('#selectPaso').val(data[2]);
                            changeColor(data[3]);
                        }
                    });
                });
            });

            function changeColor(colorParam) {
                let color = colorParam;
                var optionElement = document.getElementById('selectPaso');
                optionElement.style.background = color;
            };
        });
    </script>
</head>

<body>
    <div class="mb-2">
        <?php
        include("navbar.php");
        ?>
    </div>
    <div class="container">
        <h2>Calificaciones</h2>
        <div class="input-group mb-2">
            <button class="btn btn-dark" data-bs-toggle='modal' data-bs-target='#modalBuscarCurso'> <i
                    class="bi bi-search"></i> Buscar</button>
        </div>
        <!-- meustra mensaje exito/error -->
        <div id="resultados"></div>
        <!-- Tabla -->
        <div id="tablaCalificacion"></div>
    </div>
    
</body>

</html>