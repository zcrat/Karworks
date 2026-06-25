<!-- Modal -->
<div class="modal fade" id="DetallesGeneralesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DetGenTittle">Editar Detalles Generales</h5>
                
                </button>
            </div>
            <form id="DetallesGeneralesForm">
            @csrf
            <div class="modal-body">
                    <p class="h5 text-uppercase font-weight-bold border-bottom">Datos Generales de la Solicitud</p>
                    <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                    <input type='hidden' id="DetGenId" name="DetGenId">
                        <div class=" select2conlabel zdw-30pct  zdrelative">
                            <label for="DetGenOrdSer">Ord.servicio<strong>*</strong></label>
                            <input required class="form-control" type="text" id="DetGenOrdSer" name="DetGenOrdSer">
                        </div>
                        <div class=" select2conlabel zdw-30pct  zdrelative">
                            <label for="DetGenOrdSeg">Ord. Seguimiento<strong>*</strong></label>
                            <input required class="form-control" type="text" id="DetGenOrdSeg" name="DetGenOrdSeg">
                        </div>
                        <div class=" select2conlabel zdw-30pct  zdrelative">
                            <label for="DetGenOrdOpc">Orden Opcional<strong>*</strong></label>
                            <input required class="form-control" type="text" id="DetGenOrdOpc" name="DetGenOrdOpc">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenUbi">Ubicacion<strong>*</strong></label>
                            <input required class="form-control" type="text" id="DetGenUbi" name="DetGenUbi">
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenGasEnt">Gasolina Entreda<strong>*</strong></label>
                            <select id="DetGenGasEnt" name="DetGenGasEnt" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="0">LLENO</option>
                                <option value="1">3/4</option>
                                <option value="2">2/4</option>
                                <option value="3">1/4</option>
                                <option value="4">vacio</option>
                            </select>
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenFecEsp">Fecha Esperada<strong>*</strong></label>
                            <input required class="form-control" type="datetime-local"  id="DetGenFecEsp"
                                name="DetGenFecEsp">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenFecAlt">Fecha Alta<strong>*</strong></label>
                            <input required class="form-control" type="datetime-local"  id="DetGenFecAlt"
                                name="DetGenFecAlt">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenKilEnt">Km De Ingreso<strong>*</strong></label>
                            <input required class="form-control" type="number" id="DetGenKilEnt" name="DetGenKilEnt">
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenEmp">Empresa<strong>*</strong></label>
                            <select id="DetGenEmp" name="DetGenEmp" required></select>
                            <button id="DetGenEmpNew" class="btnin"  type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenCli">Clientes <strong>*</strong></label>
                            <select id="DetGenCli" name="DetGenCli" required></select>
                            <button id="DetGenCliNew" class="btnin"  type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenAdmTra">Administrador de Trasportes <strong>*</strong></label></label>
                            <select id="DetGenAdmTra" name="DetGenAdmTra" required></select>
                            <button data-origin="UserTaller1" data-label="Nombre" data-select2='DetGenAdmTra' data-title="Nuevo Administrador de Trasportes" class="btnin NewElementOneAttribute" type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenJefPro">Jefe de Proceso<strong>*</strong></label></label>
                            <select id="DetGenJefPro"name="DetGenJefPro" required></select>
                            <button data-origin="UserTaller2" data-label="Nombre" data-select2='DetGenJefPro' data-title="Nuevo Jefe de Proceso" class="btnin NewElementOneAttribute" type="button">+</button>
                        </div>
                        <div class="elect2conlabel zdw-45pct  zdrelative"><label for="DetGenTel">Telefono<strong>*</strong></label><input
                                class="form-control" id="DetGenTel" name="DetGenTel" maxlength="10"
                                pattern="\d{10}" type="tel" placeholder="Ej. 4443552266 " required>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="DetGenTra">Trabajador<strong>*</strong></label></label>
                            <select id="DetGenTra" name="DetGenTra" required></select>
                            <button data-origin="UserTaller3" data-label="Nombre" data-select2='DetGenTra' data-title="Nuevo Trabajador" class="btnin NewElementOneAttribute" type="button">+</button>
                        </div>
                        <div class="zdw-45pct vaniflex zdfd-column">
                            <label for="DetGenIndCli">Indicaciones Cliente</label>
                            <textarea class="zdh-100pct form-control" name="DetGenIndCli" id="DetGenIndCli"></textarea>
                        </div>
                    </div>
                    <p class="h5 text-uppercase font-weight-bold border-bottom">Datos del Vehículo</p>
                    <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                        
                        <div class="select2conlabel zdw-45pct zdrelative">
                            <label for="DetGenVeh">Vehiculo <span class="spanrelleno">#Econonomico - Placas</span><strong>*</strong></label>
                            <select  id="DetGenVeh" name="DetGenVeh" required></select>
                            <button class="btnin NewVehiculoTaller" data-select2='DetGenVeh' id="DetGenVehNew" type="button" >+</button>
                            <button class="btnin EditVehiculoTaller" data-select2='DetGenVeh' id="DetGenVehEdit" type="button" hidden data-id=''><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                        </div>
            
                        <div class="zdw-45pct">
                                <label for="tipo" class='zdfz-r08'>Tipo<strong>*</strong></label>
                                <select id="DetGenVehTip" name="DetGenVehTip" required></select>
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="DetGenVehMod">Modelo</label>
                            <input required class="form-control" type="text" disabled id="DetGenVehMod">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="DetGenVehVin">VIN</label>
                            <input  class="form-control" type="text" disabled id="DetGenVehVin">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="DetGenVehPla">Placas</label>
                            <input  class="form-control" type="text" disabled id="DetGenVehPla">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="DetGenVehAnio">Año</label>
                            <input  class="form-control" type="text" disabled id="DetGenVehAnio">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="DetGenVehMar">Marca</label>
                            <input  class="form-control" type="text" disabled id="DetGenVehMar">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseDetallesGenerales">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="DetGenSave">Actualizar Detalles</button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#DetallesGeneralesModal');
        let DisparadorOtroModal = null;
        $(".EditDetallesGenerales").on('click',async function(){
            const id = $(this).data('id');
            if(id){
                const isSuccess = await GetDataGeneralPresupuesto(id);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    ThisModal.modal('show');
                    DisparadorOtroModal=$(this).data('disparador')
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
        $(".CloseDetallesGenerales").on('click',function(){
            closethismodal();
        })
        function closethismodal(){
            ThisModal.modal('hide');
            DisparadorOtroModal=null;
            if(ModalFather){
                ModalFather.modal('show');
                ModalFather=null;
            }
            
        }
        $('#DetGenEmp').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Companies') }}',
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
        $('#DetGenCli').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Customers') }}',
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
        $('#DetGenVeh').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Vehicles') }}',
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
        $('#DetGenVehTip').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Available.Types.Concepts') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: @json($modulo),
                        contrato:@json($contrato),
                        anio: @json($anio),
                        zona:@json($zona)
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
        $('#DetGenAdmTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo : 1,
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
        $('#DetGenJefPro').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo : 2,
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
        $('#DetGenTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#DetallesGeneralesModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo : 3,
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
        window.GetDataGeneralPresupuesto = async function(id) {
            try {
                const response = await $.ajax({
                    url: "{{route('DetallesGenerales.Get.Element')}}",
                    type: "get",
                    data: { id: id },
                });
                // Si la solicitud es exitosa
                llenar_campos(response.element);
                return true; // Devuelve true si todo salió bien
            } catch (xhr) {
                Swal.fire({ 
                    title: 'Error: ' + xhr.responseJSON.status, 
                    html: `Detalles del error:<br> ${xhr.responseJSON.message}`, 
                    icon: 'error' 
                });
                return false; // Devuelve false si hubo un error
            }
        };
        function llenar_campos(element){
            $('#DetGenOrdSer').val(element.OrdenServicio);
            $('#DetGenOrdSeg').val(element.OrdenSeguimiento);
            $('#DetGenOrdOpc').val(element.Orden);
            $('#DetGenUbi').val(element.Ubicacion);
            $('#DetGenFecAlt').val(element.Fecha_entrada);
            $('#DetGenFecEsp').val(element.Fecha_Esperada);
            $('#DetGenKilEnt').val(element.Kilometraje_entrada);
            $('#DetGenGasEnt').val(element.Gas_entrada);
            $('#DetGenTel').val(element.Telefono);
            $('#DetGenId').val(element.id);
            $("#DetGenIndCli").val(element.Indicaciones_cliente); 

            $("#DetGenEmp").empty().append('<option value="' + element.empresa.id + '">' + element.empresa.nombre + '</option>');
            $("#DetGenCli").empty().append('<option value="' + element.Customer_id + '">' + element.customer.nombre + '</option>');
            $("#DetGenVeh").empty().append('<option value="' + element.vehiculo.id + '">' + element.vehiculo.no_economico + '-' + element.vehiculo.placas + '</option>');
            $("#DetGenAdmTra").empty().append('<option value="' + element.administrador_trasporte.id + '">' + element.administrador_trasporte.nombre + '</option>');
            $("#DetGenJefPro").empty().append('<option value="' + element.jefede_proceso.id + '">' + element.jefede_proceso.nombre + '</option>');
            $("#DetGenTra").empty().append('<option value="' + element.trabajador.id + '">' + element.trabajador.nombre + '</option>');
            $("#DetGenVehTip").empty().append('<option value="' + element.tipo_vehiculo.id + '">' + element.tipo_vehiculo.nombre + '</option>');

            $('#DetGenVehNew').attr('hidden',true);
            $('#DetGenVehEdit').data('id',element.vehiculo.id).removeAttr('hidden');
            llenar_campos_vehiculo(element.vehiculo);
        }
        $("#DetGenVeh").on("change", function(){
            let id=$(this).val()
            if(id){
                $.ajax({
                type: 'GET',
                url: '/Zcrat/Vehiculo/Get/Element',
                data:{
                    id: id,
                },
                success: function(response) {
                    $('#DetGenVehNew').attr('hidden',true);
                    $('#DetGenVehEdit').data('id',response.element.id).removeAttr('hidden');
                    llenar_campos_vehiculo(response.element)
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon:'error',
                        title:'Vehiculo No Dispoble, Intentelo Mas Tarde',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    $("#DetGenVeh").val('').trigger('change');
                }
            }); 
            }else{
                $('#DetGenVehNew').removeAttr('hidden');
                $('#DetGenVehEdit').attr('hidden',true).removeData('id');
                $('#DetGenVehMod').val('');
                $('#DetGenVehMar').val('');
                $('#DetGenVehVin').val('');
                $('#DetGenVehPla').val('');
                $('#DetGenVehAnio').val('');
            }
        })
        function llenar_campos_vehiculo(element){
            $('#DetGenVehMod').val(element.modelo.nombre);
            $('#DetGenVehVin').val(element.vim);
            $('#DetGenVehPla').val(element.placas);
            $('#DetGenVehAnio').val(element.anio);
            $('#DetGenVehMar').val(element.marca.nombre);
        }
        $("#DetallesGeneralesForm").submit(async function(e){
            e.preventDefault();
            const thisform = $(this);
            const formData = new FormData(this);
            
            const rutaedit="{{route('DetallesGenerales.Update.Element')}}";
            let ruta=null;
            if($('#DetGenId').val()){
                ruta=rutaedit
            }
            const button=$("#DetGenSave");
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
                            timer: 2000
                        });
                        if (typeof window[DisparadorOtroModal] === "function") {
                                window[DisparadorOtroModal](response.Element);
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
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
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