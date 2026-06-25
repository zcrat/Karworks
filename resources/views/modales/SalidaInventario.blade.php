<div class="modal fade" id="salida_producto" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Salida</h5>
            </div>
            <div class="modal-body">
                <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                    <div class="select2conlabel zdw-100pct" id='div-producto-salida'>
                        <label for="producto_salida" >Producto Almacen<strong>*</strong></label>
                        <select id="producto_salida"name="producto_salida" required></select>
                    </div>
                    
                    <div class="vaniflex zdjc-between" >
                        @if(auth()->user()->can('ver.taller.1') && auth()->user()->can('ver.taller.2'))
                            <div class="zdmg-r02">
                                <label for="taller_nuevo_salida">Taller</label>
                                <select name="taller_nuevo_salida" class="form-control" id="taller_nuevo_salida">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Altozano</option>
                                    <option value="2">Quiroga</option>
                                </select>
                            </div>
                        @endcan
                        <div class=" selectconlabel zdmg-r02" id='div-producto-salida'>
                            <label for="cantidad_salida">Cantidad<strong>*</strong></label>
                            <input required class="form-control" type="number" id="cantidad_salida" name="cantidad_salida">
                        </div>
                        <div class=" selectconlabel zdw-60pct" id='div_orden_salida' hidden>
                            <label for="orden_salida">Orden Servicio<strong>*</strong></label>
                            <select id="orden_salida"name="orden_salida" required></select>
                        </div>
                        <div class=" selectconlabel zdw-60pct" id='div_motivo_salida' hidden>
                            <label for="motivo_salida">Motivo De La Salida<strong>*</strong></label>
                            <textarea id="motivo_salida"name="motivo_salida" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class=" selectconlabel zdw-100pct" id='div_datos_vehiculos'>
                        <label for="orden_salida">Datos Vehiculo</label>
                        <input disabled type="text" name="datosvehiculo" id="datosvehiculo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closesalida">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="savenewsalidainventario()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#salida_producto');
        let DisparadorOtroModal=null;
        $(".closesalida").on('click',closethismodal)
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
        window.newsalidainventario=function(id = null,descripcion=null){
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            try {
                $('#taller_nuevo_salida').val('')
            } catch (error) {
            }
            $('#cantidad_salida').val('');
            $('#orden_salida').empty().val('');
            $('#motivo_salida').val('');
            $('#datosvehiculo').empty().val('');
            $('#div_orden_salida').attr('hidden',true);
            $('#div_motivo_salida').attr('hidden',true);
            $('#div_datos_vehiculos').attr('hidden',true);

            if(id){
                $('#producto_salida').empty().append('<option value="' + id+'">' +(descripcion??'Sin Definir') +'</option>').val(id).trigger('change');
                $('#div-producto-salida').attr('hidden',true);
            }else{
                $('#producto_salida').empty().val('');
                $('#div-producto-salida').removeAttr('hidden');
            }
            ThisModal.modal('show');
            DisparadorOtroModal=$(this).data('disparador')
        };

        $('#producto_salida').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#salida_producto"),
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
        $('#orden_salida').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#salida_producto"),
            placeholder: 'Escribe para buscar por descripcion o clave...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.OrdenesServico') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        id: $('#producto_salida').val(),
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
        $('#orden_salida').on('change',function(){
            let id=$('#orden_salida').val();
            if(id){
                $.ajax({
                    url: "{{route('DetallesGenerales.Get.Element')}}",
                    type: "get",
                    data:{
                        id
                    },
                    success: function(response) {
                        let vehiculo=response.element.vehiculo;
                        $('#datosvehiculo').val(vehiculo.no_economico + '-' + vehiculo.placas + '-' + vehiculo.vim + '-' + vehiculo.modelo.nombre + '-' + vehiculo.marca.nombre);
                    },
                    error: function(error) {
                        mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                    }
                });
            }else{
                $('#datosvehiculo').val('')
            }
        })
        $('#producto_salida').on('change',function(){
            let id=$('#producto_salida').val();
            if(id){
                $.ajax({
                    url: "{{route('2025.Almacen.Read.Producto')}}",
                    type: "get",
                    data:{
                        id
                    },
                    success: function(response) {
                      if(response.tipo_producto == 1){
                        $('#div_orden_salida').removeAttr('hidden');
                        $('#div_datos_vehiculos').removeAttr('hidden');
                        $('#div_motivo_salida').attr('hidden',true);
                        $('#motivo_salida').val('');
                    }else{
                        $('#div_motivo_salida').removeAttr('hidden')
                        $('#orden_salida').empty().val('');
                        $('#datosvehiculo').empty().val('');
                        $('#div_orden_salida').attr('hidden',true);
                        $('#div_datos_vehiculos').attr('hidden',true);
                      } 
                    },
                    error: function(error) {
                        mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                    }
                });
            }else{
                $('#orden_salida').empty().val('');
                $('#motivo_salida').val('');
                $('#datosvehiculo').empty().val('');
                $('#div_orden_salida').attr('hidden',true);
                $('#div_motivo_salida').attr('hidden',true);
                $('#div_datos_vehiculos').attr('hidden',true);
            }
        })
        window.savenewsalidainventario = ()=>{
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
                        x= $('#taller_nuevo_salida').val();
                    } catch (error) {
                    }
                    $.ajax({
                        url: "{{route('2025.Almacen.Create.Salida')}}",
                        type: "post",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            producto:$('#producto_salida').val(),
                            cantidad:$('#cantidad_salida').val(),
                            orden_id:$('#orden_salida').val(),
                            motivo:$('#motivo_salida').val(),
                            taller:x,
                        },
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
    });
</script>
@endpush