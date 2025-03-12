<?php
$page_title = "Ver Participante";
require_once "../../includes/header.php";
require_once '../../controllers/participanteController.php';

$participanteController = new ParticipanteController();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger'>ID de participante no válido.</div>";
    require_once "../../includes/footer.php";
    exit;
}

$result = $participanteController->obtener($id);

if (!$result['success']) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($result['mensaje']) . "</div>";
    require_once "../../includes/footer.php";
    exit;
}

$participante = $result['data'];
?>
<!-- Incluir stylesheet innovador -->
<link rel="stylesheet" href="/assets/css/estilo_innovador.css">

<div class="container mt-5 innovative-form">
    <h2 class="mb-4 text-center"><i class="fas fa-user-circle"></i> Detalles del Participante</h2>
    
    <!-- Card: Datos Básicos -->
    <div class="card innovative-card mb-4 shadow-sm">
        <div class="card-header bg-info text-white text-center py-2">
            <i class="fas fa-id-badge"></i> Datos Básicos
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="mb-0"><strong>ID:</strong> <?= htmlspecialchars($participante['id_participante']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Tipo:</strong> <?= htmlspecialchars($participante['tipo_identificacion']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Identificación:</strong> <?= htmlspecialchars($participante['identificacion']) ?></p>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="mb-0"><strong>Nombres:</strong> <?= htmlspecialchars($participante['nombres']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Apellidos:</strong> <?= htmlspecialchars($participante['apellidos']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Fecha Nac.:</strong> <?= htmlspecialchars($participante['fecha_nacimiento']) ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-0"><strong>Edad:</strong> <?= htmlspecialchars($participante['edad']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Género:</strong> <?= htmlspecialchars($participante['genero']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Nacionalidad:</strong> <?= htmlspecialchars($participante['nacionalidad']) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card: Contacto y Ubicación -->
    <div class="card innovative-card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark text-center py-2">
            <i class="fas fa-map-marker-alt"></i> Contacto y Ubicación
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="mb-0"><strong>Correo:</strong> <?= htmlspecialchars($participante['correo']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Teléfono:</strong> <?= htmlspecialchars($participante['telefono']) ?></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($participante['direccion']) ?></p>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3">
                    <p class="mb-0"><strong>Estado:</strong> <?= htmlspecialchars($participante['estado']) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Municipio:</strong> <?= htmlspecialchars($participante['municipio']) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Parroquia:</strong> <?= htmlspecialchars($participante['parroquia']) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Ciudad:</strong> <?= htmlspecialchars($participante['ciudad']) ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0"><strong>Comuna:</strong> <?= htmlspecialchars($participante['comuna']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0"><strong>Sector:</strong> <?= htmlspecialchars($participante['sector']) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card: Información Adicional -->
    <div class="card innovative-card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white text-center py-2">
            <i class="fas fa-info-circle"></i> Información Adicional
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <p class="mb-0"><strong>Posee Discapacidad:</strong> <?= (($participante['posee_discapacidad'] ?? 0) ? 'Sí' : 'No') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Tipo de Discapacidad:</strong> <?= htmlspecialchars($participante['tipo_discapacidad'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Grado de Instrucción:</strong> <?= htmlspecialchars($participante['grado_instruccion'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Tipo de Economía:</strong> <?= htmlspecialchars($participante['tipo_economia'] ?? 'N/A') ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-0"><strong>Estudia:</strong> <?= (($participante['estudia'] ?? 0) ? 'Sí' : 'No') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>¿Qué Estudia?:</strong> <?= htmlspecialchars($participante['detalle_estudia'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Trabaja:</strong> <?= (($participante['trabaja'] ?? 0) ? 'Sí' : 'No') ?></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>Entidad de Trabajo:</strong> <?= htmlspecialchars($participante['entidad_trabajo'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <a href="listar.php" class="btn btn-outline-secondary mx-2">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
        <a href="editar.php?id=<?= htmlspecialchars($participante['id_participante']) ?>" class="btn btn-outline-primary mx-2">
            <i class="fas fa-edit"></i> Editar Participante
        </a>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
