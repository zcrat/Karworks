<div class="modal fade" id="DetGenEntSalModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Entradas Y Salidas</h5>
                <button type="button" class="btn-close"  data-bs-dismiss="modal"></button>
            </div>
            <form id="DetGenEntSalForm" enctype="multipart/form-data">
                @csrf
                <input name="DetGenId" id="DetGenId" type="hidden" >
                <div class="modal-body">
                    <div class=" selectconlabel zdmg-r02">
                       <label for="DetGenFecSal">Fecha Salida</label>
                       <input name="DetGenFecSal" id="DetGenFecSal" type="datetime-local" class="form-control">
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                       <label for="DetGenGasSal">Gas Salida</label>
                       <select id="DetGenGasSal" name="DetGenGasSal" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    <option value="0">LLENO</option>
                                    <option value="1">3/4</option>
                                    <option value="2">2/4</option>
                                    <option value="3">1/4</option>
                                    <option value="4">vacio</option>
                        </select>
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                       <label for="DetGenKilSal">Kilometraje Salida</label>
                       
                       <input name="DetGenKilSal" id="DetGenKilSal" min='0' step="1" type="number" class="form-control">
                    </div>

                    <div class="zdmg-r02 zdflex zdscroll-y zdmw-r30" id='ArchivosSalida' hidden></div>
                    <div class="zdmg-r02 zdw-r30 zdmw-r30" id='ArchivoSalidaGrande' hidden>

                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="newmarca">Nuevo Archivo<strong>*</strong></label>
                        <input id="nuevo_archivo" class="form-control" type="file"  name="nuevo_archivo">
                    </div>
                    <div class="logos zdmg-r02 zdw-r30 zdmw-r30">
                        <iframe id="pdf_preview" src="#"  class="mimagen" hidden></iframe>
                        <img id="img_preview" src="#"  class="mimagen" hidden></img>
                        <video id="video_preview" class="mimagen" hidden controls> 
                            <source id="video_src_preview" src="" type="video/mp4"> Tu navegador no soporta la etiqueta de video. 
                        </video>
                        <h5 id="text_preview" hidden>El Archivo Que Se Eligio No Se uede Mostrar</h5>
                    </div>
                    </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" id="DetGenSalSave" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('modales.nuevousuariocaja')
