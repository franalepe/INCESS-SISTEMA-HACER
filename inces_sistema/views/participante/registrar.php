<?php
// registrar_nuevo.php
// Página para registrar un nuevo participante con nuevo diseño

$page_title = "Registrar Participante - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/participanteController.php';
require_once '../../config/ubicaciones.php';
?>
<?php
$participanteController = new ParticipanteController();

$error_message = '';
$success_message = '';

// Inicializar variables para mantener los valores en el formulario
$tipo_identificacion = '';
$identificacion      = '';
$nombres             = '';
$apellidos           = '';
$correo              = '';
$telefono            = '';
$direccion           = '';
// Las variables de ubicación se inicializan vacías. Se usarán como selects.
$estado              = '';
$municipio           = '';
$parroquia           = '';
$comuna              = '';
$ciudad              = '';
$sector              = '';
$fecha_nacimiento    = '';
$edad                = '';
$genero              = '';
$nacionalidad        = '';
$posee_discapacidad  = 0;
$tipo_discapacidad   = '';
$grado_instruccion   = '';
$estudia             = 0;
$detalle_estudia     = ''; // Se agregó la inicialización de $detalle_estudia
$tipo_economia       = '';
$trabaja             = 0;
$entidad_trabajo     = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiar campos del formulario
    $tipo_identificacion = trim($_POST['tipo_identificacion'] ?? '');
    $identificacion      = trim($_POST['identificacion'] ?? '');
    $nombres             = trim($_POST['nombres'] ?? '');
    $apellidos           = trim($_POST['apellidos'] ?? '');
    $correo              = trim($_POST['correo'] ?? '');
    $telefono            = trim($_POST['telefono'] ?? '');
    $direccion           = trim($_POST['direccion'] ?? '');
    $estado              = trim($_POST['estado'] ?? '');
    $municipio           = trim($_POST['municipio'] ?? '');
    $parroquia           = trim($_POST['parroquia'] ?? '');
    $comuna              = trim($_POST['comuna'] ?? '');
    $ciudad              = trim($_POST['ciudad'] ?? '');
    $sector              = trim($_POST['sector'] ?? '');
    $fecha_nacimiento    = trim($_POST['fecha_nacimiento'] ?? '');
    $edad                = trim($_POST['edad'] ?? '');
    $genero              = trim($_POST['genero'] ?? '');
    $nacionalidad        = trim($_POST['nacionalidad'] ?? '');
    $posee_discapacidad  = isset($_POST['posee_discapacidad']) ? 1 : 0;
    $tipo_discapacidad   = trim($_POST['tipo_discapacidad'] ?? '');
    $grado_instruccion   = trim($_POST['grado_instruccion'] ?? '');
    $estudia             = isset($_POST['estudia']) ? 1 : 0;
    $detalle_estudia     = trim($_POST['detalle_estudia'] ?? ''); // Se agregó la captura de $detalle_estudia
    $tipo_economia       = trim($_POST['tipo_economia'] ?? '');
    $trabaja             = isset($_POST['trabaja']) ? 1 : 0;
    $entidad_trabajo     = trim($_POST['entidad_trabajo'] ?? '');
    
    // Validación global de los campos requeridos (se pueden ampliar)
    if (empty($tipo_identificacion) || empty($identificacion) || empty($nombres) || empty($apellidos) || empty($correo) || empty($telefono) || empty($direccion) || empty($estado) || empty($municipio) || empty($parroquia) || 
        ($posee_discapacidad == 1 && empty($tipo_discapacidad)) || 
        ($estudia == 1 && empty($detalle_estudia)) || 
        ($trabaja == 1 && empty($entidad_trabajo))) {

        $error_message = "Todos los campos requeridos son necesarios.";
    } else {
        // Validaciones adicionales...
        if (!in_array($tipo_identificacion, ['V', 'P'])) {
            $error_message = "Tipo de identificación no válido.";
        }
        if ($tipo_identificacion == 'V' && !preg_match('/^\d{7,8}$/', $identificacion)) {
            $error_message = "Número de cédula no válido. Debe tener entre 7 y 8 dígitos.";
        } elseif ($tipo_identificacion == 'P' && !preg_match('/^[A-Z]{1}\d{7,8}$/', $identificacion)) {
            $error_message = "Número de pasaporte no válido. Debe tener una letra seguida de 7 u 8 dígitos.";
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{2,50}$/', $nombres)) {
            $error_message = "Los nombres deben contener solo letras y tener entre 2 y 50 caracteres.";
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{2,50}$/', $apellidos)) {
            $error_message = "Los apellidos deben contener solo letras y tener entre 2 y 50 caracteres.";
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error_message = "El correo electrónico no es válido.";
        } else {
            $domain = substr(strrchr($correo, "@"), 1);
            $allowed_domains = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
            if (!in_array($domain, $allowed_domains)) {
                $error_message = "Solo se permiten correos de Gmail, Hotmail, Yahoo o Outlook.";
            }
        }
        if (!preg_match('/^\d{7,15}$/', $telefono)) {
            $error_message = "El número de teléfono debe tener entre 7 y 15 dígitos.";
        }
        if (strlen($direccion) < 5 || strlen($direccion) > 100) {
            $error_message = "La dirección debe tener entre 5 y 100 caracteres.";
        }
    }
    
    if (empty($error_message)) {
        try {
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
                'tipo_economia'       => $tipo_economia,
                'trabaja'             => $trabaja,
                'entidad_trabajo'     => $entidad_trabajo
            ];
            $resultado = $participanteController->registrar($data);
            if ($resultado['success']) {
                $success_message = $resultado['mensaje'];
                // Limpiar variables para reiniciar el formulario
                $tipo_identificacion = $identificacion = $nombres = $apellidos = $correo = $telefono = $direccion = "";
                $estado = $municipio = $parroquia = $comuna = $ciudad = $sector = "";
                $fecha_nacimiento = $edad = $genero = $nacionalidad = "";
                $tipo_discapacidad = $grado_instruccion = $tipo_economia = $entidad_trabajo = "";
                $posee_discapacidad = $estudia = $trabaja = 0;
                $detalle_estudia = ""; // Se agregó el reinicio de $detalle_estudia
            } else {
                $error_message = $resultado['mensaje'];
            }
        } catch (Exception $e) {
            $error_message = "Error al registrar el participante.";
        }
    }
}
?>

