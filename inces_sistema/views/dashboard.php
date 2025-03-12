
<?php
require_once 'C:/xampp/htdocs/sistema_definitivo/inces_sistema/includes/verificar_sesion.php';

require_once '../config.php';


// dashboard.php
// Página principal del sistema INCES

$page_title = "Panel de Administración - Sistema INCES";

// Iniciar la sesión y verificar si el usuario está logueado
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Aseguramos que la sesión esté activa
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

require_once "../includes/header.php";  // Incluir el encabezado común
?>


<!-- Contenido principal sin banner -->
<div class="container mt-3">
    <div class="row">
        <!-- Columna de ancho completo -->
        <div class="col-md-12">
            <!-- Tarjeta con menú principal -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Sistema INCES</h2>
                </div>
                <div class="card-body">
                    <p class="text-center">Utiliza el menú de navegación para gestionar los participantes, cursos, usuarios y Formadores.</p>
                    <div class="nav-menu">
                        <a href="<?php echo BASE_URL; ?>views/participante/listar.php" class="btn btn-light btn-sm">
                            <i class="fas fa-users"></i> Gestionar Participantes
                        </a>
                        <a href="<?php echo BASE_URL; ?>//views//formacion//listar.php " class="btn btn-light btn-sm">
                            <i class="fas fa-book"></i> Gestionar formaciones
                        </a>
                        <a href="<?php echo BASE_URL; ?>views/instructor/listar.php" class="btn btn-light btn-sm">
                            <i class="fas fa-chalkboard-teacher"></i> Gestionar Instructores
                        </a>
                        <a href="<?php echo BASE_URL; ?>views/usuario/listar.php" class="btn btn-light btn-sm">
                            <i class="fas fa-user"></i> Gestionar Usuarios
                        </a>
                    </div>
                </div>
            </div>
            <!-- Otros bloques de información o contenido adicional -->
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    body {
        background-color: #e9ecef; /* Color de fondo más atractivo */
    }
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: scale(1.05);
    }
    .nav-menu {
        background-color: #007bff;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }
    .nav-menu a {
        margin: 5px;
    }
</style>

<!-- Información sobre el INCES -->
<div class="container mt-5">
    <div class="row">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header bg-primary text-white text-center">
            <h5>Participantes Registrados</h5>
            
        </div>
<div class="card-body text-center">
    <h1 id="contador-participantes" class="display-4 mb-3">
        
        <span>0</span>
    </h1>
    <i class="fas fa-users fa-lg text-primary mr-3"></i> 
    <p class="text-muted">Participantes registrados</p>
</div>
    </div>
</div>

<script>
$(document).ready(function() {
    function actualizarContador() {
        $.ajax({
            url: '../includes/contador_participantes.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#contador-participantes').text(response.count);
                } else {
                    $('#contador-participantes').text('Error');
                }
            },
            error: function() {
                $('#contador-participantes').text('Error de conexión');
            }
        });
    }

    // Actualizar al cargar
    actualizarContador();
    
    // Actualizar cada 5 segundos
    setInterval(actualizarContador, 5000);
});
</script>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>



        </div>
