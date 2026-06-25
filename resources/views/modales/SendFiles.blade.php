
<div class="modal fade" id="SendFilesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Cabecera del Modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="SendFilesTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="modal-body">
        <div class=" selectconlabel zdmg-r02">
                <input id="SendFilesInputFile" class="form-control" type="file" hidden name="SendFilesInputFile">
                <button id="SendFilesOpenFile" class='btn btn-success'>Seleccionar</button>
        </div>
        <div class="logos">
          <iframe id="SendFilesIframe" class="mimagen" hidden></iframe>
          <img id="SendFilesImg" class="mimagen" hidden></img>
          <video id="SendFilesVideo" class="mimagen" hidden controls> 
            <source id="SendFilesVideoSource" type="video/mp4"> Tu navegador No Soporta La Etiqueta de Video. 
          </video>
          <h5 id="SendFileText" hidden>El Archivo Que Se Eligio No Se Puede Previzualisar</h5>
        </div>
      </div>

      <!-- Pie del Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="SendFilesHistory"><i class="fa fa-picture-o"></i></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="SendFilesSend">Guardar</button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
  $(function(){
    let ModalFather=null;
    let ThisModal=$('#SendFilesModal');
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
    window.historialarchivos=(presupuesto,tipo)=>{
        $.ajax({
            url: "{{route('2025.Presupuestos.Get.File')}}",
            type: "get",
            data:{
              presupuesto:presupuesto,
              tipo:tipo
            },
            success: function(response) {
                window.open(response.url.replace(/#/g, '%23'),'_blank');
            },
            error: function(xhr) {
              console.log(xhr);
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
        })
    }
    $('#SendFilesOpenFile').on('click',function(){
      $('#SendFilesInputFile').trigger('click');
    })
    $('#SendFilesInputFile').change(function(){
        let InputEmpty=['SendFilesIframe','SendFilesImg','SendFilesVideoSource'] 
        let InputHidden=['SendFilesIframe','SendFilesImg','SendFilesVideo','SendFileText']
        
        InputEmpty.forEach(input =>{
            $("#"+input ).removeAttr('src')
        });
        InputHidden.forEach(input =>{
            $("#"+input ).attr('hidden',true)
        });
        const file = this.files[0];
          
        if (file) {
          let fileType = file.type;
          const reader = new FileReader();
          reader.readAsDataURL(file); // Lee el archivo como una URL de datos
          reader.onload = function(e) {
          if (fileType.startsWith('image/')) { 
            $('#SendFilesImg').attr('src',e.target.result)
            $('#SendFilesImg').removeAttr('hidden');
          }
          else if (fileType === 'application/pdf') {
            $('#SendFilesIframe').attr('src',e.target.result)
            $('#SendFilesIframe').removeAttr('hidden');} 
          else if (fileType.startsWith('video/')){ 
            console.log(e.target.result)
            $('#SendFilesVideoSource').attr('src',e.target.result)
            $('#SendFilesVideo')[0].load();
            $('#SendFilesVideo').removeAttr('hidden'); } 
            else { 
            $('#SendFileText').removeAttr('hidden');
          }
          }
        }
    })
    $('#SendFilesSend').on('click',function(){
      let tipo=$(this).data('tipo')
      let presupuesto=$(this).data('presupuesto')
      const fileInput = $('#SendFilesInputFile');
      if (fileInput[0].files.length > 0) {
        const formData = new FormData();
          formData.append('file', fileInput[0].files[0]);
          formData.append('presupuesto', presupuesto);  // Agregar id al FormData
          formData.append('tipo', tipo);
          $.ajax({
              url: "{{route('2025.Presupuestos.Send.File')}}",
              type: "post",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:formData,
              processData: false,
              contentType: false,
              success: function(response) {
                console.log(response)
                Swal.fire({
                    title: 'Exito',
                    html: `${response.message ?? "Archivo Subido Correctamente"}`,
                    icon: 'success',
                    timer:1500,
                });
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
    $('#SendFilesHistory').on('click',function(){
      let tipo=$('#SendFilesHistory').data('tipo')
      let presupuesto=$('#SendFilesHistory').data('presupuesto')
      console.log(tipo);
      console.log(presupuesto);
      historialarchivos(presupuesto,tipo)
    })
  });
</script>
@endpush