<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/css/custom_styles.css">
<!-- Incluir el nuevo stylesheet innovador -->
<link rel="stylesheet" href="/assets/css/estilo_innovador.css">

<div class="container mt-4 innovative-form">
    <h1 class="mb-4 text-center"><?= $page_title; ?></h1>

    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <!-- Sección: Datos de Identificación -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-id-card"></i> Datos de Identificación
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="tipo_identificacion">Tipo de Identificación</label>
                        <select name="tipo_identificacion" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="V" <?= ($tipo_identificacion === 'V') ? 'selected' : '' ?>>Cédula</option>
                            <option value="P" <?= ($tipo_identificacion === 'P') ? 'selected' : '' ?>>Pasaporte</option>
                        </select>
                    </div>
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
                    <div class="col-md-4 form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($nombres) ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($apellidos) ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($correo) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Ubicación y Contacto -->
        <div class="card shadow mb-4 innovative-card">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-map-marker-alt"></i> Datos de Contacto y Ubicación
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($fecha_nacimiento) ?>" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="edad">Edad</label>
                        <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($edad) ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="genero">Género</label>
                        <select name="genero" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" <?= ($genero == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($genero == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="nacionalidad">Nacionalidad</label>
                        <select name="nacionalidad" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Venezolano" <?= ($nacionalidad == 'Venezolano') ? 'selected' : '' ?>>Venezolano</option>
                            <option value="Extranjero" <?= ($nacionalidad == 'Extranjero') ? 'selected' : '' ?>>Extranjero</option>
                        </select>
                    </div>
                </div>
                <div class="row">
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
                    <!-- Ubicación: Estado, Municipio, Ciudad y Parroquia se cargarán dinámicamente -->
                    <div class="col-md-3 form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="municipio">Municipio</label>
                        <select id="municipio" name="municipio" class="form-control" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="ciudad">Ciudad</label>
                        <select id="ciudad" name="ciudad" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="parroquia">Parroquia</label>
                        <select id="parroquia" name="parroquia" class="form-control" required>
                            <option value="">Seleccione...</option>
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

        <!-- Botón de envío -->
        <div class="row">
            <div class="col-md-12 mb-3 text-center">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Participante</button>
            </div>
        </div>
    </form>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Script para cargar dinámicamente las Ubicaciones -->
