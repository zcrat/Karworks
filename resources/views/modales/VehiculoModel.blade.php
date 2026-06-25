<div class="modal fade" id="VehiculoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" >Nuevo Vehiculo</h5>
                <button type="button" class="btn-close CloseVehiculoModal"  aria-label="Close">
            </div>
            <form id="VehiculoForm">
                @csrf
            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <input type="hidden" id="VehId" name="VehId">
                <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                    <div class="select2conlabel zdw-40pct  zdrelative">
                        <label for="tipo">Tipo <strong>*</strong></label>
                        <select id="VehTip"name="VehTip" required></select>
                        <button id="VehTipNew" class="btnin" type="button">+</button>
                    </div>
                    <div class="select2conlabel zdw-40pct  zdrelative">
                        <label for="VehMar">Marca <strong>*</strong></label>
                        <select id="VehMar" name="VehMar" required></select>
                        <button class="btnin NewElementOneAttribute" data-origin="MarcaVehiculo" data-label="Marca De Vehiculo" data-title="Nueva Marca De Vehiculo" id="VehMarNew" type="button">+</button>
                    </div>
                    <div class="select2conlabel zdw-40pct  zdrelative">
                        <label for="VehMod">Modelo <strong>*</strong></label>
                        <select id="VehMod" name="VehMod" required></select>
                        <button id="VehModNew" data-origin="ModeloVehiculo" data-label="Modelo De Vehiculo" data-title="Nuevo Modelo De Vehiculo"  class="btnin NewElementOneAttribute" type="button">+</button>
                    </div>
                    <div class="select2conlabel zdw-40pct  zdrelative">
                        <label for="VehCol">Color <strong>*</strong></label>
                        <select id="VehCol"name="VehCol" required></select>
                        <button id="VehColNew" data-origin="ColorVehiculo" data-label="Color De Vehiculo" data-title="Nuevo Color De Vehiculo"  class="btnin NewElementOneAttribute" type="button">+</button>
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="VehAnio">Año<strong>*</strong></label>
                        <input required class="form-control" type="number" pattern="^\d{4}$" max="9999" placeholder="Ej.2024 " id="VehAnio" name="VehAnio">
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="VehNumEco">Numero Economico<strong>*</strong></label>
                        <input required class="form-control" type="text" placeholder="Ej.27379 "id="VehNumEco" name="VehNumEco">
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="VehVim">VIN<strong>*</strong></label>
                        <input required class="form-control" type="text" placeholder="Ej.JJSOE18P388988750 " id="VehVim" name="VehVim">
                    </div>
                    <div class=" selectconlabel zdmg-r02">
                        <label for="VehPla">Placas<strong>*</strong></label>
                        <input required class="form-control" type="text" placeholder="Ej.YBU-80-66 "id="VehPla" name="VehPla">
                    </div>

                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseVehiculoModal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="VehiculoModalSave">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#VehiculoModal');
        let DisparadorOtroModal=null;
        $(".NewVehiculoTaller").on('click',function(){
            DisparadorOtroModal=$('#'+$(this).data('select2'))
            OpenNewCar();
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisModal.modal('show');
        });
        $(".EditVehiculoTaller").on('click',async function(){
            const id = $(this).data('id');
            if(id){
                DisparadorOtroModal=$('#'+$(this).data('select2'))
                const isSuccess = await GetDataVehiculo(id);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
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
        });
        $(".CloseVehiculoModal").on('click',function(){
            closethismodal()
        })
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
        $('#VehMar').on('change', function() {
            $('#VehMod').empty();
            if ($(this).val()) {
                $('#VehModNew').data('atribute2',$(this).val())
            } else {
               $('#VehModNew').removeData('atribute2');
            }
        });

        $('#VehMar').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#VehiculoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '/select2/obtenermarcasvehiculos',
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
        $('#VehMod').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#VehiculoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '/select2/obtenermodelosvehiculo',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        marcaid:$('#VehMar').val(),
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
        $('#VehCol').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#VehiculoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '/select2/obtenercoloresvehiculo',
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
        $('#VehTip').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#VehiculoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '/select2/obtenertipovehiculo',
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
        window.OpenNewCar = function(){
            $('#VehiculoModal input').not('input[name="_token"]').val('').trigger('change');
            $('#VehiculoModal select').val('').trigger('change'); 
            $("#VehiculoModal").find(".error-message").remove();
        }
        window.GetDataVehiculo = async function(id) {
            try {
                const response = await $.ajax({
                    url: "{{route('2025.Vehiculos.Get.Element')}}",
                    type: "get",
                    data: { id: id },
                });
                llenar_campos(response.element);
                return true;
            } catch (xhr) {
                Swal.fire({ 
                    title: 'Error: ' + xhr.responseJSON.status, 
                    html: `Detalles del error:<br> ${xhr.responseJSON.message}`, 
                    icon: 'error' 
                });
                return false;
            }
        }
        function llenar_campos(element){
            OpenNewCar();
            $('#VehAnio').val(element.anio); 
            $('#VehNumEco').val(element.no_economico); 
            $('#VehVim').val(element.vim); 
            $('#VehPla').val(element.placas); 
            $("#VehTip").empty().append('<option value="' + element.tipo.id + '">' + element.tipo.nombre + '</option>').removeAttr('disabled')
            $("#VehMar").empty().append('<option value="' + element.marca.id + '">' + element.marca.nombre + '</option>').trigger('change').removeAttr('disabled')
            $("#VehMod").empty().append('<option value="' + element.modelo.id + '">' + element.modelo.nombre +'</option>').removeAttr('disabled')
            $("#VehCol").empty().append('<option value="' + element.color.id + '">' + element.color.nombre + '</option>').removeAttr('disabled')
            $("#VehId").val(element.id);
        }
        $("#VehiculoForm").submit(async function(e){
            e.preventDefault();
            const thisform = $(this);
            const formData = new FormData(this);
            const rutacreate="{{route('2025.Vehiculos.Create')}}";
            const rutaedit="{{route('2025.Vehiculos.Update')}}";
            let ruta=null;
            if($('#VehId').val()){
                ruta=rutaedit
            }else{
                 ruta=rutacreate
            }
            const button=$("#VehiculoModalSave");

            Swal.fire({
                icon: "question",
                text: "¿Estás Seguro?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn-primary",
                    cancelButton: "btn-light",
                },
            }).then(async (result) => {
                if (result.isConfirmed) {
                    button.attr('disabled', true);
                    try {
                        const response = await $.ajax({
                            url: ruta,
                            type: 'post',
                            data: formData,
                            processData: false,
                            contentType: false,
                        });
                        Swal.fire({
                            title: 'Éxito',
                            html: response.message,
                            icon: 'success',
                            timer: 1000
                        });
                        if(DisparadorOtroModal){
                            DisparadorOtroModal.empty().append('<option value="' + response.id + '">' + response.nombre + '</option>').val(response.id).trigger('change');
                        }
                        closethismodal();
                    } catch (xhr) {
                        console.log(xhr)
                        if (xhr.status === 422) {
                            thisform.find(".error-message").remove();
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).map((msgs) => {
                                if (msgs && msgs !== "Este campo es obligatorio." && msgs !== "La opción no es válida") {
                                    return msgs.join("<br>");
                                }
                            }).filter(Boolean).join("<br>");
                            for (let field in errors) {
                                let input = thisform.find(`[name="${field}"]`);
                                let errorMessage = `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                                input.after(errorMessage);
                            }
                            Swal.fire({
                                icon: "warning",
                                title: "Información",
                                html: errorMessages,
                                timer: 10000
                            });
                        } else {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message?? "Ocurrio un Error Inesperado <br> Contacte A Soporte"}`,
                                icon: 'error'
                            });
                        }
                    } finally {
                        button.removeAttr('disabled');
                    }
                }
            });
        });
        
    });
</script>
@endpush