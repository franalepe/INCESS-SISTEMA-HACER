<?php
$page_title = "Editar Participante";
// Incluir el nuevo stylesheet innovador
require_once "../../includes/header.php";
?>
<?php
require_once '../../controllers/participanteController.php';
require_once '../../config/ubicaciones.php';

$participanteController = new ParticipanteController();

// Validación del ID en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de participante inválido.</div>";
    require_once "../../includes/footer.php";
    exit();
}

// Obtener datos del participante
$resp = $participanteController->obtener($_GET['id']);
if (!$resp['success']) {
    echo "<div class='alert alert-danger'>Participante no encontrado.</div>";
    require_once "../../includes/footer.php";
    exit();
}
$participante = $resp['data'];

// Inicializar variables a partir de los datos almacenados
$tipo_identificacion = ($participante['tipo_identificacion'] ?? '') == 'Cédula' ? 'V' : 'P';
$identificacion      = $participante['identificacion'] ?? '';
$nombres             = $participante['nombres'] ?? '';
$apellidos           = $participante['apellidos'] ?? '';
$correo              = $participante['correo'] ?? '';
$telefono            = $participante['telefono'] ?? '';
$direccion           = $participante['direccion'] ?? '';
$fecha_nacimiento    = $participante['fecha_nacimiento'] ?? '';
$edad                = $participante['edad'] ?? '';
$genero              = $participante['genero'] ?? '';
$nacionalidad        = $participante['nacionalidad'] ?? '';
$estado              = $participante['estado'] ?? '';
$municipio           = $participante['municipio'] ?? '';
$parroquia           = $participante['parroquia'] ?? '';
$comuna              = $participante['comuna'] ?? '';
$ciudad              = $participante['ciudad'] ?? '';
$sector              = $participante['sector'] ?? '';
$posee_discapacidad  = $participante['posee_discapacidad'] ?? 0;
$tipo_discapacidad   = $participante['tipo_discapacidad'] ?? '';
$grado_instruccion   = $participante['grado_instruccion'] ?? '';
$estudia             = $participante['estudia'] ?? 0;
$detalle_estudia     = $participante['detalle_estudia'] ?? '';
$tipo_economia       = $participante['tipo_economia'] ?? '';
$trabaja             = $participante['trabaja'] ?? 0;
$entidad_trabajo     = $participante['entidad_trabajo'] ?? '';

