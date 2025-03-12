<?php
// header.php
// Archivo para gestionar el encabezado común de todas las páginas
require_once __DIR__ . '/../config.php';  // Asegura que la constante BASE_URL esté disponible

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Sistema INCES' ?></title>

    <!-- Incluir Bootstrap CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Font Awesome para iconos -->
    <link href="<?php echo BASE_URL; ?>assets/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Incluir Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Estilos personalizados para cabecera -->
    <style>
        /* Fondo de página con degradado profesional */
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        }
        /* Animación para el fondo del header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        /* Cabecera profesional, elegante e innovadora */
        header {
            position: relative;
            background: linear-gradient(135deg, #343a40, #495057); /* tonos oscuros elegantes */
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            padding: 1rem 0;
            overflow: hidden;
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }
        /* Pseudo-elemento para efecto radial innovador */
        header::before {
            content: "";
            position: absolute;
            top: -10%;
            left: -10%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.05), transparent 70%);
            transform: rotate(15deg);
        }
        /* Navbar transparente para resaltar el header */
        .navbar {
            background: transparent;
            padding: 0;
            z-index: 1;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: #fff !important;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
            transition: transform 0.3s;
        }
        .navbar-brand:hover {
            transform: scale(1.07);
        }
        .nav-link {
            font-weight: 500;
            color: #fff !important;
            margin: 0 0.75rem;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            transition: color 0.3s, transform 0.3s;
        }
        .nav-link:hover {
            color: #f0e68c !important;
            transform: translateY(-3px);
        }
        /* Botón toggler rediseñado con animación de borde */
        .navbar-toggler {
            border: 2px solid rgba(255,255,255,0.9);
            transition: border-color 0.3s;
        }
        .navbar-toggler:hover {
            border-color: #f0e68c;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.9)' stroke-width='2' stroke-linecap='round' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        /* Mejoras en el margen del contenedor para destacar el header */
        .container {
            flex: 1;
            margin-top: 90px;
        }
        footer {
            flex-shrink: 0;
            padding: 30px 20px; /* padding adecuado */
            min-height: 80px;   /* altura mínima profesional */
            background-color: #343a40;
            color: #fff;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<header>
<!-- Navbar actualizado para un estilo más moderno -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <!-- Logo y texto en el navbar sin imagen -->
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
        Sistema INCES
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menú de navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/dashboard.php" aria-label="Ir a la página de inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/participante/listar.php" aria-label="Gestionar participantes">Participantes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/formacion/listar.php" aria-label="Gestionar formaciones">Formaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/formacion/asignaciones.php" aria-label="Ver asignaciones">Asignaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/instructor/listar.php" aria-label="Gestionar instructores">Formadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/usuario/listar.php" aria-label="Gestionar usuarios">Usuarios</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>views/logout.php" aria-label="Cerrar sesión">Cerrar sesión</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</nav>
</header>
<div class="container mt-5">
<!-- Incluir Bootstrap JS (opcional para funcionalidades interactivas) -->
<script src="<?php echo BASE_URL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