@push('scripts')
<script>
$(function(){

    let ThisModal = $('#DetGenEntSalModal');
    let ThisForm = $('#DetGenEntSalForm');
    let ModalFather = null;

    window.OpenDetGenEntSalModal = async function(id) {
        if(id){
            const isSuccess = await GetDatDetGen(id);
            if (isSuccess) {
                ModalFather = $('.modal.show');
                if(ModalFather){
                    ModalFather.modal('hide');
                }
                 $('#nuevo_archivo').val('').trigger('change');
                ThisModal.modal('show');
            }
        }else{
            Swal.fire({ 
                title: 'Error', 
                html: `Detalles del error:<br> Datos Corrompidos`, 
                icon: 'error',
                timer: 1000, 
            });
        }
    }
    async function GetDatDetGen(id) {
        try {
            const response = await $.ajax({
                url: '{{ route('DetallesGenerales.Get.Data.EntradaSalida') }}',
                type: "get",
                data: { id: id },
            });
            insertar_valores(response.element)
            return true;
        } catch (xhr) {
            console.log(xhr);
            Swal.fire({ 
                title: 'Error: ' + (xhr.status ?? "Desconocido"), 
                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte"}`, 
                icon: 'error' 
            });
            return false;
        }
    }
    function insertar_valores(data){
        $('#DetGenId').val(data.id);
        $('#DetGenFecSal').val(data.fecha);
        $('#DetGenGasSal').val(data.gasolina);
        $('#DetGenKilSal').val(data.kilometraje);
        insertar_images(data.archivos);
    }
    function insertar_images(archivos){
        $("#ArchivosSalida").empty();
        $("#ArchivoSalidaGrande").empty();
        archivos.forEach(function(archivo) {
            $("#ArchivosSalida").removeAttr('hidden')
            let ext=archivo.Nombre.split('.')[1].toLowerCase();
            console.log(ext)
            switch (ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button" class="boton-imagen zdmg-r02 zdw-r4 zdmnw-r4 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                    '<img  src="/storage/documentos/salidas/'+archivo.Nombre+'"  class="zdw-100pct zdh-100pct"></img>'+
                                '</button>'+
                                '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                case 'pdf':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                    '<img  src="{{asset('storage/iconos/pdf1.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                '</button>'+
                                    '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                case 'doc':
                case 'docx':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                '<img  src="{{asset('storage/iconos/DOC.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                '</button>'+
                                    '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                case 'xls':
                case 'xlsx':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                '<img  src="{{asset('storage/iconos/xlsx.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                '</button>'+
                                    '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                case 'xml':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                '<img  src="{{asset('storage/iconos/XML.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                '</button>'+
                                '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                // case 'mp3':
                // case 'wav':
                //     tipoArchivo ='<button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.Nombre+')"title='+archivo.Nombre+'>'+
                //                     '<img  src="/storage/documentos/salidas/'+archivo.Nombre+'"  class="mimagen" hidden></img>'+
                //                 '</button>'
                //     break;
                case 'mp4':
                case 'avi':
                case 'mov':
                    tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r6 zdmnw-r6 zdh-r4" onclick="viewarchivo(\''+archivo.Nombre+'\')"title='+archivo.Nombre+'>'+
                                    '<video id="video_preview"  src="/storage/documentos/salidas/'+archivo.Nombre+'"  class="zdw-100pct zdh-100pct"</video> '+
                                '</button>'+
                                '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
                    break;
                default:
                tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.Nombre+')"title='+archivo.Nombre+'>'+
                                    '<img  src= src="{{asset('storage/iconos/desconocido.png')}}"  class="mimagen" hidden></img>'+
                                '</button>'+
                                '<button type="button" class="eliminar-imagen" onclick="DeleteArchivoSalida(\''+archivo.id+'\')"title=Eliminar '+archivo.Nombre+'>Eliminar</button></div>'
            }
            $("#ArchivosSalida").append(tipoArchivo);
        });

    }
    window.viewarchivo=function (archivo){
        let ext=archivo.split('.')[1].toLowerCase();
        $("#ArchivoSalidaGrande").attr('hidden',true)
        $("#ArchivoSalidaGrande").empty();
        switch (ext) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                tipoArchivo ='<img  src="/storage/documentos/salidas/'+archivo+'"  class="zdmw-100pct"></img>'
                $("#ArchivoSalidaGrande").removeAttr('hidden')
                break;
            case 'pdf':
                tipoArchivo ='<iframe src="/storage/documentos/salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                    +'Tu navegador no soporta iframes.'+
                                +'</iframe>'
                                $("#ArchivoSalidaGrande").removeAttr('hidden')
                break;
            case 'doc':
            case 'docx':
                tipoArchivo ='<iframe src="/storage/documentos/salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                    +'Tu navegador no soporta iframes.'+
                                +'</iframe>'
                break;
            case 'xls':
            case 'xlsx':
                tipoArchivo ='<iframe src="/storage/documentos/salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                    +'Tu navegador no soporta iframes.'+
                                +'</iframe>'
                break;
            case 'xml':
                tipoArchivo ='<iframe src="/storage/documentos/salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                    +'Tu navegador no soporta iframes.'+
                                +'</iframe>'
                break;
            // case 'mp3':
            // case 'wav':
            //     tipoArchivo ='<button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.archivo+')"title='+archivo.archivo+'>'+
            //                     '<img  src="/storage/documentos/salidas/'+archivo.archivo+'"  class="mimagen" hidden></img>'+
            //                 '</button>'
            //     break;
            case 'mp4':
            case 'avi':
            case 'mov':
                tipoArchivo = '<video id="video_preview"  src="/storage/documentos/salidas/'+archivo+'" class="zdmw-100pct"  controls></video>'
                $("#ArchivoSalidaGrande").removeAttr('hidden')
                break;
            default:
            tipoArchivo ='<h5>El Archivo Que Se Eligio No Se puede Mostrar</h5>'
        }
        $("#ArchivoSalidaGrande").append(tipoArchivo);
    }
    window.DeleteArchivoSalida = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Eliminara El Archivo de Forma Permanente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('DetallesGenerales.Delete.File.Exit')}}",
                    type: "Delete",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id:id,
                    },
                    success: function(response) {
                        Swal.fire({ html: `Se Elimino Correctamente`, icon: 'success',showConfirmButton: false,timer: 2000,});
                        GetDatDetGen( $('#DetGenId').val());
                    },
                    error: function(xhr, status, error) {
                    if(xhr.status===499){
                        Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                    }else if(xhr.status===422){
                        Swal.fire({ title: 'Error', html: `Verifique Los datos:<br>${xhr.responseJSON.error}`, icon: 'error'});
                    }else{
                        let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                        Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                    }
                    }
                });
            } 
        });  
    }
    $('#nuevo_archivo').change(function(){ 
        const file = this.files[0]; // Obtén el primer archivo seleccionado
          $('#img_preview').attr('src',"");
          $('#img_preview').attr('hidden',true);
          $('#pdf_preview').attr('src',"");
          $('#pdf_preview').attr('hidden',true);
          $('#video_src_preview').attr('src',"");
          $('#video_preview')[0].load();
          $('#video_preview').attr('hidden',true);
          $('#text_preview').attr('hidden',true);
          
        if (file) {
        let fileType = file.type;

            const reader = new FileReader();
            reader.readAsDataURL(file); // Lee el archivo como una URL de datos
            reader.onload = function(e) {
            
            if (fileType.startsWith('image/')) { 
            $('#img_preview').attr('src',e.target.result)
            $('#img_preview').removeAttr('hidden');}

            else if (fileType === 'application/pdf') {
                $('#pdf_preview').attr('src',e.target.result)
                $('#pdf_preview').removeAttr('hidden');} 

            else if (fileType.startsWith('video/')){ 
                console.log(e.target.result)
            $('#video_src_preview').attr('src',e.target.result)
            $('#video_preview')[0].load();
            $('#video_preview').removeAttr('hidden'); } 
            else { 
            $('#text_preview').removeAttr('hidden');
            }
        }
        }
    })
    ThisForm.submit(function(e) {
        e.preventDefault();
        let ruta= "{{route('DetallesGenerales.Update.Exit')}}";
        let data= new FormData(this);
        Swal.fire({
            title: '¿Estás seguro de hacer la salida?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ruta,
                        type: "post",
                        data:data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            executeSearchdata()
                            ThisModal.modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        if(xhr.status===422){
                            ThisForm.find(".error-message").remove()
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors)
                                .map((msgs) => msgs.join("<br>"))
                                .join("<br>");
                            for (let field in errors) {
                                let input = ThisForm.find(`[name="${field}"]`);
                                let errorMessage =
                                    `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                                input.after(errorMessage);
                            }
                        }else{
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                        }
                        }
                    });
                } 
            });
    })   
})
</script>

@endpush