$error_message = '';
$success_message = '';

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtención y limpieza de datos
    $tipo_identificacion = trim($_POST['tipo_identificacion'] ?? '');
    $identificacion      = trim($_POST['identificacion'] ?? '');
    $nombres             = trim($_POST['nombres'] ?? '');
    $apellidos           = trim($_POST['apellidos'] ?? '');
    $correo              = trim($_POST['correo'] ?? '');
    $telefono            = trim($_POST['telefono'] ?? '');
    $direccion           = trim($_POST['direccion'] ?? '');
    $fecha_nacimiento    = trim($_POST['fecha_nacimiento'] ?? '');
    $edad                = trim($_POST['edad'] ?? '');
    $genero              = trim($_POST['genero'] ?? '');
    $nacionalidad        = trim($_POST['nacionalidad'] ?? '');
    $estado              = trim($_POST['estado'] ?? '');
    $municipio           = trim($_POST['municipio'] ?? '');
    $parroquia           = trim($_POST['parroquia'] ?? '');
    $comuna              = trim($_POST['comuna'] ?? '');
    $ciudad              = trim($_POST['ciudad'] ?? '');
    $sector              = trim($_POST['sector'] ?? '');
    $posee_discapacidad  = isset($_POST['posee_discapacidad']) ? 1 : 0;
    $tipo_discapacidad   = trim($_POST['tipo_discapacidad'] ?? '');
    $grado_instruccion   = trim($_POST['grado_instruccion'] ?? '');
    $estudia             = isset($_POST['estudia']) ? $_POST['estudia'] : 0;
    $detalle_estudia     = trim($_POST['detalle_estudia'] ?? '');
    $tipo_economia       = trim($_POST['tipo_economia'] ?? '');
    $trabaja             = isset($_POST['trabaja']) ? 1 : 0;
    $entidad_trabajo     = trim($_POST['entidad_trabajo'] ?? '');

    // Validaciones básicas (se pueden ampliar)
    if (empty($tipo_identificacion) || empty($identificacion) || empty($nombres) || empty($apellidos) ||
        empty($correo) || empty($telefono) || empty($direccion) || empty($estado) ||
        empty($municipio) || empty($parroquia)) {
        $error_message = "Todos los campos requeridos son necesarios.";
    } elseif (!in_array($tipo_identificacion, ['V', 'P'])) {
        $error_message = "Tipo de identificación no válido.";
    } elseif ($tipo_identificacion == 'V' && !preg_match('/^\d{7,8}$/', $identificacion)) {
        $error_message = "Número de cédula no válido. Debe tener entre 7 y 8 dígitos.";
    } elseif ($tipo_identificacion == 'P' && !preg_match('/^[A-Z]{1}\d{7,8}$/', $identificacion)) {
        $error_message = "Número de pasaporte no válido. Debe tener una letra seguida de 7 u 8 dígitos.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{2,50}$/', $nombres)) {
        $error_message = "Los nombres deben contener solo letras y tener entre 2 y 50 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{2,50}$/', $apellidos)) {
        $error_message = "Los apellidos deben contener solo letras y tener entre 2 y 50 caracteres.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } elseif (!preg_match('/^\d{7,15}$/', $telefono)) {
        $error_message = "El número de teléfono debe tener entre 7 y 15 dígitos.";
    } elseif (strlen($direccion) < 5 || strlen($direccion) > 100) {
        $error_message = "La dirección debe tener entre 5 y 100 caracteres.";
    }

    if (empty($error_message)) {
        $data = [
            'tipo_identificacion' => $tipo_identificacion,
            'identificacion'      => $identificacion,
            'nombres'             => $nombres,
            'apellidos'           => $apellidos,
            'fecha_nacimiento'    => $fecha_nacimiento,
            'edad'                => $edad,
            'genero'              => $genero,
            'nacionalidad'        => $nacionalidad,
            'correo'              => $correo,
            'telefono'            => $telefono,
            'direccion'           => $direccion,
            'estado'              => $estado,
            'municipio'           => $municipio,
            'parroquia'           => $parroquia,
            'comuna'              => $comuna,
            'ciudad'              => $ciudad,
            'sector'              => $sector,
            'posee_discapacidad'  => $posee_discapacidad,
            'tipo_discapacidad'   => $tipo_discapacidad,
            'grado_instruccion'   => $grado_instruccion,
            'estudia'             => $estudia,
            'detalle_estudia'     => $detalle_estudia,
            'tipo_economia'       => $tipo_economia,
            'trabaja'             => $trabaja,
            'entidad_trabajo'     => $entidad_trabajo
        ];
        try {
            $res = $participanteController->editar($_GET['id'], $data);
            if (($res['success'] ?? false)) {
                $success_message = "Participante actualizado exitosamente.";
                $resp = $participanteController->obtener($_GET['id']);
                $participante = $resp['data'];
            } else {
                $error_message = "Error al actualizar el participante. Por favor, intenta de nuevo.";
            }
        } catch (Exception $e) {
            $error_message = "Hubo un error al intentar actualizar el participante: " . $e->getMessage();
        }
    }
}
?>
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/css/custom_styles.css">
<link rel="stylesheet" href="/assets/css/estilo_innovador.css">

