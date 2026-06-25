<div class="modal fade" id="producoinventariomodel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Producto</h5>
            </div>
            <div class="modal-body">
                <div class="vaniflex zdmg-r05 zdjc-between zdfd-column">
                    <div class=" selectconlabel zdmg-r02">
                        <label for="codigo_producto">Codigo Unico<strong>*</strong></label>
                        <input required class="form-control" type="text" id="codigo_producto" name="codigo_producto">
                    </div>
                    <div class="select2conlabel">
                        <label for="descripcion_producto">Producto Almacen<strong>*</strong></label>
                        <textarea name="descripcion_producto" id="descripcion_producto" placeholder="escriba el nombre del producto" class="form-control"></textarea>
                    </div>
                    <div class="zdmg-r02">
                            <label for="tipo_nuevo_producto">Tipo De Producto</label>
                            <select name="tipo_nuevo_producto" class="form-control" id="tipo_nuevo_producto">
                                <option value="">Seleccionar</option>
                                <option value="1">Refaccion</option>
                                <option value="2">Herramienta</option>
                            </select>
                        </div>
                    <div class=" selectconlabel zdmg-r02" id='div_cantidad_inicio'>
                        <label for="cantidad_inicio">Cantidad Compra<strong>*</strong></label>
                        <input required class="form-control" type="number" id="cantidad_inicio" name="cantidad_inicio">
                    </div>
                    <div class=" selectconlabel zdmg-r02" id='div_precio_producto'>
                        <label for="precio_producto">Precio Total de La Compra</label>
                        <input required class="form-control" type="number" id="precio_producto" name="precio_producto">
                    </div>
                    <div class=" selectconlabel zdmg-r02" id='div_proveedor'>
                        <label for="proveedor">Proveedor<strong>*</strong></label>
                        <select id="proveedor"name="proveedor" required></select>
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="marca_producto">Marca<strong>*</strong></label>
                        <select id="marca_producto"name="marca_producto" required></select>
                    </div>
                    @if(auth()->user()->can('ver.taller.1') && auth()->user()->can('ver.taller.2'))
                        <div class="zdmg-r02" id='div_taller_nuevo_producto'>
                            <label for="taller_nuevo_producto">Taller</label>
                            <select name="taller_nuevo_producto" class="form-control" id="taller_nuevo_producto">
                                <option value="">Seleccionar</option>
                                <option value="1">Altozano</option>
                                <option value="2">Quiroga</option>
                            </select>
                        </div>
                    @endcan
                    <div class=" selectconlabel zdmg-r02">
                        <label for="cantidad_minima">Cantidad Minima<strong>*</strong></label>
                        <input required class="form-control" type="number" id="cantidad_minima" name="cantidad_minima">
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="cantidad_optima">Cantidad Optima<strong>*</strong></label>
                        <input required class="form-control" type="number" id="cantidad_optima" name="cantidad_optima">
                    </div>
                    <div id='div-img-new-prod' class="vaniflex zdmg-r05 zdjc-between zdfd-column">
                        <div>
                            <input hidden type="file" class='form-control' id="photos_producto" name="photos_producto" accept="image/*" multiple 
                            capture="environment">
                            <button type="button"  class='btn btn-success' id="elegirarchivo_producto">Tomar Foto</button>
                            <button type="button"  class='btn btn-success' id="delpreimg_producto">Eliminar Fotos</button>
                        </div>

                        <div class="zdmg-r02 zdflex zdscroll-x  zdw-100pct zdmw-45vw" id='fotosevidencia_producto' hidden></div>

                        <div class="zdmg-r02 zdflex zdfd-column zdw-100pct zdmw-45vw" id='div_fotossubidas_producto' hidden>
                            <h3>Fotos Cargadas</h3>
                            <div class="zdmg-r02 zdflex zdscroll-x zdw-100pct zdmw-45vw" id='fotossubidas_producto'></div>
                        </div>

                        <div class="zdw-100pct zdflex zdjc-center" >
                            <div class="zdmg-r02 zdw-r30 zdmw-r30  dflex zdfd-column " id='div_img_preview_producto' hidden>
                                <img id="img_preview_producto" src="#"  class="mimagen"></img>
                                <button type="button"  class='btn btn-info' id='cerrarimagen_producto'>Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closenewproducto">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="savenewproducto()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#producoinventariomodel');
        let DisparadorOtroModal=null;
        let Id=null;
        let arrayfiles=[];
        let arrayfilespreview=[];

        $(".closenewproducto").on('click',closethismodal)
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
        window.newproductoinventario=function(id=null){
            Id=id;
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            arrayfilespreview=[];
            arrayfiles=[];
            mostrarimgs();
           
            if(Id){
                $.ajax({
                    url: "{{route('2025.Almacen.Read.Producto')}}",
                    type: "get",
                    data:{
                       id
                    },
                    success: function(response) {
                        $('#descripcion_producto').val(response.descripcion_producto);
                        $('#cantidad_minima').val(response.cantidad_minima);
                        $('#cantidad_optima').val(response.cantidad_optima);
                        $('#codigo_producto').val(response.codigo_producto);
                        $('#tipo_nuevo_producto').val(response.tipo_producto);
                        $('#div_proveedor').attr('hidden',true);
                        $('#proveedor').empty().val('');
                        $('#marca_producto').empty().append('<option value="' +response.marca_producto+'">' +(response.marca_producto) +'</option>').val(id);

                        try {
                            $('#div_taller_nuevo_producto').attr('hidden',true);
                            $('#taller_nuevo_producto').val('');
                        } catch (error) {
                        }
                        $('#div_cantidad_inicio').attr('hidden',true);
                        $('#cantidad_inicio').val('');
                        $('#div_precio_producto').attr('hidden',true);
                        $('#div-img-new-prod').attr('hidden',true);
                        $('#precio_producto').val('');
                        ThisModal.modal('show');
                    },
                    error: function(error) {
                        mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                    }
                });
            }else{
                try {
                    $('#div_taller_nuevo_producto').removeAttr('hidden');
                    $('#taller_nuevo_producto').val('');
                } catch (error) {
                }
                $('#div-img-new-prod').removeAttr('hidden');

                $('#div_proveedor').removeAttr('hidden');
                $('#marca_producto').empty().val('')
                $('#proveedor').empty().val('')
                $('#div_cantidad_inicio').removeAttr('hidden');
                $('#cantidad_inicio').val('');
                $('#div_precio_producto').removeAttr('hidden');
                $('#precio_producto').val('');
                $('#descripcion_producto').val('');
                $('#cantidad_minima').val('');
                $('#cantidad_optima').val('');
                $('#codigo_producto').val('');
                $('#tipo_nuevo_producto').val(''),
                ThisModal.modal('show');
            }
        };

        window.savenewproducto = ()=>{

            Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    let x ='';
                    try {
                    x= $('#taller_nuevo_producto').val();
                    } catch (error) {
                    }
                    let formData = new FormData();
                    if(Id!==null){
                        formData.append('id',Id);
                    }
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('taller',x);
                    formData.append('proveedor',$('#proveedor').val());
                    formData.append('cantidad_inicio',$('#cantidad_inicio').val());
                    formData.append('precio_producto',$('#precio_producto').val());
                    formData.append('descripcion_producto',$('#descripcion_producto').val());
                    formData.append('cantidad_minima',$('#cantidad_minima').val());
                    formData.append('cantidad_optima',$('#cantidad_optima').val());
                    formData.append('codigo_producto',$('#codigo_producto').val());
                    formData.append('marca_producto',$('#marca_producto').val());
                    formData.append('tipo_producto',$('#tipo_nuevo_producto').val());
                    arrayfiles.forEach((file, index) => {
                        formData.append('fotos[]', file); // file = objeto File
                    });
                    $.ajax({
                        url: "{{route('2025.Almacen.CrearOrEditar.Producto')}}",
                        type: "post",
                        data:formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            mostramensajeexito(response.message ?? 'creado')
                            executeSearchdata(1,false)
                            closethismodal()
                        },
                        error: function(error) {
                            mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                        }
                    });
                } 
            });
        } 
        $('#proveedor').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: ThisModal,
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
        $('#marca_producto').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: ThisModal,
            placeholder: 'Escribe para buscar por descripcion o clave...',
            allowClear: true,
            minimumInputLength: 0,
            tags: true, // 🔥 permite crear
            createTag: function (params) {
                const term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            },
            ajax: {
                url: '{{ route('Select2.Get.Productos.Marcas') }}',
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
                                id: item.nombre
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $("#elegirarchivo_producto").on("click", function (event) {
                $("#photos_producto").trigger("click");
        });
        $("#photos_producto").on("change", function (event) {
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
                $("#fotosevidencia_producto").attr('hidden', true);
            }
            $(this).val('')
        });
        window.viewfotorproducto=function (archivo){
            $("#img_preview_producto").attr('src',archivo) 
            $("#div_img_preview_producto").removeAttr('hidden')
        }
        $('#cerrarimagen_producto').on('click',function(){
            $("#img_preview_producto").attr('src','') 
            $("#div_img_preview_producto").attr('hidden',true) 
        })
        $(document).on("click", ".delpreimg_producto", function (event) {
            let indice = $(this).data('id'); // Asegúrate de tener el índice del elemento en algún atributo de datos
            arrayfilespreview.splice(indice,1);
            arrayfiles.splice(indice,1);
            mostrarimgs();
    
        });
        $("#delpreimg_producto").on("click", function (indice) {
            arrayfilespreview=[];
            arrayfiles=[];
            mostrarimgs();
        });
        function mostrarimgs(){
            $("#fotosevidencia_producto").empty();
            if (arrayfilespreview.length > 0) {
                for (let index = 0; index < arrayfilespreview.length; index++) {
                    addimg(arrayfilespreview[index],index)
                }
                $("#fotosevidencia_producto").removeAttr('hidden');
            }else{
                $("#fotosevidencia_producto").attr('hidden', true);
            }
        }
        function addimg(data,index){
            let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container" data-index="${index}">
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8" onclick="viewfotorproducto('${data}')" title='Foto-${index}'>
                    <img src="${data}" class="zdw-100pct zdh-100pct">
                </button>
                <button type="button" class="delpreimg_producto eliminar-imagen" data-id="${index}" title="Eliminar">
                    Eliminar
                </button>
            </div>`;
            
            $("#fotosevidencia_producto").append(tipoArchivo);
            $("#fotosevidencia_producto").removeAttr('hidden');
        }
    });
</script>
@endpush