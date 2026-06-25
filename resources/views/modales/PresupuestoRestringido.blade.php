<!-- Modal -->
<div class="modal fade" id="PresupuestoRestringidoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PresupuestoRestringidoTitle">Nuevo Presupuesto Taller</h5>
                <button type="button" class="btn-close PresupuestoResClose" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                     <div class="zdflex zdai-center">
                        <p class="h5 text-uppercase font-weight-bold border-bottom">Datos Generales de la Solicitud</p>
                     </div>
                     <form id="ButgetRestrigidoForm">
                        @csrf
                        <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                            <div class="select2conlabel zdw-20pct  zdrelative">
                                <label for="PreResUbi">Ubicacion</label>
                                <input required class="form-control" type="text" id="PreResUbi" name="PreResUbi">
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreResGasEnt">Gasolina Entrada<strong>*</strong></label>
                                <select id="PreResGasEnt" name="PreResGasEnt" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    <option value="0">LLENO</option>
                                    <option value="1">3/4</option>
                                    <option value="2">2/4</option>
                                    <option value="3">1/4</option>
                                    <option value="4">vacio</option>
                                </select>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreResGasEnt">Modulo<strong>*</strong></label>
                                <select id="ModuloCortana" name="ModuloCortana" class="form-control" required>
                                </select>
                            </div>
                            <div class=" select2conlabel zdw-30pct  zdrelative">
                                <label for="PreResKmEnt">Km De Ingreso<strong>*</strong></label>
                                <input required class="form-control" type="number" id="PreResKmEnt" name="PreResKmEnt">
                            </div>
                            
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreResFecEsp">Fecha Esperada<strong>*</strong></label>
                                <input required class="form-control" type="datetime-local" id="PreResFecEsp"name="PreResFecEsp">
                            </div>
                            
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="admintrasportes">Administrador de Trasportes <strong>*</strong></label>
                                <select id="PreResAdmTra" name="PreResAdmTra" required></select>
                                <button data-origin="UserTaller1" data-label="Nombre" data-select2='PreAdmTra' data-title="Nuevo Administrador de Trasportes" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreResTel">Telefono<strong>*</strong></label>
                                <input class="form-control" id="PreResTel" name="PreResTel" maxlength="10" pattern="\d{10}" type="tel" placeholder="Ej. 4443552266 " required>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative" >
                                <label for="PreResSer">Servicio</label>
                                <select class="form-control" id="PreResSer" name="PreResSer" required>
                                    <option value="">Seleccione el tipo de servicio</option>
                                    <option value="1">Preventivo</option>
                                    <option value="2">Correctivo</option>
                                    <option value="3">Ambos juntos</option>
                                </select>
                            </div>
                            
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="">Empresa<strong>*</strong></label>
                                <select id="PreResEmp" name="PreResEmp" required></select>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="">Clientes <strong>*</strong></label>
                                <select id="PreResCli"name="PreResCli" required></select>
                            </div>
                            <div class="select2conlabel zdw-30pct zdrelative">
                                <label for="">Vehiculo <span class="spanrelleno">#Econonomico - Placas</span><strong>*</strong></label>
                                <select  id="PreResVeh" name="PreResVeh" required></select>
                                <button class="btnin PreBtnNewOption NewVehiculoTaller" data-select2='PreVeh' id="PreResVehNew" type="button" >+</button>
                            </div>
                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreResIndCli">Reporte de Fallas</label>
                                <textarea class="zdh-100pct form-control" name="PreResIndCli" id="PreResIndCli"></textarea>
                            </div>
                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreResDesMO">Notas</label>
                                <textarea class="zdh-100pct form-control" name="PreResDesMO" id="PreResDesMO"></textarea>
                            </div>
                           
                        </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary PresupuestoResClose" >Cerrar</button>
                <button type="button" class="btn btn-primary" id="ButgetResButtonCreate">Crear Presupuesto</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#PresupuestoRestringidoModal');
        let DisparadorOtroModal=null;
        $(".NuevoPresupuestoRes").on('click',function(){
            $('#PresupuestoRestringidoTitle').text('Nuevo Presupuesto');
            OpenModalNew()
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisModal.modal('show');
        });
         $(".PresupuestoResClose").on('click',function(){
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
        
        function OpenModalNew(){
            $('#PresupuestoRestringidoModal input').not('input[name="_token"]').val('').removeAttr('data-id');
            $('#PresupuestoRestringidoModal textarea').val('');
            $('#PresupuestoRestringidoModal select').val('').trigger('change')
            //$('.PreBtnNewOption').attr('disabled',true); 
            $("#PresupuestoRestringidoModal").find(".error-message").remove();
        }
        $('#PreResEmp').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Companies')}}',
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
         $('#ModuloCortana').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent:  $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
            url: "{{route('Select2.Get.Modulos.Cortana')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term
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
                                id: item.value
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $('#PreResCli').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Customers')}}',
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
        $('#PreResVeh').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Vehicles')}}',
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
        $('#PreResVehTip').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Available.Types.Concepts')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: modulo,
                        contrato:contrato,
                        anio: anio,
                        zona:zona
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
        $('#PreResAdmTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo : 1
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
        $('#PreResJefPro').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo : 2
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
        $('#PreResTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoRestringidoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.User.RepairShop')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        tipo :3
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
        $('#ButgetResButtonCreate').on('click',function(){
            let thisform=$('#ButgetRestrigidoForm');
            if (thisform[0].checkValidity()) { 
                let data = new FormData(thisform[0]);

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Se Creara Un Nuevo Presupuesto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, Crear',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('2025.Presupuestos.Create.Layaut') }}', // Cambia esto por la URL del endpoint en tu backend
                            method: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire({
                                    title: 'Éxito',
                                    html: response.message,
                                    icon: 'success',
                                    timer: 2000
                                });
                                closethismodal();
                                executeSearchdata();
                            },
                            error: function (xhr) {
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
                            }
                        });
                    } 
                });
            }else {
                thisform[0].reportValidity(); // Fuerza la validación y resalta los campos requeridos vacíos
            }
        })
    });
</script>
@endpush