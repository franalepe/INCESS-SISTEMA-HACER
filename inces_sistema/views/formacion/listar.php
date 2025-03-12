<?php

require_once 'C:/xampp/htdocs/sistema_definitivo/inces_sistema/includes/verificar_sesion.php';


$page_title = "Listado de Formaciones";
require_once "../../includes/header.php";
require_once '../../controllers/formacionController.php';
require_once '../../controllers/participanteController.php';

$formacionController = new FormacionController();
$resultado = $formacionController->listar();

$participanteController = new ParticipanteController();
$listaParticipantes = $participanteController->listar();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>

<div class="container mt-5">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Búsqueda y Selección de Formaciones</h2>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <div class="table-responsive">
                <table id="tablaFormaciones" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Formación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado['success'] && !empty($resultado['data'])): ?>
                            <?php foreach ($resultado['data'] as $formacion): ?>
                                <tr data-id="<?= htmlspecialchars($formacion['id_formacion']) ?>">
                                    <td><?= htmlspecialchars($formacion['id_formacion']) ?></td>
                                    <td><?= htmlspecialchars($formacion['nombre_formacion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center"><?= htmlspecialchars($resultado['mensaje'] ?? "Ningún dato disponible en esta tabla") ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar participante (versión Bootstrap) -->
<div class="modal fade" id="modalParticipantes" tabindex="-1" role="dialog" aria-labelledby="modalParticipantesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalParticipantesLabel">Asignar Participante a la Formación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Formación: <strong id="nombreFormacion"></strong></p>
        <form id="formAsignacion" method="post" action="/sistema_definitivo/inces_sistema/controllers/asignarParticipante.php">
          <input type="hidden" id="id_formacion_input" name="id_formacion">
          <div class="form-group">
            <label for="id_participante">Seleccione un Participante:</label>
          </div>
          <table id="tablaParticipantes" class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Identificación</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($listaParticipantes['success'] && !empty($listaParticipantes['data'])): ?>
                <?php foreach ($listaParticipantes['data'] as $participante): ?>
                  <tr data-id="<?= htmlspecialchars($participante['id_participante']) ?>">
                    <td><?= htmlspecialchars($participante['id_participante']) ?></td>
                    <td><?= htmlspecialchars($participante['nombres']) ?></td>
                    <td><?= htmlspecialchars($participante['apellidos']) ?></td>
                    <td><?= htmlspecialchars($participante['identificacion']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center"><?= htmlspecialchars($listaParticipantes['mensaje'] ?? "No hay participantes registrados.") ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div class="form-group">
            <p>Participante seleccionado: <span id="nombreParticipante"></span></p>
            <input type="hidden" id="id_participante_input" name="id_participante">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="formAsignacion" class="btn btn-primary">Asignar Participante</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelarModal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    var tableForm = $('#tablaFormaciones').DataTable({
        searching: true,
        ordering: true,
        paging: true,
        info: true,
        responsive: true,
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        }
    });

    var tablePart = $('#tablaParticipantes').DataTable({
        searching: true,
        ordering: true,
        paging: true,
        info: true,
        responsive: true,
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron participantes",
            sEmptyTable: "Ningún participante registrado",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        }
    });
    
    // Al hacer clic en una formación se mostrará el modal de Bootstrap
    $('#tablaFormaciones tbody').on('click', 'tr', function() {
        var idFormacion = $(this).data('id');
        var data = tableForm.row(this).data();
        $('#id_formacion_input').val(idFormacion);
        $('#nombreFormacion').text(data[1]);
        $('#modalParticipantes').modal('show');
    });
    
    // Seleccionar participante
    $('#tablaParticipantes tbody').on('click', 'tr', function() {
        var idPart = $(this).data('id');
        var data = tablePart.row(this).data();
        $('#id_participante_input').val(idPart);
        $('#nombreParticipante').text(data[1] + " " + data[2]);
        $(this).addClass('selected').siblings().removeClass('selected');
    });

    // Cancelar la asignación (se puede prescindir, ya que el botón ya cierra el modal)
    $('#cancelarModal').on('click', function() {
       // Reiniciar selección en el modal si es necesario
       $('#id_participante_input').val('');
       $('#nombreParticipante').text('');
    });

    // Validar formulario antes de enviarlo
    $("#formAsignacion").on("submit", function(e){
        if($("#id_formacion_input").val() === "" || $("#id_participante_input").val() === ""){
            e.preventDefault();
            alert("Por favor, seleccione una formación y un participante.");
        }
    });
});
</script>

<?php require_once "../../includes/footer.php"; ?>
