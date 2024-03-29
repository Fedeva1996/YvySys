<nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">YvySys</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Academico
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="pensums.php">Pensum</a></li>
            <li><a class="dropdown-item" href="cronogramas.php">Cronogramas</a></li>
            <li><a class="dropdown-item" href="planesExamen.php">Planes de Examen</a></li>
            <li><a class="dropdown-item" href="inscripciones.php">Inscripciones</a></li>
            <li><a class="dropdown-item" href="planesClase.php">Planes de clase</a></li>
            <li><a class="dropdown-item" href="asistencias.php">Asistencias</a></li>
            <li><a class="dropdown-item" href="procesosClase.php">Procesos de clase</a></li>
            <li><a class="dropdown-item" href="puntuarExamen.php">Puntuar Examen</a></li>
            <li><a class="dropdown-item" href="calificaciones.php">Calificaciones</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Documental
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="fichaAcademica.php">Ficha Academica</a></li>
            <li><a class="dropdown-item" href="formulario03.php">Formulario 03</a></li>
            <li><a class="dropdown-item" href="justificativoAlumnos.php">Justificativo Alumnos</a></li>
            <li><a class="dropdown-item" href="desmatriculacionAlumnos.php">Desmatriculación Alumnos</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Referencias
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="personas.php">Personas</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="alumnos.php">Alumnos</a></li>
            <li><a class="dropdown-item" href="docentes.php">Docentes</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="cursos.php">Cursos</a></li>
            <li><a class="dropdown-item" href="modulos.php">Modulos</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="periodos.php">Periodos</a></li>
            <li><a class="dropdown-item" href="turnos.php">Turnos</a></li>
          </ul>
        </li>
      </ul>
      <span class="navbar-text">
        <?php
          echo "Bienvenido <strong>". $_SESSION['rol'] ."</strong> " . $_SESSION['nombre'];
        ?>
      </span>
      &nbsp;&nbsp;
      <a class="btn btn-outline-danger" role="button" href="logout.php">
        Salir <i class="bi bi-box-arrow-in-right"></i>
      </a>
    </div>
  </div>
</nav>