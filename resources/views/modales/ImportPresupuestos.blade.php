
<div class="modal fade" id="ImportPresupuestosModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Cabecera del Modal -->
      <div class="modal-header">
        <h5 class="modal-title">Importar Presupuestos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="modal-body">
        <div class=" selectconlabel zdmg-r02">
                <input id="InputFileImport" class="form-control" type="file" hidden name="InputFileImport"  accept=".xls,.xlsx">
                <button id="SelectFileImport" class='btn btn-success'>Seleccionar</button>
        </div>
        <h2 id="FileNameImport" class="" hidden></h2>
      </div>

      <!-- Pie del Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="ImportSave">Importar</button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
  $(function(){
    let ModalFather=null;
    let ThisModal=$('#ImportPresupuestosModal');
    let DisparadorOtroModal=null;
    function closethismodal(){
      ThisModal.modal('hide');

      if(DisparadorOtroModal){
          DisparadorOtroModal=null;
      }
      if(ModalFather){
          ModalFather.modal('show');
          ModalFather=null;
      }       
    }
    window.ImportPresupuestos=()=>{
       $('#FileNameImport').text('').attr('hidden',true);
       $('#InputFileImport').val('')
      ThisModal.modal('show');
    }
    $('#SelectFileImport').on('click',function(){
      $('#InputFileImport').trigger('click');
    })
    $('#InputFileImport').change(function(){
        const file = this.files[0];
        if (file) {
          let fileType = file.type;
          const reader = new FileReader();
          reader.readAsDataURL(file); // Lee el archivo como una URL de datos
          reader.onload = function(e) {
           $('#FileNameImport').text(file.name).removeAttr('hidden');
          }
        }
    })
    $('#ImportSave').on('click',function(){
      const fileInput = $('#InputFileImport');
      if (fileInput[0].files.length > 0) {
        const formData = new FormData();
          formData.append('file', fileInput[0].files[0]);
          $.ajax({
              url: "{{route('2025.Presupuestos.Import')}}",
              type: "post",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:formData,
              processData: false,
              contentType: false,
              success: function(response) {
                console.log(response)
                if(response.filasnoimportadas.length > 0){
                  message='Las siguientes Filas No se Pudieron Importar:'+response.filasnoimportadas.join(', ')
                        Swal.fire({
                      title: 'Hay Problemas',
                      html: message,
                      icon: 'warning',
                  });
                }else{
                  Swal.fire({
                      title: 'Exito',
                      html: `${response.message ?? "Presupuestos Agregados Correctamente"}`,
                      icon: 'success',
                      timer:1500,
                  });
                }
                try {
                  executeSearchdata();
                } catch (error) {
                  console.log(error);
                }
                closethismodal()
              },
              error: function(xhr) {
                if (xhr.status === 422) {
                  let errors = xhr.responseJSON.errors;
                  let errorMessages = Object.values(errors).map((msgs) => {
                      if (msgs && msgs !== "Este campo es obligatorio." && msgs !== "La opción no es válida") {
                          return msgs.join("<br>");
                      }
                  }).filter(Boolean).join("<br>");
                  Swal.fire({
                      icon: "warning",
                      title: "Información",
                      html: errorMessages,
                      timer: 10000
                  });
              } else {
                  Swal.fire({
                      title: 'Error: ' + (xhr.status ?? "Desconocido"),
                      html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                      icon: 'error'
                  });
              }
            }
          });
      } else {
        Swal.fire({
            title: 'Atencion',
            html: `No Se Ha Seleccionado Ningun Archivo`,
            icon: 'Warning'
        });
      }
    })
  });
</script>
@endpush