<script>
var ubicaciones = <?php echo json_encode($ubicaciones); ?>;
console.log("Ubicaciones cargadas:", ubicaciones); // Verifica en la consola

document.addEventListener('DOMContentLoaded', function() {
  var estadoSelect    = document.getElementById('estado');
  var municipioSelect = document.getElementById('municipio');
  var ciudadSelect    = document.getElementById('ciudad');
  var parroquiaSelect = document.getElementById('parroquia');
  // Nota: "comuna" y "sector" serán ingresados manualmente.

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
  } else {
      console.error("No se han cargado estados. Revisa el archivo de configuraciones.");
  }

  estadoSelect.addEventListener('change', function() {
    var selectedEstado = this.value;
    if (selectedEstado !== '' && ubicaciones[selectedEstado]) {
      populateSelect(municipioSelect, Object.keys(ubicaciones[selectedEstado]));
    } else {
      initSelect(municipioSelect);
    }
    initSelect(ciudadSelect);
    initSelect(parroquiaSelect);
  });

  municipioSelect.addEventListener('change', function() {
    var selectedEstado = estadoSelect.value;
    var selectedMunicipio = this.value;
    if (selectedEstado !== '' && selectedMunicipio !== '' && ubicaciones[selectedEstado][selectedMunicipio]) {
      populateSelect(ciudadSelect, ubicaciones[selectedEstado][selectedMunicipio]);
      populateSelect(parroquiaSelect, ubicaciones[selectedEstado][selectedMunicipio]);
    } else {
      initSelect(ciudadSelect);
      initSelect(parroquiaSelect);
    }
  });
});

// Actualización del JavaScript para mostrar/ocultar según selección
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para Tipo de discapacidad
    var radioDiscapacidadSi = document.getElementById('discapacidad-si');
    var radioDiscapacidadNo = document.getElementById('discapacidad-no');
    var tipoDiscapacidadContainer = document.getElementById('tipo_discapacidad_container');
    function toggleTipoDiscapacidad() {
        tipoDiscapacidadContainer.style.display = radioDiscapacidadSi.checked ? 'block' : 'none';
    }
    radioDiscapacidadSi.addEventListener('change', toggleTipoDiscapacidad);
    radioDiscapacidadNo.addEventListener('change', toggleTipoDiscapacidad);

    // Toggle para ¿Qué estudia?
    var radioEstudiaSi = document.getElementById('estudia-si');
    var radioEstudiaNo = document.getElementById('estudia-no');
    var detalleEstudiaContainer = document.getElementById('detalle_estudia_container');
    function toggleDetalleEstudia() {
        detalleEstudiaContainer.style.display = radioEstudiaSi.checked ? 'block' : 'none';
    }
    radioEstudiaSi.addEventListener('change', toggleDetalleEstudia);
    radioEstudiaNo.addEventListener('change', toggleDetalleEstudia);

    // Toggle para Entidad de Trabajo
    var radioTrabajaSi = document.getElementById('trabaja-si');
    var radioTrabajaNo = document.getElementById('trabaja-no');
    var entidadTrabajoContainer = document.getElementById('entidad_trabajo_container');
    function toggleEntidadTrabajo() {
        entidadTrabajoContainer.style.display = radioTrabajaSi.checked ? 'block' : 'none';
    }
    radioTrabajaSi.addEventListener('change', toggleEntidadTrabajo);
    radioTrabajaNo.addEventListener('change', toggleEntidadTrabajo);
});
</script>

<?php require_once "../../includes/footer.php"; ?>
