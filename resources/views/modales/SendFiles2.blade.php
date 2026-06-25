
<div class="modal fade" id="SendFilesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Cabecera del Modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="SendFilesTitle"></h5>
        <button type="button" class="btn-close .closesendfiles2" ></button>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="modal-body">
        <div class=" selectconlabel zdmg-r02">
                <input id="SendFilesInputFile" class="form-control" type="file" hidden name="SendFilesInputFile" multiple>
                <button id="SendFilesOpenFile" class='btn btn-success'>Seleccionar</button>
        </div>
        <div class="logos zdfd-column previewfiles" id="previewfilediv" hidden>
          <iframe id="SendFilesIframe"  hidden></iframe>
          <img id="SendFilesImg"  hidden></img>
          <video id="SendFilesVideo" hidden controls> 
            <source id="SendFilesVideoSource" type="video/mp4"> Tu navegador No Soporta La Etiqueta de Video. 
          </video>
          <h5 id="SendFileText" hidden>El Archivo Que Se Eligio No Se Puede Previzualisar</h5>
          <button type="button"  class='btn btn-warning zdw-100pct' id='cerrarimagen' >Cerrar</button>
        </div>
        <h3 id='fotosevidenciatitle' hidden>Archivos Por Subir</h3>
        <div class="zdmg-r02 zdflex zdscroll-y zdw-100pct zdmw-45vw" id='fotosevidencia'></div>
        <h3 id='archivossubidostittle' hidden>Archivos Subidos</h3>
        <button type="button" class="btn btn-success descargar-imagen" id="descargartodos"
          onclick="downloadalls()" title="Descargar" hidden>Descargar Todos</button>
        <div class="zdmg-r02 zdflex zdscroll-y zdw-100pct zdmw-45vw" id='archivossubidos'></div>
      </div>

      <!-- Pie del Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closesendfiles2" >Cerrar</button>
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
    let arrayfiles=[];
     $(".closesendfiles2").on('click',closethismodal);
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
            url: "{{route('2025.Presupuestos.Get.Files')}}",
            type: "get",
            data:{
              presupuesto:presupuesto,
              tipo:tipo
            },
            success: function(response) {
              recargarfilesup(response.archivos,presupuesto,tipo)
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
       let files = this.files;
        if (files.length > 0) {
            $("#fotosevidencia").removeAttr('hidden');
            $("#fotosevidenciatitle").removeAttr('hidden');
            let filesProcessed = 0;
            for (let i = 0; i < files.length; i++) {
                let file = files[i]; // Captura segura
                arrayfiles.push(file); 
                let index = arrayfiles.length - 1; // Índice de la nueva imagen
                let reader = new FileReader();
                reader.onload = function (e) {
                    let dataURL = e.target.result;
                    addfiletoformdata(dataURL,index)
                };

                reader.readAsDataURL(files[i]); // Leer archivo como Base64
            }
        }
        $(this).val('')
    })
    window.viewfileup = function (archivo,tipo=null) {
      let InputEmpty = ['SendFilesIframe', 'SendFilesImg', 'SendFilesVideoSource'];
      let InputHidden = ['SendFilesIframe', 'SendFilesImg', 'SendFilesVideo', 'SendFileText'];

      InputEmpty.forEach(input => {
          $("#" + input).removeAttr('src');
      });
      InputHidden.forEach(input => {
          $("#" + input).attr('hidden', true);
      });
      if (archivo.startsWith('/storage/') || archivo.startsWith('http')) {
        if (tipo=='imagen') {
            $('#SendFilesImg').attr('src', archivo.replace(/#/g, '%23')).removeAttr('hidden');
        } else if (tipo=='pdf') {
            $('#SendFilesIframe').attr('src', archivo.replace(/#/g, '%23')).removeAttr('hidden');
        } else if (tipo=='video') {
          $('#SendFilesVideoSource').attr('src', archivo.replace(/#/g, '%23'));
          $('#SendFilesVideo')[0].load();
          $('#SendFilesVideo').removeAttr('hidden');
      } else {
          $('#SendFileText').removeAttr('hidden');
      }
      }else if (archivo.startsWith('data:image/')) {
          $('#SendFilesImg').attr('src', archivo).removeAttr('hidden');
      } else if (archivo.startsWith('data:application/pdf')) {
          $('#SendFilesIframe').attr('src', archivo).removeAttr('hidden');
      } else if (archivo.startsWith('data:video/')) {
          $('#SendFilesVideoSource').attr('src', archivo);
          $('#SendFilesVideo')[0].load();
          $('#SendFilesVideo').removeAttr('hidden');
      } else {
          $('#SendFileText').removeAttr('hidden');
      }
      $("#previewfilediv").removeAttr('hidden') 
    }
    $(document).on("click", ".deletepreimagerecepcionvehicular", function (event) {
      let indice = $(this).data('id'); // Asegúrate de tener el índice del elemento en algún atributo de datos
      arrayfiles.splice(indice,1);
      recargarfilespreview();
    });
    function recargarfilespreview(){
      $("#fotosevidencia").empty();
      if (arrayfiles.length > 0) {
          for (let i = 0; i < arrayfiles.length; i++) {
              let file = arrayfiles[i];
              let reader = new FileReader();
              reader.onload = function (e) {
                  let dataURL = e.target.result;
                  addfiletoformdata(dataURL,i)
              };

              reader.readAsDataURL(file);
          }
      }else{
          $("#fotosevidencia").attr('hidden',true);
          $("#fotosevidenciatitle").attr('hidden',true);
      } 
    }
  window.downloadalls = () => {
    const tipo = $('#descargartodos').data('tipo');
    const presupuesto = $('#descargartodos').data('presupuesto');
    $.ajax({
      type: 'get',
      url: '{{ route('2025.Presupuestos.Descargar.Files') }}',
      data: {
        tipo,
        presupuesto
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(blob, status, xhr) {
        const filename = xhr.getResponseHeader('Content-Disposition')?.split('filename=')[1] || 'archivos.zip';
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
      },
      error: function(xhr) {
        console.error('Error al descargar archivos:', xhr.responseText);
      }
    });
  };
   window.downloadone = (id) => {
      $.ajax({
        type: 'get',
        url: '{{ route('2025.Presupuestos.Descargar.File') }}',
        data: {
          id
        },
        xhrFields: {
          responseType: 'blob'
        },
        success: function(blob, status, xhr) {
          const rawHeader = xhr.getResponseHeader('Content-Disposition');
          const filename = rawHeader?.split('filename=')[1]?.replace(/^"|"$/g, '') || 'archivo';
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          a.remove();
          window.URL.revokeObjectURL(url);
        },
        error: function(xhr) {
          console.error('Error al descargar archivo:', xhr.responseText);
        }
      });
    };
    function addfiletoformdataup(filePath, id,presupuestoid,tipo){
      const fileURL = filePath;
      
      // Obtener extensión en minúsculas
      const ext = filePath.split('.').pop().toLowerCase();

      // Crear contenedor base
      let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container" data-index="file-${id}">`;

      // Evaluar tipo de archivo según extensión
      if (['mp4', 'webm', 'mov'].includes(ext)) {
        tipoArchivo += `
          <video class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${fileURL}','video')"
            title='Video-${id}' controls>
            <source src="${fileURL}" type="video/${ext}">
            Tu navegador no soporta la etiqueta de video.
          </video>`;
      } else if (ext === 'pdf') {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${fileURL}','pdf')"
            title='PDF-${id}'>
            <img src="/storage/iconos/pdf1.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (ext === 'xml') {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='XML-${id}'>
            <img src="/storage/iconos/XML.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (['xls', 'xlsx'].includes(ext)) {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='Excel-${id}'>
            <img src="/storage/iconos/XLSX.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (['doc', 'docx'].includes(ext)) {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='DOC-${id}'>
            <img src="/storage/iconos/DOC.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'].includes(ext)) {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${fileURL}','imagen')"
            title='Foto-${id}'>
            <img src="${fileURL}" class="zdw-100pct zdh-100pct">
          </button>`;
      } else {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('nosoportado')" title='Archivo-${id}'>
            <img src="/storage/iconos/desconocido.png" class="zdw-100pct zdh-100pct">
          </button>`;
      }

      // Botón eliminar actualizado
      tipoArchivo += `
        <button type="button" class="btn btn-danger eliminar-imagen"
          onclick="deleteimagenup(${id},${presupuestoid},${tipo})" title="Eliminar">Eliminar</button>
        <button type="button" class="btn btn-success descargar-imagen"
          onclick="downloadone(${id},${presupuestoid},${tipo})" title="Descargar">Descargar</button>
      </div>`;

      // Insertar en el contenedor
      $("#archivossubidos").append(tipoArchivo);
    }
    function addfiletoformdata(dataURL,index){
      let mimeMatch = dataURL.match(/^data:(.*?);base64,/);
      let mimeType = mimeMatch ? mimeMatch[1] : '';

      // Estructura base
      let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container" data-index="${index}">`;

      // Evaluar según el tipo contenido en el dataURL
      if (mimeType.startsWith('video/')) {
        tipoArchivo += `
          <video class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${dataURL}')"
            title='Video-${index}' controls>
            <source src="${dataURL}" type="${mimeType}">
            Tu navegador no soporta la etiqueta de video.
          </video>`;
      } else if (mimeType === 'application/pdf') {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${dataURL}')"
            title='PDF-${index}'>
            <img src="/storage/iconos/pdf1.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (mimeType === 'application/xml' || dataURL.includes('.xml')) {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='XML-${index}'>
            <img src="/storage/iconos/XML.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (
        mimeType === 'application/vnd.ms-excel' ||
        mimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
        dataURL.includes('.xls')
      ) {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='Excel-${index}'>
            <img src="/storage/iconos/XLSX.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (
        mimeType === 'application/msword' ||
        mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
        dataURL.includes('.doc')
      ) {
        tipoArchivo += `
          <button type="button" onclick="viewfileup('nosoportado')" title='DOC-${index}'>
            <img src="/storage/iconos/DOC.png" class="zdw-100pct zdh-100pct">
          </button>`;
      } else if (mimeType.startsWith('image/')) {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('${dataURL}')"
            title='Foto-${index}'>
            <img src="${dataURL}" class="zdw-100pct zdh-100pct">
          </button>`;
      } else {
        tipoArchivo += `
          <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
            onclick="viewfileup('nosoportado')" title='Archivo-${index}'>
            <img src="/storage/iconos/desconocido.png" class="zdw-100pct zdh-100pct">
          </button>`;
      }

      tipoArchivo += `
        <button type="button" class="deletepreimagerecepcionvehicular eliminar-imagen"
          data-id="${index}" title="Eliminar">Eliminar</button>
      </div>`;
      $("#fotosevidencia").append(tipoArchivo);
    }
    window.executesubirarchivo=(presupuesto,tipo)=>{
          arrayfiles=[];
            $('#fotosevidencia').empty();
            $('#fotosevidencia').attr('hidden',true);
            $('#fotosevidenciatitle').attr('hidden',true);
            $('#SendFilesSend').data('tipo',tipo);
            $('#SendFilesSend').data('presupuesto',presupuesto);
            $('#descargartodos').data('tipo',tipo);
            $('#descargartodos').data('presupuesto',presupuesto);
            $('#SendFilesInputFile').val('').trigger('change');
            $("#previewfilediv").attr('hidden',true) 
            historialarchivos(presupuesto,tipo)
            ModalFather=null;
            ModalFather = $('.modal.show');
            if(ModalFather){
              ModalFather.modal('hide');
            }
            ThisModal.modal('show');
    }
    function recargarfilesup(files,presupuestoid,tipo){
      $("#archivossubidos").empty();
      if (files.length > 0) {
        $("#archivossubidos").removeAttr('hidden');
        $("#descargartodos").removeAttr('hidden');
        $("#archivossubidostittle").removeAttr('hidden');
          for (let i = 0; i < files.length; i++) {
              let url = files[i]['ruta_completa'];
              let id = files[i]['id'];
              addfiletoformdataup(url,id,presupuestoid,tipo);
          }
      }else{
          $("#archivossubidos").attr('hidden',true);
          $("#descargartodos").attr('hidden',true);
          $("#archivossubidostittle").attr('hidden',true);
      } 
    }
    $('#cerrarimagen').on('click',function(){
        $("#previewfilediv").attr('hidden',true) 
    })
    $('#SendFilesSend').on('click',function(){
      let tipo=$(this).data('tipo')
      let presupuesto=$(this).data('presupuesto')
      
      if (arrayfiles.length > 0) {
        const formData = new FormData();
          for (let i = 0; i < arrayfiles.length; i++) {
              formData.append('files[]', arrayfiles[i]);
          }
          formData.append('presupuesto', presupuesto);  // Agregar id al FormData
          formData.append('tipo', tipo);
          $.ajax({
              url: "{{route('2025.Presupuestos.Send.Files')}}",
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
                    html: `${response.message ?? "Archivos Subidos Correctamente"}`,
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
    window.deleteimagenup = (archivo,presupuesto,tipo) => { // Tu código aquí };
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Una vez eliminado, No lo podras revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('2025.Presupuestos.delete.file')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            archivo:archivo,
                            presupuesto:presupuesto,
                        },
                        success: function(response) {
                            const mensaje=response.message
                            Swal.fire({ html: `${mensaje}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            historialarchivos(presupuesto,tipo)
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            console.log(xhr)
                            Swal.fire({
                                title: 'Error',
                                html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.error}`:``}`,
                                icon: 'error'
                                });

                        }
                    });
                }
            });
        }
  });
</script>
@endpush