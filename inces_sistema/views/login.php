<?php
// login_alternativa1.php
// Página de inicio de sesión - Alternativa 1

$page_title = "Iniciar sesión - Sistema INCES - Alternativa 1";

require_once '../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'views/dashboard.php');
    exit();
}

$error_message = '';

require_once '../controllers/loginController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
       
    $loginController = new LoginController();
    $resultado = $loginController->login($username, $password);

    if ($resultado['success']) {
        header('Location: ' . $resultado['data']['redirect']);
        exit();
    } else {
        $error_message = $resultado['mensaje'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts y Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Condensed:wght@600&family=SF+Pro+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Estilos personalizados -->
    <style>
        :root {
            --bs-primary: #0A2946; /* Azul Profundo */
            --bs-accent: #3AB7B0;  /* Turquesa Operacional */
            --bs-bg-card: rgba(255, 255, 255, 0.92); /* Blanco Institucional semitransparente */
        }
        body {
            background: linear-gradient(135deg, #0A2946, #3AB7B0);
            font-family: 'SF Pro Display', sans-serif;
        }
        /* Preloader */
        #preloader {
            position: fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background: var(--bs-primary);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Tarjeta del formulario con Glassmorphism y borde luminoso */
        .card-custom {
            background: var(--bs-bg-card);
            border: 1px solid transparent;
            border-radius: 1rem;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 8px rgba(10,41,70,0.08);
            transition: transform 0.3s ease;
            animation: glowing 2s infinite;
        }
        .card-custom:hover {
            transform: translateY(-5px);
        }
        @keyframes glowing {
            0% {
                box-shadow: 0 0 5px var(--bs-accent);
            }
            50% {
                box-shadow: 0 0 20px var(--bs-accent);
            }
            100% {
                box-shadow: 0 0 5px var(--bs-accent);
            }
        }
        .card-header-custom {
            background: linear-gradient(135deg, #3AB7B0, #0A2946);
            color: #FFFFFF;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            text-align: center;
            font-size: 1.5rem;
            font-family: 'IBM Plex Sans Condensed', sans-serif;
            font-weight: 600;
        }
        /* Inputs con iconos */
        .input-group-text {
            background: transparent;
            border: none;
            color: var(--bs-primary);
        }
        .form-control {
            border-radius: 50px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(10,41,70,0.3);
        }
        /* Botón de acceso */
        .btn-custom {
            border-radius: 50px;
            transition: transform 0.2s ease;
        }
        .btn-custom:hover {
            transform: scale(1.03);
        }
        /* Checkbox recordar usuario rediseñado */
        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        /* Divider personalizado */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider:not(:empty)::before {
            margin-right: .75rem;
        }
        .divider:not(:empty)::after {
            margin-left: .75rem;
        }
        .system-title {
            font-family: 'IBM Plex Sans Condensed', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: linear-gradient(45deg, #3AB7B0, #0A2946);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
        /* Estilos para el logo profesional */
        .logo-image {
            width: 120px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        .logo-image:hover {
            transform: scale(1.05);
        }
        /* Estilos para la cabecera profesional */
        .navbar {
            border-bottom: 2px solid rgba(255,255,255,0.3);
        }
        .system-title {
            color: #FFFFFF;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        /* Estilos personalizados para el modal de recuperación */
        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 8px 16px rgba(0,0,0,0.25);
            border: none;
        }
        .modal-header {
            background: linear-gradient(45deg, var(--bs-accent), var(--bs-primary));
            color: #ffffff;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .modal-body {
            background-color: #fefefe;
            padding: 2rem;
        }
        /* Mejorar el botón del formulario */
        #formRecuperar button {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    
    <!-- Modal para recuperación de contraseña -->
    <div class="modal fade" id="modalRecuperar" tabindex="-1" aria-labelledby="modalRecuperarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modalRecuperarLabel" class="modal-title">Recuperar Contraseña</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="formRecuperar">
              <div class="mb-3">
                <label for="emailRecuperar" class="form-label">Ingresa tu correo electrónico</label>
                <input type="email" class="form-control" id="emailRecuperar" required>
                <div class="invalid-feedback">
                  Por favor ingresa un correo válido.
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100 btn-custom">Enviar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
<header>
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #0D3B66, #1D2D44); box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div class="container-fluid d-flex justify-content-between align-items-center py-3">
            <a class="navbar-brand" href="#">
                <!-- Logo profesional -->
                <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Logo" class="logo-image">
            </a>
            <span class="system-title">SISTEMA INCES</span>
            <div style="width:100px;"></div>
        </div>
    </nav>
</header>

<main class="container d-flex justify-content-center align-items-start" style="min-height: calc(100vh - 120px); padding-top: 4rem;">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card card-custom shadow-lg animate__animated animate__fadeInDown" style="max-width: 380px; margin: auto;">
                <div class="card-header card-header-custom">
                    Iniciar Sesión
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>
                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario" required value="<?= htmlspecialchars($username ?? '') ?>">
                                <div class="invalid-feedback">
                                    Ingresa tu usuario.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                <div class="invalid-feedback">
                                    Ingresa tu contraseña.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="recordar" name="recordar">
                            <label class="form-check-label" for="recordar">Recordar usuario</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-custom">Acceder</button>
                    </form>
                    <div class="mt-3 text-center">
                        <!-- Estilizado: botón-link con font-weight y color primario -->
                        <a href="#!" data-bs-toggle="modal" data-bs-target="#modalRecuperar" class="btn btn-link text-primary" style="font-weight: 600; text-decoration: none;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Preloader y validación
    window.addEventListener('load', function(){
        document.getElementById('preloader').style.display = 'none';
    });
    (function () {
        'use strict'
        const forms = document.querySelectorAll('form')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })();

    // Listener para el formulario de recuperación de contraseña
    $("#formRecuperar").on("submit", function(event) {
        event.preventDefault();
        var email = $("#emailRecuperar").val();
        $.ajax({
            url: "../controllers/recoveryController.php",
            type: "POST",
            data: { email: email },
            success: function(response) {
                $("#modalRecuperar .modal-body").html(response);
            },
            error: function() {
                alert("Error al procesar la solicitud.");
            }
        });
    });
</script>

</body>
</html>
