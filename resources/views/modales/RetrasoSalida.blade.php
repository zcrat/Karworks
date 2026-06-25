<div class="modal fade" id="RetrasoSalidaModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="TiitleModalRetrasoSalidaModel">Retraso De Salida</h5>
                <button type="button" class="btn-close"  data-bs-dismiss="modal"></button>
            </div>
            <form id="RetrasoSalidaForm">
                @csrf
                <div class="modal-body">
                    <div class=" selectconlabel zdmg-r02">
                        <input type="hidden" id='RRVV_id' name='RRVV_id'>
                       <label for="DetRetSal" >Detalles Del Motivo De La Demora</label>
                       <textarea name="DetRetSal" id="DetRetSal"></textarea>
                       
                    </div>
                    <div class="zdmg-r02 zdflex zdscroll-y zdmw-r30" id='archivosretraso' hidden></div>
                    <div class="zdmg-r02 zdw-r30 zdmw-r30" id='archivogrande' hidden>

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
                    <button type="submit" id="RetrasoSalidaModel" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('modales.nuevousuariocaja')
@push('scripts')
<script>
$(function(){
    window.OpenRetrasoSalida = function(id) {
        $.ajax({
            url: '{{route('Recepciones.Vehiculares.Get.Data.Demora')}}',
            type: "GET",
            data:{
                id:id,
            },
            success: function(response) {
                data=response.element
                archivos=data.files__retraso
                console.log(response)
                vaciariframe()
                $('#RRVV_id').val(data.id).trigger('change'); 
                $('#DetRetSal').val(data.Notas_Retraso).trigger('change'); 
                $("#newmovimientocaja").find(".error-message").remove();
                
                $("#archivosretraso").attr('hidden',true)
                $("#archivogrande").attr('hidden',true)
                $("#archivosretraso").empty();
                $("#archivogrande").empty();
                archivos.forEach(function(archivo) {
                    $("#archivosretraso").removeAttr('hidden')
                   let ext = archivo.Archivo.split('.').pop().toLowerCase();
                    console.log(ext)
                    switch (ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button" class="boton-imagen zdmg-r02 zdw-r4 zdmnw-r4 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                            '<img  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo.Archivo+'"  class="zdw-100pct zdh-100pct"></img>'+
                                        '</button>'+
                                        '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        case 'pdf':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                            '<img  src="{{asset('storage/iconos/pdf1.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                        '</button>'+
                                            '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        case 'doc':
                        case 'docx':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                        '<img  src="{{asset('storage/iconos/DOC.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                        '</button>'+
                                            '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        case 'xls':
                        case 'xlsx':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                        '<img  src="{{asset('storage/iconos/xlsx.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                        '</button>'+
                                            '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        case 'xml':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r3 zdmnw-r3 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                        '<img  src="{{asset('storage/iconos/XML.png')}}"alt=""  class="zdw-100pct zdh-100pct"></img>'+
                                        '</button>'+
                                        '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        // case 'mp3':
                        // case 'wav':
                        //     tipoArchivo ='<button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.Archivo+')"title='+archivo.Archivo+'>'+
                        //                     '<img  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo.Archivo+'"  class="mimagen" hidden></img>'+
                        //                 '</button>'
                        //     break;
                        case 'mp4':
                        case 'avi':
                        case 'mov':
                            tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button"  class="boton-imagen zdmg-r02 zdw-r6 zdmnw-r6 zdh-r4" onclick="viewarchivo(\''+archivo.Archivo+'\')"title='+archivo.Archivo+'>'+
                                            '<video id="video_preview"  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo.Archivo+'"  class="zdw-100pct zdh-100pct"</video> '+
                                        '</button>'+
                                        '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                            break;
                        default:
                        tipoArchivo ='<div class="zdflex zdjc-center zdfd-column"><button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.Archivo+')"title='+archivo.Archivo+'>'+
                                            '<img  src= src="{{asset('storage/iconos/desconocido.png')}}"  class="mimagen" hidden></img>'+
                                        '</button>'+
                                        '<button type="button" class="eliminar-imagen" onclick="deletearchivomovimientocaja(\''+archivo.id+'\')"title=Eliminar '+archivo.Archivo+'>Eliminar</button></div>'
                    }
                    $("#archivosretraso").append(tipoArchivo);
                });



                $('#RetrasoSalidaModel').modal('show');
            },
            error: function(xhr, status, error) {
                if(xhr.status===422){
                    Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                    searchdata();
                }else{
                    let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                    Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                }
            }
        });
    }
    window.deletearchivomovimientocaja = function(id) {
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
                            url: "{{route('Recepciones.Vehiculares.Delete.File.Demora')}}",
                            type: "delete",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                id:id,
                            },
                            success: function(response) {
                                mostramensaje("success",response.message??'Se Elimino Correctamente')
                                OpenRetrasoSalida(response.idrrvv)
                            },
                            error: function(xhr, status, error) {
                                if(xhr.status===422){
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessages = Object.values(errors).map((msgs) => msgs.join("<br>")).join("<br>");
                                    mostramensaje("error",errorMessages)
                                }else{
                                    let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                     mostramensaje("error",errorMessage??'Se Elimino Correctamente')
                                }
                            }
                        });
                    } 
                });  
        }
            window.viewarchivo=function (archivo){
            let ext=archivo.split('.')[1].toLowerCase();
            $("#archivogrande").attr('hidden',true)
            $("#archivogrande").empty();
            switch (ext) {
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                                tipoArchivo ='<img  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'"  class="zdmw-100pct"></img>'
                                $("#archivogrande").removeAttr('hidden')
                                break;
                            case 'pdf':
                                tipoArchivo ='<iframe src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                                    +'Tu navegador no soporta iframes.'+
                                                +'</iframe>'
                                                $("#archivogrande").removeAttr('hidden')
                                break;
                            case 'doc':
                            case 'docx':
                                tipoArchivo ='<iframe src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                                    +'Tu navegador no soporta iframes.'+
                                                +'</iframe>'
                                break;
                            case 'xls':
                            case 'xlsx':
                                tipoArchivo ='<iframe src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                                    +'Tu navegador no soporta iframes.'+
                                                +'</iframe>'
                                break;
                            case 'xml':
                                tipoArchivo ='<iframe src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'" class="zdw-100pct zdmnh-r40" frameborder="0">'+
                                                    +'Tu navegador no soporta iframes.'+
                                                +'</iframe>'
                                break;
                            // case 'mp3':
                            // case 'wav':
                            //     tipoArchivo ='<button type="button" class="btn btn-warning" onclick="viewarchivo('+archivo.archivo+')"title='+archivo.archivo+'>'+
                            //                     '<img  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo.archivo+'"  class="mimagen" hidden></img>'+
                            //                 '</button>'
                            //     break;
                            case 'mp4':
                            case 'avi':
                            case 'mov':
                                tipoArchivo = '<video id="video_preview"  src="/storage/documentos/RV/Retrasos/Salidas/'+archivo+'" class="zdmw-100pct"  controls></video>'
                                $("#archivogrande").removeAttr('hidden')
                                break;
                            default:
                            tipoArchivo ='<h5>El Archivo Que Se Eligio No Se puede Mostrar</h5>'
                        }

                        
                        $("#archivogrande").append(tipoArchivo);
                        
                        
        }
        function vaciariframe(){
            $('#img_preview').attr('src',"");
            $('#img_preview').attr('hidden',true);
            $('#pdf_preview').attr('src',"");
            $('#pdf_preview').attr('hidden',true);
            $('#video_src_preview').attr('src',"");
            $('#video_preview')[0].load();
            $('#video_preview').attr('hidden',true);
            $('#text_preview').attr('hidden',true);
        }
    $('#nuevo_archivo').change(function(){ 
        const file = this.files[0]; // Obtén el primer archivo seleccionado
        vaciariframe()
          
        if (file) {
            let fileType = file.type;
            const reader = new FileReader();
            reader.readAsDataURL(file); // Lee el archivo como una URL de datos
            reader.onload = function(e) {
                if (fileType.startsWith('image/')) { 
                    $('#img_preview').attr('src',e.target.result)
                    $('#img_preview').removeAttr('hidden');
                }else if (fileType === 'application/pdf') {
                    $('#pdf_preview').attr('src',e.target.result)
                    $('#pdf_preview').removeAttr('hidden');
                }else if (fileType.startsWith('video/')){ 
                    $('#video_src_preview').attr('src',e.target.result)
                    $('#video_preview')[0].load();
                    $('#video_preview').removeAttr('hidden');
                }else { 
                    $('#text_preview').removeAttr('hidden');
                }
            }
        }

    })
     function mostramensaje(icon,message) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                        });
                        Toast.fire({
                        icon: icon,
                        title: message
                    });
        }
    $("#RetrasoSalidaForm").submit(function(e) {
        e.preventDefault();
        let ruta= "{{route('Recepciones.Vehiculares.Update.Data.Demora')}}";
        let data= new FormData(this);
        Swal.fire({
            title: '¿Seguro De Guardar?',
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
                        $("#RetrasoSalidaModel").modal('hide');
                        mostramensaje("success",'Guardado Correctamente')
                    },
                    error: function(xhr, status, error) {
                        if(xhr.status===422){
                        $("#RetrasoSalidaModel").find(".error-message").remove()
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map((msgs) => msgs.join("<br>")).join("<br>");
                        mostramensaje("error",errorMessages)
                        
                    }else{
                        let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                        mostramensaje("error",errorMessage)
                    }
                    }
                });
            } 
        });
    })
})
</script>

@endpush