<div class="container mt-4 innovative-form">
    <h1 class="mb-4 text-center"><?= $page_title; ?></h1>
    
    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <form method="post">
        <!-- Sección: Datos de Identificación -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-id-card"></i> Datos de Identificación
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Tipo de identificación -->
                    <div class="col-md-3 form-group">
                        <label for="tipo_identificacion">Tipo de Identificación</label>
                        <select name="tipo_identificacion" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="V" <?= ($tipo_identificacion==='V') ? 'selected' : '' ?>>Cédula</option>
                            <option value="P" <?= ($tipo_identificacion==='P') ? 'selected' : '' ?>>Pasaporte</option>
                        </select>
                    </div>
                    <!-- Número de identificación -->
                    <div class="col-md-3 form-group">
                        <label for="identificacion">Número de Identificación</label>
                        <input type="text" name="identificacion" class="form-control" value="<?= htmlspecialchars($identificacion) ?>" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección: Datos Personales -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-user"></i> Datos Personales
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Nombres -->
                    <div class="col-md-4 form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($nombres) ?>" required>
                    </div>
                    <!-- Apellidos -->
                    <div class="col-md-4 form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($apellidos) ?>" required>
                    </div>
                    <!-- Correo -->
                    <div class="col-md-4 form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($correo) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <!-- Fecha de Nacimiento y Edad -->
                    <div class="col-md-3 form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($fecha_nacimiento) ?>" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="edad">Edad</label>
                        <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($edad) ?>">
                    </div>
                    <!-- Género -->
                    <div class="col-md-3 form-group">
                        <label for="genero">Género</label>
                        <select name="genero" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" <?= ($genero==='Masculino') ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($genero==='Femenino') ? 'selected' : '' ?>>Femenino</option>
                        </select>
                    </div>
                    <!-- Nacionalidad -->
                    <div class="col-md-4 form-group">
                        <label for="nacionalidad">Nacionalidad</label>
                        <select name="nacionalidad" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Venezolano" <?= ($nacionalidad==='Venezolano') ? 'selected' : '' ?>>Venezolano</option>
                            <option value="Extranjero" <?= ($nacionalidad==='Extranjero') ? 'selected' : '' ?>>Extranjero</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección: Ubicación y Contacto -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-map-marker-alt"></i> Ubicación y Contacto
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Teléfono y Dirección -->
                    <div class="col-md-3 form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($telefono) ?>" required>
                    </div>
                    <div class="col-md-5 form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($direccion) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <!-- Selects de Ubicación (Estado, Municipio, Ciudad, Parroquia) -->
                    <!-- Se asume que el JavaScript los carga dinámicamente -->
                    <div class="col-md-3 form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <?php if(!empty($estado)): ?>
                                <option value="<?= htmlspecialchars($estado) ?>" selected><?= htmlspecialchars($estado) ?></option>
                            <?php else: ?>
                                <option value="">Seleccione...</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="municipio">Municipio</label>
                        <select id="municipio" name="municipio" class="form-control" required>
                            <?php if(!empty($municipio)): ?>
                                <option value="<?= htmlspecialchars($municipio) ?>" selected><?= htmlspecialchars($municipio) ?></option>
                            <?php else: ?>
                                <option value="">Seleccione...</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="ciudad">Ciudad</label>
                        <select id="ciudad" name="ciudad" class="form-control">
                            <?php if(!empty($ciudad)): ?>
                                <option value="<?= htmlspecialchars($ciudad) ?>" selected><?= htmlspecialchars($ciudad) ?></option>
                            <?php else: ?>
                                <option value="">Seleccione...</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="parroquia">Parroquia</label>
                        <select id="parroquia" name="parroquia" class="form-control" required>
                            <?php if(!empty($parroquia)): ?>
                                <option value="<?= htmlspecialchars($parroquia) ?>" selected><?= htmlspecialchars($parroquia) ?></option>
                            <?php else: ?>
                                <option value="">Seleccione...</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <!-- Comuna y Sector -->
                    <div class="col-md-6 form-group">
                        <label for="comuna">Comuna (opcional)</label>
                        <input type="text" id="comuna" name="comuna" class="form-control" placeholder="Escriba la comuna">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="sector">Sector (opcional)</label>
                        <input type="text" id="sector" name="sector" class="form-control" placeholder="Escriba el sector">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección: Información Adicional -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-info-circle"></i> Información Adicional
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>¿Posee discapacidad?</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="posee_discapacidad" id="discapacidad-si" value="1" class="form-check-input" <?= ($posee_discapacidad==1) ? 'checked' : '' ?>>
                                <label for="discapacidad-si" class="form-check-label">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="posee_discapacidad" id="discapacidad-no" value="0" class="form-check-input" <?= ($posee_discapacidad==0) ? 'checked' : '' ?>>
                                <label for="discapacidad-no" class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="form-group mb-3" id="tipo_discapacidad_container" style="display: <?= (!empty($tipo_discapacidad) || $posee_discapacidad==1) ? 'block' : 'none'; ?>;">
                            <label>Tipo de Discapacidad</label>
                            <select name="tipo_discapacidad" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Visual" <?= ($tipo_discapacidad=="Visual") ? "selected" : ""; ?>>Visual</option>
                                <option value="Auditiva" <?= ($tipo_discapacidad=="Auditiva") ? "selected" : ""; ?>>Auditiva</option>
                                <option value="Motora" <?= ($tipo_discapacidad=="Motora") ? "selected" : ""; ?>>Motora</option>
                                <option value="Intelectual" <?= ($tipo_discapacidad=="Intelectual") ? "selected" : ""; ?>>Intelectual</option>
                                <option value="Otra" <?= ($tipo_discapacidad=="Otra") ? "selected" : ""; ?>>Otra</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Grado de Instrucción</label>
                            <select name="grado_instruccion" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Ninguno" <?= ($grado_instruccion=='Ninguno') ? 'selected' : '' ?>>Ninguno</option>
                                <option value="Primaria" <?= ($grado_instruccion=='Primaria') ? 'selected' : '' ?>>Primaria</option>
                                <option value="Secundaria" <?= ($grado_instruccion=='Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                                <option value="Bachillerato" <?= ($grado_instruccion=='Bachillerato') ? 'selected' : '' ?>>Bachillerato</option>
                                <option value="Técnico" <?= ($grado_instruccion=='Técnico') ? 'selected' : '' ?>>Técnico</option>
                                <option value="Universitario" <?= ($grado_instruccion=='Universitario') ? 'selected' : '' ?>>Universitario</option>
                                <option value="Postgrado" <?= ($grado_instruccion=='Postgrado') ? 'selected' : '' ?>>Postgrado</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>¿Estudia?</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="estudia" id="estudia-si" value="1" class="form-check-input" <?= ($estudia==1) ? 'checked' : '' ?>>
                                <label for="estudia-si" class="form-check-label">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="estudia" id="estudia-no" value="0" class="form-check-input" <?= ($estudia==0) ? 'checked' : '' ?>>
                                <label for="estudia-no" class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="form-group mb-3" id="detalle_estudia_container" style="display: <?= (!empty($detalle_estudia) || $estudia==1) ? 'block' : 'none'; ?>;">
                            <label>¿Qué estudia?</label>
                            <select name="detalle_estudia" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Ingeniería" <?= ($detalle_estudia=="Ingeniería") ? "selected" : ""; ?>>Ingeniería</option>
                                <option value="Medicina" <?= ($detalle_estudia=="Medicina") ? "selected" : ""; ?>>Medicina</option>
                                <option value="Derecho" <?= ($detalle_estudia=="Derecho") ? "selected" : ""; ?>>Derecho</option>
                                <option value="Administración" <?= ($detalle_estudia=="Administración") ? "selected" : ""; ?>>Administración</option>
                                <option value="Ciencias" <?= ($detalle_estudia=="Ciencias") ? "selected" : ""; ?>>Ciencias</option>
                                <option value="Artes" <?= ($detalle_estudia=="Artes") ? "selected" : ""; ?>>Artes</option>
                                <option value="Otro" <?= ($detalle_estudia=="Otro") ? "selected" : ""; ?>>Otro</option>
                            </select>
                        </div>
                    </div>
                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Tipo de Economía</label>
                            <select name="tipo_economia" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Baja" <?= ($tipo_economia=="Baja") ? "selected" : "" ?>>Baja</option>
                                <option value="Media" <?= ($tipo_economia=="Media") ? "selected" : "" ?>>Media</option>
                                <option value="Alta" <?= ($tipo_economia=="Alta") ? "selected" : "" ?>>Alta</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>¿Trabaja?</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="trabaja" id="trabaja-si" value="1" class="form-check-input" <?= ($trabaja==1) ? 'checked' : '' ?>>
                                <label for="trabaja-si" class="form-check-label">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="trabaja" id="trabaja-no" value="0" class="form-check-input" <?= ($trabaja==0) ? 'checked' : '' ?>>
                                <label for="trabaja-no" class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="form-group mb-3" id="entidad_trabajo_container" style="display: <?= ($trabaja==1) ? 'block' : 'none' ?>;">
                            <label>Entidad de Trabajo</label>
                            <select name="entidad_trabajo" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Empresa Privada" <?= ($entidad_trabajo=="Empresa Privada") ? "selected" : "" ?>>Empresa Privada</option>
                                <option value="Empresa Pública" <?= ($entidad_trabajo=="Empresa Pública") ? "selected" : "" ?>>Empresa Pública</option>
                                <option value="Mi negocio" <?= ($entidad_trabajo=="Mi negocio") ? "selected" : "" ?>>Mi negocio</option>
                                <option value="Otra" <?= ($entidad_trabajo=="Otra") ? "selected" : "" ?>>Otra</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-lg btn-primary">Actualizar Participante</button>
            </div>
        </div>
    </form>
</div>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
var ubicaciones = <?php echo json_encode($ubicaciones); ?>;
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar selects de Ubicación
    var estadoSelect = document.getElementById('estado');
    var municipioSelect = document.getElementById('municipio');
    var ciudadSelect = document.getElementById('ciudad');
    var parroquiaSelect = document.getElementById('parroquia');

    function initSelect(selectElem) {
        selectElem.innerHTML = '<option value="">Seleccione...</option>';
    }
    function populateSelect(selectElem, optionsArray) {
        initSelect(selectElem);
        optionsArray.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item;
            option.text = item;
            selectElem.add(option);
        });
    }
    if(Object.keys(ubicaciones).length > 0) {
        populateSelect(estadoSelect, Object.keys(ubicaciones));
    }
    // Setear valores actuales
    estadoSelect.value = "<?= htmlspecialchars($estado) ?>";
    municipioSelect.value = "<?= htmlspecialchars($municipio) ?>";
    ciudadSelect.value = "<?= htmlspecialchars($ciudad) ?>";
    parroquiaSelect.value = "<?= htmlspecialchars($parroquia) ?>";

    estadoSelect.addEventListener('change', function(){
        var selectedState = this.value;
        if(selectedState && ubicaciones[selectedState]){
            populateSelect(municipioSelect, Object.keys(ubicaciones[selectedState]));
        } else {
            initSelect(municipioSelect);
        }
        initSelect(ciudadSelect);
        initSelect(parroquiaSelect);
    });
    municipioSelect.addEventListener('change', function(){
        var selectedState = estadoSelect.value;
        var selectedMunicipio = this.value;
        if(selectedState && selectedMunicipio && ubicaciones[selectedState][selectedMunicipio]){
            populateSelect(ciudadSelect, ubicaciones[selectedState][selectedMunicipio]);
            populateSelect(parroquiaSelect, ubicaciones[selectedState][selectedMunicipio]);
        } else {
            initSelect(ciudadSelect);
            initSelect(parroquiaSelect);
        }
    });

    // Toggle para campos adicionales
    var radioDiscapacidadSi = document.getElementById('discapacidad-si');
    var radioDiscapacidadNo = document.getElementById('discapacidad-no');
    var tipoDiscapacidadContainer = document.getElementById('tipo_discapacidad_container');
    function toggleTipoDiscapacidad() {
        if(radioDiscapacidadSi.checked){
            tipoDiscapacidadContainer.style.display = 'block';
        } else if(radioDiscapacidadNo.checked && !("<?= $tipo_discapacidad ?>" !== "")){
            tipoDiscapacidadContainer.style.display = 'none';
        }
    }
    radioDiscapacidadSi.addEventListener('change', toggleTipoDiscapacidad);
    radioDiscapacidadNo.addEventListener('change', toggleTipoDiscapacidad);

    var radioEstudiaSi = document.getElementById('estudia-si');
    var radioEstudiaNo = document.getElementById('estudia-no');
    var detalleEstudiaContainer = document.getElementById('detalle_estudia_container');
    function toggleDetalleEstudia() {
        if(radioEstudiaSi.checked){
            detalleEstudiaContainer.style.display = 'block';
        } else if(radioEstudiaNo.checked && !("<?= $detalle_estudia ?>" !== "")){
            detalleEstudiaContainer.style.display = 'none';
        }
    }
    radioEstudiaSi.addEventListener('change', toggleDetalleEstudia);
    radioEstudiaNo.addEventListener('change', toggleDetalleEstudia);
});
</script>

<?php require_once "../../includes/footer.php"; ?>
