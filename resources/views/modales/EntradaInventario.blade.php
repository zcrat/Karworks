<div class="modal fade" id="entrada_producto" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Entrada</h5>
            </div>
            <div class="modal-body">
                <div class="vaniflex zdmg-r05  zdfw-w">
                    <input type="text" name="id_entrada" id="id_entrada" hidden>
                        <div class="select2conlabel zdw-100pct" id='div-producto-entrada'>
                            <label for="producto_entrada">Producto Almacen<strong>*</strong></label>
                            <select id="producto_entrada"name="producto_entrada" required></select>
                        </div>
                    <div class="vaniflex zdjc-between" >
                        <div class=" selectconlabel zdmg-r02">
                            <label for="cantidad_entrada">Cantidad<strong>*</strong></label>
                            <input required class="form-control" type="number" id="cantidad_entrada" name="cantidad_entrada">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="precio_entrada">Costo Total<strong>*</strong></label>
                            <input required class="form-control" type="text" id="precio_entrada" name="parte_entrada">
                        </div>
                    </div>
                    @if(auth()->user()->can('ver.taller.1') && auth()->user()->can('ver.taller.2'))
                        <div class="zdmg-r02" id='div_taller_nuevo_entrada'>
                            <label for="taller_nuevo_entrada">Taller</label>
                            <select name="taller_nuevo_entrada" class="form-control" id="taller_nuevo_entrada">
                                <option value="">Seleccionar</option>
                                <option value="1">Altozano</option>
                                <option value="2">Quiroga</option>
                            </select>
                        </div>
                    @endcan
                    <div class="select2conlabel zdw-100pct" id='div-proveedor_entrada'>
                        <label for="proveedor_entrada">Proveedor</label>
                        <select id="proveedor_entrada"name="proveedor_entrada" required></select>
                    </div>

                    <input hidden type="file" class='form-control' id="photos_entrada" name="photos_entrada" accept="image/*" multiple 
                    capture="environment">
                    <button type="button"  class='btn btn-success' id="elegirarchivo_entrada">Tomar Foto</button>
                    <button type="button"  class='btn btn-success' id="delpreimg_entrada">Eliminar Fotos</button>

                    <div class="zdmg-r02 zdflex zdscroll-x  zdw-100pct zdmw-45vw" id='fotosevidencia_entrada' hidden></div>

                    <div class="zdmg-r02 zdflex zdfd-column zdw-100pct zdmw-45vw" id='div_fotossubidas_entrada' hidden>
                        <h3>Fotos Cargadas</h3>
                        <div class="zdmg-r02 zdflex zdscroll-x zdw-100pct zdmw-45vw" id='fotossubidas_entrada'></div>
                    </div>

                    <div class="zdw-100pct zdflex zdjc-center" >
                        <div class="zdmg-r02 zdw-r30 zdmw-r30  dflex zdfd-column " id='div_img_preview_entrada' hidden>
                            <img id="img_preview_entrada" src="#"  class="mimagen"></img>
                            <button type="button"  class='btn btn-info' id='cerrarimagen_entrada'>Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeentrada">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="savenewentradainventario()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#entrada_producto');
        let DisparadorOtroModal=null;
        let arrayfiles=[];
        let arrayfilespreview=[];
        $(".closeentrada").on('click',closethismodal)

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

        window.newentradainventario=function(id = null,descripcion=null){
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            $('#id_entrada').val('');
            $('#cantidad_entrada').val('');
            $('#precio_entrada').val('');
            try {
                $('#taller_nuevo_entrada').val('')
            } catch (error) {
                
            }
            arrayfilespreview=[];
            arrayfiles=[];
            mostrarimgs();
            if(id){
                $('#producto_entrada').empty().append('<option value="' +id+'">' +(descripcion??'Sin Definir') +'</option>').val(id);
                $('#div-producto-entrada').attr('hidden',true);
            }else{
                $('#producto_entrada').empty().val('');
                $('#div-producto-entrada').removeAttr('hidden');
            }
            $('#proveedor_entrada').empty().val('');
            $('#div-proveedor_entrada').removeAttr('hidden');

            $('#div_fotossubidas_entrada').attr('hidden',true);
            $('#fotossubidas_entrada').empty();

            ThisModal.modal('show');
            DisparadorOtroModal=$(this).data('disparador')
        };
        window.editmovimientoentrada=function(id,cleanimgpre=true){
            $.ajax({
                url: "{{route('2025.Almacen.Read.Entrada')}}",
                type: "get",
                data:{
                    id
                },
                success: function(response) {
                    let cantidad=response.data.cantidad;
                    let precio=response.data.precio;
                    let id_entrada=response.data.id;
                    let imagenes=response.data.imagenes;
                    
                    $('#fotossubidas_entrada').empty();
                    $('#cantidad_entrada').val(cantidad);
                    $('#precio_entrada').val(precio);
                    $('#id_entrada').val(id_entrada);
                    $('#proveedor_entrada').empty().val('');
                    $('#producto_entrada').empty().val('');
                    $('#div-producto-entrada').attr('hidden',true);
                    $('#div_taller_nuevo_entrada').attr('hidden',true);
                    $('#div-proveedor_entrada').attr('hidden',true);
                    try {
                        $('#taller_nuevo_entrada').val('')
                    } catch (error) {
                        
                    }
                    if(imagenes.length > 0){
                        try {
                            imagenes.forEach((file) => {
                                console.log(file);
                                viewimg(file.foto,file.id);
                            })
                        } catch (error) {
                            console.log(error);
                            
                        }
                        $('#div_fotossubidas_entrada').removeAttr('hidden');
                    }else{
                        $('#div_fotossubidas_entrada').attr('hidden',true);
                    }

                    if(cleanimgpre){
                        arrayfilespreview=[];
                        arrayfiles=[];
                    }
                    mostrarimgs();
                    ThisModal.modal('show');
                },
                error: function(error) {
                    mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                }
            });
        }

        $('#producto_entrada').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#entrada_producto"),
            placeholder: 'Escribe para buscar por descripcion o clave...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Productos.Inventarios') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    };
                    return query;
                },
                delay: 500,
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.descripcion,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $('#proveedor_entrada').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#entrada_producto"),
            placeholder: 'Escribe para buscar por descripcion o clave...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Productos.Proveedores') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    };
                    return query;
                },
                delay: 500,
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nombre,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });

        window.deleteimagenentrada = (id)=>{
            Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('2025.Almacen.Delete.Foto')}}",
                        type: "DELETE",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id':id
                        },
                        success: function(response) {
                            mostramensajeexito(response.message ?? 'creado')
                            editmovimientoentrada(response.id,false)
                        },
                        error: function(error) {
                            mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                        }
                    });
                } 
            });
        }
        window.savenewentradainventario = ()=>{
            Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    let x ='';
                    try {
                        x= $('#taller_nuevo_entrada').val();
                    } catch (error) {
                    }

                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('producto', $('#producto_entrada').val());
                    formData.append('proveedor', $('#proveedor_entrada').val());
                    formData.append('cantidad', $('#cantidad_entrada').val());
                    formData.append('precio', $('#precio_entrada').val());
                    formData.append('id_entrada', $('#id_entrada').val());
                    formData.append('taller', x);
                    arrayfiles.forEach((file, index) => {
                        formData.append('fotos[]', file); // file = objeto File
                    });
                    $.ajax({
                        url: "{{route('2025.Almacen.Create.Entrada')}}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            mostramensajeexito(response.message ?? 'creado')
                            executeSearchdata()
                            closethismodal()
                        },
                        error: function(error) {
                            mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                        }
                    });
                } 
            });
        }
        
        $("#elegirarchivo_entrada").on("click", function (event) {
                $("#photos_entrada").trigger("click");
        });
        $("#photos_entrada").on("change", function (event) {
            let files = event.target.files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    arrayfiles.push(file);
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let dataURL = e.target.result;
                        arrayfilespreview.push(dataURL);
                        let index = arrayfilespreview.length - 1;
                        addimg(dataURL,index)
                    };
                    reader.readAsDataURL(file); // Leer archivo como Base64
                }
            } else {
                $("#fotosevidencia_entrada").attr('hidden', true);
            }
            $(this).val('')
        });
        window.viewfotorentrada=function (archivo){
            $("#img_preview_entrada").attr('src',archivo) 
            $("#div_img_preview_entrada").removeAttr('hidden')
        }
        window.viewfotosubidaentrada=function (url){
            $("#img_preview_entrada").attr('src','/storage/almacen/entradas/'+url) 
            $("#div_img_preview_entrada").removeAttr('hidden')
        }
        $('#cerrarimagen_entrada').on('click',function(){
            $("#img_preview_entrada").attr('src','') 
            $("#div_img_preview_entrada").attr('hidden',true) 
        })
        $(document).on("click", ".delpreimg_entrada", function (event) {
            let indice = $(this).data('id'); // Asegúrate de tener el índice del elemento en algún atributo de datos
            arrayfilespreview.splice(indice,1);
            arrayfiles.splice(indice,1);
            mostrarimgs();
    
        });
        $("#delpreimg_entrada").on("click", function (indice) {
            arrayfilespreview=[];
            arrayfiles=[];
            mostrarimgs();
        });
        function mostrarimgs(){
            $("#fotosevidencia_entrada").empty();
            if (arrayfilespreview.length > 0) {
                for (let index = 0; index < arrayfilespreview.length; index++) {
                    addimg(arrayfilespreview[index],index)
                }
                $("#fotosevidencia_entrada").removeAttr('hidden');
            }else{
                $("#fotosevidencia_entrada").attr('hidden', true);
            }
        }
        function addimg(data,index){
            let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container" data-index="${index}">
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8" onclick="viewfotorentrada('${data}')" title='Foto-${index}'>
                    <img src="${data}" class="zdw-100pct zdh-100pct">
                </button>
                <button type="button" class="delpreimg_entrada eliminar-imagen" data-id="${index}" title="Eliminar">
                    Eliminar
                </button>
            </div>`;
            
            $("#fotosevidencia_entrada").append(tipoArchivo);
            $("#fotosevidencia_entrada").removeAttr('hidden');
        }
        function viewimg(url,id){
            let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container">
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8" onclick="viewfotosubidaentrada('${url}')" title='Foto-${id}'>
                    <img src="/storage/almacen/entradas/${url}" class="zdw-100pct zdh-100pct">
                </button>
                <button type="button" class="eliminar-imagen"  onclick="deleteimagenentrada('${id}')" title="Eliminar">
                    Eliminar
                </button>
            </div>`;
            $("#fotossubidas_entrada").append(tipoArchivo);
        }

    });
</script>
@endpush