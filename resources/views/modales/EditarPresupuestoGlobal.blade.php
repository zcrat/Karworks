<!-- Modal -->
<div class="modal fade" id="PresupuestoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PresupuestoTitle">Editar Presupuesto</h5>
                <button type="button" class="btn-close PresupuestoClose" aria-label="Close">
                </button>
            </div>
            <div class="modal-body Pruebavisor">
                <div class="Visorizquierda2 ">
                    <div class="zdflex zdai-center">
                    <p class="h5 text-uppercase font-weight-bold border-bottom">Datos Generales de la Solicitud</p>
                    <button class="btn EditDetallesGenerales" id="DetGenEdit" data-disparador="RecargarDatos" type="button"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                    </div>
                    <form id="ButgetForm">
                        @csrf
                        <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                            <div class=" select2conlabel zdw-20pct  zdrelative">
                                <label for="PreOrdSer">Ord2.servicio</label>
                                <div class="iconoin">
                                <i id='PreSearchOrdSer' class="fa-solid fa-magnifying-glass zd-i-button"></i> 
                                <input required class="form-control" type="text" id="PreOrdSer" name="PreOrdSer">
                                </div>
                            </div>
                            <div class=" select2conlabel zdw-20pct  zdrelative">
                                <label for="PreFol">Folio</label>
                                    <input required class="form-control" type="text" id="PreFol" name="PreFol">
                            </div>
                            <div class=" select2conlabel zdw-20pct  zdrelative">
                                <label for="PreOrdSeg">Ord. Seguimiento</label>
                                <input required class="form-control" type="text" id="PreOrdSeg" name="PreOrdSeg">
                            </div>
                            <div class="select2conlabel zdw-20pct  zdrelative">
                                <label for="PreOrdOpc">Orden Opcional</label>
                                <input  class="form-control" type="text" id="PreOrdOpc" name="PreOrdOpc">
                            </div>
                            <div class="select2conlabel zdw-25pct  zdrelative">
                                <label for="PreUbi">Ubicacion</label>
                                <input required class="form-control" type="text" id="PreUbi" name="PreUbi">
                            </div>
                            @if (in_array(Auth::user()->id,[1,170,171,192,36]))
                                <div class="select2conlabel zdw-45pct  zdrelative " id="taller_div">
                                    <label for="">Taller</label>
                                    <div>
                                        <select id="taller_orden_servicio" name="taller_orden_servicio" class="talleres_select2"></select>
                                    </div>
                                </div>
                            @else
                                <div id="taller_div" hidden></div>
                            @endif
                            <div class="select2conlabel zdw-20pct  zdrelative">
                                <label for="PreGasEnt">Gasolina Entrada<strong>*</strong></label>
                                <select id="PreGasEnt" name="PreGasEnt" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    <option value="0">LLENO</option>
                                    <option value="1">3/4</option>
                                    <option value="2">2/4</option>
                                    <option value="3">1/4</option>
                                    <option value="4">vacio</option>
                                </select>
                            </div>
                            <div class=" select2conlabel zdw-20pct  zdrelative">
                                <label for="PreKmEnt">Km De Ingreso<strong>*</strong></label>
                                <input required class="form-control" type="number" id="PreKmEnt" name="PreKmEnt">
                            </div>
                            
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreFecEsp">Fecha Esperada<strong>*</strong></label>
                                <input required class="form-control" type="datetime-local" id="PreFecEsp"name="PreFecEsp">
                            </div>
                            
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="admintrasportes">Administrador de Trasportes <strong>*</strong></label>
                                <select id="PreAdmTra" name="PreAdmTra" required></select>
                                <button data-origin="UserTaller1" data-label="Nombre" data-select2='PreAdmTra' data-title="Nuevo Administrador de Trasportes" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreAdmTra">Jefe de Proceso<strong>*</strong></label>
                                <select id="PreJefPro" name="PreJefPro" required></select>
                                <button data-origin="UserTaller2" data-label="Nombre" data-select2='PreJefPro' data-title="Nuevo Jefe de Proceso" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreTel">Telefono<strong>*</strong></label>
                                <input class="form-control" id="PreTel" name="PreTel" maxlength="10" pattern="\d{10}" type="tel" placeholder="Ej. 4443552266 " required>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative">
                                <label for="PreTra">Trabajador<strong>*</strong></label></label>
                                <select id="PreTra"name="PreTra" required></select>
                                <button data-origin="UserTaller3" data-label="Nombre" data-select2='PreTra' data-title="Nuevo Trabajador" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                            </div>
                            <div class="select2conlabel zdw-30pct  zdrelative" >
                                <label for="PreSer">Servicio</label>
                                <select class="form-control" id="PreSer" name="PreSer" required>
                                    <option value="">Seleccione el tipo de servicio</option>
                                    <option value="1">Preventivo</option>
                                    <option value="2">Correctivo</option>
                                    <option value="3">Ambos juntos</option>
                                </select>
                            </div>
                            
                            <div class="select2conlabel zdw-45pct  zdrelative">
                                <label for="">Empresa<strong>*</strong></label>
                                <select id="PreEmp" name="PreEmp" required></select>
                                <button id="PreEmpNew" class="btnin PreBtnNewOption" disabled type="button">+</button>
                            </div>
                            <div class="select2conlabel zdw-45pct  zdrelative">
                                <label for="">Clientes <strong>*</strong></label>
                                <select id="PreCli"name="PreCli" required></select>
                                <button id="newcustomer"class="btnin NewCustomer" data-select2='PreCli' type="button">+</button>
                            </div>

                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreIndCli">Reporte de Fallas</label>
                                <textarea class="zdh-100pct form-control" name="PreIndCli" id="PreIndCli"></textarea>
                            </div>
                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreDesMO"> Notas</label>
                                <textarea class="zdh-100pct form-control" name="PreDesMO" id="PreDesMO"></textarea>
                            </div>
                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreGar">Garantia</label>
                                <textarea class="zdh-100pct form-control" name="PreGar" id="PreGar" placeholder='No Modificar si es el predeterminado' ></textarea>
                            </div>
                            <div class="zdw-45pct vaniflex zdfd-column">
                                <label for="PreObs">Tiempo de Entrega</label>
                                <textarea class="zdh-100pct form-control" name="PreObs" id="PreObs" placeholder='No Modificar si es el predeterminado'></textarea>
                            </div>
                        
                        </div>
                        <p class="h5 text-uppercase font-weight-bold border-bottom">Datos del Vehículo</p>
                        <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                            <div class="select2conlabel zdw-45pct zdrelative">
                                <label for="">Vehiculo <span class="spanrelleno">#Econonomico - Placas</span><strong>*</strong></label>
                                <select  id="PreVeh" name="PreVeh" required></select>
                                <button class="btnin PreBtnNewOption NewVehiculoTaller" data-select2='PreVeh' id="PreVehNew" type="button" hidden>+</button>
                                <button class="btnin EditVehiculoTaller" id="PreVehEdit" data-select2='PreVeh' type="button"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                            </div>
                            <div class="zdw-45pct ">
                                    <label for="PreVehTip" class='zdfz-r08'>Tipo <strong class='zdfz-r08'>*</strong></label>
                                    <select id="PreVehTip"name="PreVehTip" required></select>
                            </div>
                            <input type='hidden' id="PreId" name="PreId">
                            <div class=" selectconlabel zdmg-r02">
                                <label for="PreVehMod">Modelo</label>
                                <input required class="form-control" type="text" name='PreVehMod' id="PreVehMod">
                            </div>
                            <div class=" selectconlabel zdmg-r02">
                                <label for="PreVehVim">VIN</label>
                                <input  class="form-control" type="text"  id="PreVehVim" name="PreVehVim">
                            </div>
                            <div class=" selectconlabel zdmg-r02">
                                <label for="PreVehPla">Placas</label>
                                <input  class="form-control" type="text"  name='PreVehPla' id="PreVehPla">
                            </div>
                            <div class=" selectconlabel zdmg-r02">
                                <label for="PreVehAnio">Año</label>
                                <input  class="form-control" type="text" name='PreVehAnio' id="PreVehAnio">
                            </div>
                            <div class=" selectconlabel zdmg-r02">
                                <label for="PreVehMar">Marca</label>
                                <input  class="form-control" type="text" name='PreVehMar' id="PreVehMar">
                            </div>
                        </div>
                    </form>
                    <div id="PreTablaCon">
                        <div class="d-flex superior zdjc-between">
                            <div>
                            <button type="button" class="btn btn-primary NuevoConcepto" id="CreateNewConcept"><i class="fa-solid fa-circle-plus" ></i>&nbsp;Nuevo</button>
                            <button type="button" class="btn btn-success AddConcepto" data-function='reloadlista' id="PreAddCon"><i class="fa-solid fa-bars"></i>&nbsp;Agregar</button>
                            </div>
                            <button type="button" class="btn btn-primary" id="ButgetButtonUpdate"><i class="fa-solid fa-floppy-disk"></i></i>&nbsp;Guardar Cambios</button>
                        </div>
                        <p class="h5 text-uppercase font-weight-bold border-bottom">Diagnostico</p>
                        <div>
                            <table id="tablaconceptos" class="table table-sm  table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Codigo</th>
                                        <th>Cantidad</th>
                                        <th>Vehiculo</th>
                                        <th>Concepto</th>
                                        <th>Costo</th>
                                        <th>Precio</th>
                                        <th>Total</th>
                                        <th>Garantia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                            <div class="zdw-30pct vaniflex zdfd-column">
                                <div class="vaniflex zdfd-column"> 
                                    <table id='importestable'>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Costos</th>
                                                <th>Venta</th>
                                                <th>Utilidades</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="Visorderecha2" id="Visorderechapresupuesto">
                    <div class="zdflex zdmgb-r05  zdpd-r03 zdjc-between">
                        <button type="button" class='Cambiarpdf' data-type='3'>Acuse</button>
                        <button type="button" class='Cambiarpdf' data-type='1'>Venta</button>
                        <button type="button" class='Cambiarpdf' data-type='2'>Costo</button>
                    </div>
                    <iframe src="" frameborder="0" id='VisorpdfPresupuesto' class='Visorpdf2'></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary PresupuestoClose" >Cerrar</button>
                <button type="button" class="btn btn-primary" id="PreUpdCon">Actualizar Precios y Cantidades</button>
                <button type="button" class="btn btn-primary" id="ButgetButtonCreate" hidden>Crear Presupuesto</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        window.modulo = 0;
        window.contrato = 0;
        window.anio = 0;
        window.zona = 0;
        let typepresupuesto=1;
        let listaconceptos=[];
        let ModalFather=null;
        let ThisModal=$('#PresupuestoModal');
        let DisparadorOtroModal=null;
        $(".NuevoPresupuesto").on('click',function(){
            modulo = @json($modulo ?? 0);
            contrato = @json($contrato ?? 0);
            anio = @json($anio ?? 0);
            zona = @json($zona ?? 0);
                    
            $('#PresupuestoTitle').text('Nuevo Presupuesto');
            DisparadorOtroModal=$('#'+$(this).data('select2'))
            OpenModalNew()
            ocultarvisorpresupuesto();
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisModal.modal('show');
        });
        $(".EditarPresupuesto").on('click',async function(){
            const id = $(this).data('id');
            if(id){
                DisparadorOtroModal=$('#'+$(this).data('Disparador'))
                const isSuccess = await DetDataBudGet(id);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    typepresupuesto=1;
                    $('.Cambiarpdf').removeClass("active");
                    $('.Cambiarpdf[data-type="1"]').addClass("active");
                    mostrarvisorpresupuesto();
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
        window.OpenEditBudGetWitRequest=async function(id){
            if(id){
                const isSuccess = await DetDataBudGet(id);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    typepresupuesto=1;
                    $('.Cambiarpdf').removeClass("active");
                    $('.Cambiarpdf[data-type="1"]').addClass("active");
                    mostrarvisorpresupuesto();
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
        };
        $(".PresupuestoClose").on('click',function(){
            closethismodal()
        })
        $('#taller_orden_servicio').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                placeholder: 'Escribe para buscar...',
                dropdownParent:ThisModal,
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '/Zcrat/Select2/Get/talleres',
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
        async function DetDataBudGet(id) {
            try {
                const response = await $.ajax({
                    url: '{{ route('2025.Presupuestos.Get.Element') }}',
                    type: "get",
                    data: { id: id },
                });
                listaconceptos=response.recepcion.conceptos;
                
                modulo = response.recepcion.detalles_generales.modulo_id;
                contrato = response.recepcion.detalles_generales.contrato_id;
                anio = response.recepcion.detalles_generales.anio;
                zona = response.recepcion.detalles_generales.zona_id;

                $("#PresupuestoTitle").text('Editar Presupuesto '+response.recepcion.Folio)
                PreNotFound()
                $("#PreOrdSer").removeAttr('disabled');
                insertar_valores(response.recepcion.detalles_generales)
                llenar_campos(response.recepcion)
                $('.PreBtnNewOption').attr('disabled',false);  
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
        $('#PreEmp').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreCli').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreVeh').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreVehTip').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreAdmTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreJefPro').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
        $('#PreTra').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#PresupuestoModal"),
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
     window.eliminarconcepto = function(idconcepto) {
            
            Swal.fire({
                title: '¿Está seguro?',
                text: "eliminarás el concepto  del presupuesto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('2025.Presupuestos.Delete.Concepto') }}',
                        data: {
                            conceptoid: idconcepto,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({ html: `${response.message??'Eliminado Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            const id = parseInt(idconcepto);
                            const itemIndex = listaconceptos.findIndex((item) => item.id === id);
                            if (itemIndex !== -1) {
                                listaconceptos.splice(itemIndex, 1);
                                actualizarlista()
                                mostrarvisorpresupuesto();
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                            
                    })
                };
            });
        };

        window.RecargarDatos = function(Element) {
            insertar_valores(Element)
        }
        function llenarsubcamposvehiculo(element){
            $('#PreVehMod').val(element.modelo.nombre);
            $('#PreVehVim').val(element.vim);
            $('#PreVehPla').val(element.placas);
            $('#PreVehAnio').val(element.anio);
            $('#PreVehMar').val(element.marca.nombre);
            $('#PreVehEdit').data('id',element.id).removeAttr('hidden');
            $('#PreVehNew').attr('hidden',true);
        }
        function vaciarsubcamposvehiculo(){
            $('#PreVehMod').val('');
            $('#PreVehVim').val('');
            $('#PreVehPla').val('');
            $('#PreVehAnio').val('');
            $('#PreVehMar').val('');
            $('#PreVehEdit').removeData('id').attr('hidden',true);
            $('#PreVehNew').removeAttr('hidden');
        }
        function llenar_campos(element){
            // $('#PresupuestoModal input').not('input[name="_token"]').attr('disabled',true);
            // $('#PresupuestoModal textarea').attr('disabled',true);
            // $('#PresupuestoModal select').attr('disabled',true);
            $('.PreBtnNewOption').attr('disabled',true);  
            $("#PresupuestoModal").find(".error-message").remove();
            
            IdsInputsNotHidden=['DetGenEdit','PreTablaCon','PreUpdCon']
            IdsInputsHidden=['ButgetButtonCreate','taller_div']
            IdsInputsNotDisabled=['PreFol','PreObs','PreId','PreGar','PreDesMO',]

            IdsInputsHidden.forEach(input =>{
                $("#"+input ).attr('hidden',true)
            });
            IdsInputsNotHidden.forEach(input =>{
                $("#"+input ).removeAttr('hidden')
            });
            IdsInputsNotDisabled.forEach(input =>{
                $("#"+input ).removeAttr('disabled')
            });
            $('#PreFol').val(element.Folio);
            $("#PreObs").val(element.Observaciones);
            $("#PreDesMO").val(element.Mano_Obra_Descripcion);
            $("#PreGar").val(element.Garantia);
            $('#PreId').val(element.id);
            $('#PreSer').val(element.Tipo_id);
            $('#DetGenEdit').data('id',element.DetallesGenerales_id);
            $("#PreAddCon").attr("data-id", element.id);
            $("#PreUpdCon").attr("data-id", element.id);
            actualizarlista()
            $("#PresupuestoModal").modal("show");
        }
        function insertar_valores(element){
            $('#PreOrdSer').val(element.OrdenServicio);
            $('#PreOrdSeg').val(element.OrdenSeguimiento);
            $('#PreOrdOpc').val(element.Orden);
            $('#PreUbi').val(element.Ubicacion);
            $('#PreGasEnt').val(element.Gas_entrada);
            $('#PreFecEsp').val(element.Fecha_Esperada);
            $('#PreKmEnt').val(element.Kilometraje_entrada);
            $("#PreIndCli").val(element.Indicaciones_cliente); 
            $("#PreTel").val(element.Telefono);
            $("#CreateNewConcept").data('Vehiculo_Concepto',element.Tipo_Vehiculo_Concepto_id);
            $("#PreEmp").empty().append('<option value="' + element.empresa.id + '">' + element.empresa.nombre + '</option>');
            $("#PreCli").empty().append('<option value="' + element.customer.id + '">' + element.customer.nombre + '</option>');
            $("#PreAdmTra").empty().append('<option value="' + element.administrador_trasporte.id + '">' + element.administrador_trasporte.nombre + '</option>');
            $("#PreJefPro").empty().append('<option value="' + element.jefede_proceso.id + '">' + element.jefede_proceso.nombre + '</option>');
            $("#PreTra").empty().append('<option value="' + element.trabajador.id + '">' + element.trabajador.nombre + '</option>');
            $("#PreVeh").empty().append('<option value="' + element.vehiculo.id + '">' + element.vehiculo.no_economico + '-' + element.vehiculo.placas + '</option>');
            $("#PreVehTip").empty().append('<option value="' + element.tipo_vehiculo.id + '">' + element.tipo_vehiculo.nombre + '</option>');
            try {
                $("#taller_orden_servicio").empty().append('<option value="' + element.taller.id + '">' + element.taller.nombre + '</option>');
            } catch (error) {
                
            }
            llenarsubcamposvehiculo(element.vehiculo)
        }
        function OpenModalNew(){
            $('#PresupuestoModal input').not('input[name="_token"]').val('').attr('disabled',true).removeAttr('data-id');
            $('#PresupuestoModal textarea').val('').attr('disabled',true);
            $('#PresupuestoModal select').val('').trigger('change').attr('disabled',true)
            $('.PreBtnNewOption').attr('disabled',true); 
            $("#PresupuestoModal").find(".error-message").remove();

            IdsInputsHidden=['DetGenEdit','PreTablaCon','PreUpdCon','PreVehEdit']
            IdsInputsNotHidden=['ButgetButtonCreate','PreSer','PreVehTip','taller_div']
            IdsInputsNotDataId=['PreAddCon','PreUpdCon']
            IdsInputsNotDisabled=['PreOrdSer','PreSearchOrdSer']
            IdsInputsEmpty=['PreEmp','PreCli','PreVeh','PreAdmTra','PreJefPro','PreTra','PreVehTip']

            IdsInputsHidden.forEach(input =>{
                $("#"+input ).attr('hidden',true)
            });
            IdsInputsNotHidden.forEach(input =>{
                $("#"+input ).removeAttr('hidden')
            });
            IdsInputsNotDisabled.forEach(input =>{
                $("#"+input ).removeAttr('disabled')
            });
            IdsInputsNotDataId.forEach(input =>{
                $("#"+input ).removeAttr('data-id')
            });
            IdsInputsEmpty.forEach(input =>{
                $("#"+input ).empty()
            });
            listaconceptos=[];
            actualizarlista();
        }
        function PreFound(){
            $('#PresupuestoModal input').not('input[name="_token"]').attr('disabled',true);
            $('#PresupuestoModal select').attr('disabled',true);
            $('.PreBtnNewOption').attr('disabled',true);

            IdsInputsNotDisableds=['PreFol','PreSer','PreObs','PreGar','PreDesMO']
            IdsInputsNotDisableds.forEach(input =>{
                $("#"+input ).removeAttr('disabled')
            });
            IdsInputsAuto=['PreOrdSer','PreFol']
            IdsInputsAuto.forEach(input =>{
                $("#"+input ).attr('required',true);
            });
        }
        function PreNotFound(){
            $('#PresupuestoModal input').not('[name="_token"], [name="PreOrdSer"]').val('')
            $('#PresupuestoModal input').not('input[name="_token"]').removeAttr('disabled');
            $('#PresupuestoModal textarea').removeAttr('disabled');
            $('#PresupuestoModal select').val('').trigger('change').removeAttr('disabled');
            IdsInputsEmpty=['PreEmp','PreCli','PreVeh','PreAdmTra','PreJefPro','PreTra','PreVehTip']
            IdsInputsEmpty.forEach(input =>{
                $("#"+input ).empty()
            });
            IdsInputsAuto=['PreOrdSer','PreFol']
            IdsInputsAuto.forEach(input =>{
                $("#"+input ).attr('disabled',true).removeAttr('required');
            });
            $('.PreBtnNewOption').removeAttr('disabled')
        }
        $("#PreSearchOrdSer").on("click", function(){
            if($('#PreId').val()){
                Swal.fire({
                        icon:'warning',
                        title:'Ya se Esta Editando Un Presupuesto',
                        showConfirmButton: false,
                        timer: 1300,
                    });  
            }else{
                let ordserv= $('#PreOrdSer').val();
                if(ordserv && ordserv.length >=4 ){
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('2025.Presupuestos.Get.GeneralData.Element') }}',
                        data:{
                            ordservicio: ordserv,
                        },
                        success: function(response) {
                            if(response.element){
                                PreFound()
                                insertar_valores(response.element)
                            }else{
                                PreNotFound()
                                Swal.fire({
                                icon:'warning',
                                title:'La Orden De Servicio No Exite, Debe de Ingresar Los Datos Manualmente',
                                showConfirmButton: false,
                                timer: 1400,});
                            }
                        },
                        error: function(xhr, status, error) {
                        console.log(xhr);
                        }
                    });
                }else{
                    if(!ordserv){
                        PreNotFound()
                    }else{
                        Swal.fire({
                            icon:'error',
                            title:'Ingrese Una Orden De Servicio Valida',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                    
                }
            }
        });
        $("#PreVeh").on("change", function(){
            let id=$(this).val()
            if(id){
                $.ajax({
                type: 'GET',
                url: '/Zcrat/Vehiculo/Get/Element',
                data:{
                    id: id,
                },
                success: function(response) {
                    llenarsubcamposvehiculo(response.element)
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon:'error',
                        title:'Vehiculo No Dispoble, Intentelo Mas Tarde',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    $("#PreVeh").val('').trigger('change').removeAttr('disabled');
                }
            }); 
            }else{
                vaciarsubcamposvehiculo() }
        })
        window.ToggleGarantia=async function(id,garantia,dictamen){             
             
             if(garantia && dictamen){
                    Swal.fire({
                        title: 'Dictamen',
                        text: dictamen,
                        icon: 'warning',
                        showConfirmButton: false
                    });
            }else if(garantia){
                    let userId = {{ auth()->id() }}
                    if(userId == 1 || userId == 170 ){
                        ThisModal.modal('hide');
                        const { value: formValues } = await Swal.fire({
                            title: "Cancelar Garantia",
                            html: `
                                <div>
                                    <label> Dictamen</label>
                                    <textarea id="dictamen" name="dictamen"  class='form-control'></textarea>
                                </div>
                                
                            `,
                            focusConfirm: false,
                            confirmButtonText: "Confirmar",
                            preConfirm: () => {
                                const dictamen = document.getElementById("dictamen").value;

                                if (!dictamen) {
                                    Swal.showValidationMessage("El dictamen Es Obligatorio");
                                    return false;
                                }

                                return [dictamen];
                            }
                        });
                        if(formValues){
                            $.ajax({
                                type: 'PUT',
                                url: '{{ route('2025.Presupuestos.Delete.Garantia') }}',
                                data: {
                                    id: id,
                                    dictamen:formValues[0],
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire({ html: `${response.message??'Cancelada Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                                    ThisModal.modal('show');
                                    reloadlista();
                                },
                                error: function(xhr, status, error) {
                                    ThisModal.modal('show');
                                    Swal.fire({
                                        title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                        html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                        icon: 'error'
                                    });
                                }
                                    
                            })
                        }else{
                            ThisModal.modal('show');

                        }
                } 
            } 
            
        };
        window.reloadlista = function(){
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Presupuestos.Get.Conceptos') }}',
                data:{
                    presupuesto: $('#PreId').val(),
                },
                success: function(response) {
                    listaconceptos=response.conceptos;
                    console.log(listaconceptos);
                    actualizarlista()
                    mostrarvisorpresupuesto();
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            }); 
        };
        function actualizarlista(){
            $('#tablaconceptos tbody').empty();
            console.log(listaconceptos);
            $.each(listaconceptos, function(index, element) {
                let row = $('<tr>'); 

                const hasgarantia= element.garantia ;
                const garantiavalid = hasgarantia && element.dictamen == null ;      
                row.append('<td><div class="Datatable-content">' + (element.datos_concepto.id ? element.datos_concepto.id : "Sin Codigo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">' + (element.datos_concepto.num ? element.datos_concepto.num : "Sin Codigo" ) + '</div></td>');
                if(garantiavalid){
                    row.append('<td><div class="Datatable-content">' + (element.Cantidad) + '</div></td>');
                }else{
                    row.append('<td><div class="Datatable-content"><input type="number" class="cantidaddiagnostico zdw-r4" data-id="'+element.id+'"  value='+element.Cantidad+' ></input></div></td>');

                }
                row.append('<td><div class="Datatable-content">' + (element.datos_concepto.tipo_vehiculo ? element.datos_concepto.tipo_vehiculo.nombre : "Sin descripcion" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">' + (element.datos_concepto ? element.datos_concepto.descripcion : "Sin descripcion" ) + '</div></td>');
                if(garantiavalid){
                    row.append('<td><div class="Datatable-content">' + (element.Costo) + '</div></td>');
                    row.append('<td><div class="Datatable-content">' + (element.Venta) + '</div></td>');
                }else{
                    row.append('<td><div class="Datatable-content"><input type="number" class="preciodiagnostico zdw-r4" data-id="'+element.id+'" value='+element.Costo+'></input></div></div></td>');
                    row.append('<td><div class="Datatable-content"><input type="number" class="preciodiagnosticofinal zdw-r4" data-id="'+element.id+'" value='+element.Venta+'></input></div></div></td>');
                }
                row.append('<td><label data-id="' + element.id + '" class="subtotaldiagnostico ">' + Number((element.Cantidad * element.Venta)).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</label></td>');
                row.append('<td>'+ (element.garantia?('<button  onclick="ToggleGarantia('+element.id+','+element.garantia+',\''+element.dictamen+'\')">'+(garantiavalid ? 'Aplica' : 'No Aplica' )+'</button>' ): '<label>-</label>' )+'</td>');
                row.append('<td><button class="btn btn-danger" onclick="eliminarconcepto(\''+(element.id ? element.id : 1 )+'\',\''+(element.concepto ? element.concepto.descripcion : "Sin descripcion" )+'\')"><i class="fa-solid fa-trash"></i></button></td>');
                $('#tablaconceptos tbody').append(row);
            });
            console.log(listaconceptos);
            let subtotalventa = 0;
            let subtotalcosto = 0;
            listaconceptos.forEach(item => {
                const garantiavalid = item.garantia && item.dictamen == null ; 
                if(!garantiavalid){
                    subtotalcosto+=item.Cantidad * item.Costo;
                    subtotalventa += item.Cantidad * item.Venta;
                }
            });
            let ivaventa=subtotalventa*0.16;
            let ivacosto=subtotalcosto*0.16;
            let totalventa=subtotalventa+ivaventa;
            let totalcosto=subtotalcosto+ivacosto;
            $('#importestable tbody').empty();
            let row = $('<tr>'); 
            row.append('<td><div class="Datatable-content">Subtotal: </div></td>');
            row.append('<td><div class="Datatable-content">' +Number(subtotalcosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row.append('<td><div class="Datatable-content">' +Number(subtotalventa).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row.append('<td><div class="Datatable-content">' +Number(subtotalventa-subtotalcosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            let row1 = $('<tr>'); 
            row1.append('<td><div class="Datatable-content">IVA: </div></td>');
            row1.append('<td><div class="Datatable-content">' +Number(ivacosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row1.append('<td><div class="Datatable-content">' +Number(ivaventa).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row1.append('<td><div class="Datatable-content">' +Number(ivaventa-ivacosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            let row2 = $('<tr>'); 
            row2.append('<td><div class="Datatable-content">Total: </div></td>');
            row2.append('<td><div class="Datatable-content">' +Number(totalcosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row2.append('<td><div class="Datatable-content">' +Number(totalventa).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            row2.append('<td><div class="Datatable-content">' +Number(totalventa-totalcosto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+ '</div></td>');
            $('#importestable tbody').append(row);
            $('#importestable tbody').append(row1);
            $('#importestable tbody').append(row2);
        }
        $(document).on('change','.preciodiagnostico,.preciodiagnosticofinal, .cantidaddiagnostico', function () {
                const val = parseFloat($(this).val()) || 0; // Obtener el valor del input
                const id = parseInt($(this).data('id')); // Obtener el ID del data-id
                const itemIndex = listaconceptos.findIndex((item) => item.id === id); // Encontrar el índice
                console.log(id);
                console.log(itemIndex);
                if (itemIndex !== -1) {
                    // Actualizar el valor correspondiente en el array
                    if ($(this).hasClass('preciodiagnostico')) {
                        listaconceptos[itemIndex].Costo = val;
                    } else if ($(this).hasClass('preciodiagnosticofinal')) {
                        listaconceptos[itemIndex].Venta = val;
                    
                    } else if ($(this).hasClass('cantidaddiagnostico')) {
                        listaconceptos[itemIndex].Cantidad = val;
                    }
                    actualizarlista();
                                
                }
        });
        $('#PreUpdCon').on('click',function(){
            Swal.fire({
                title: '¿Está seguro?',
                text: "Se Actualizara El Listado De Conceptos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Actualizar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('2025.Presupuestos.update.Conceptos') }}', // Cambia esto por la URL del endpoint en tu backend
                        method: 'POST',
                        data: {
                            productos:listaconceptos,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $message=response.message ?? "Exito Actualizado"
                            Swal.fire({
                                icon: "success",
                                title: $message,
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        mostrarvisorpresupuesto();
                        },
                        error: function (error) {
                            Swal.fire({
                                icon:'error',
                                title:'Ocurrio un problema al Actualizar El Listado De Conceptos.',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        }
                    });
                } 
            });
        })
        $('#ButgetButtonCreate').on('click',function(){
            let thisform=$('#ButgetForm');
            if (thisform[0].checkValidity()) { 
                let data = new FormData(thisform[0]);
                data.append('modulo', modulo);
                data.append('contrato', contrato);
                data.append('anio', anio);
                data.append('zona', zona);
                data.append('PreIndCli', $('#PreIndCli').val());
                thisform.find('input[name], select[name]').not('input[name="_token"] , input[name="PreId"]').each(function() {
                    data.append($(this).attr('name'), $(this).val());
                });

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
                            url: '{{ route('2025.Presupuestos.Create') }}', // Cambia esto por la URL del endpoint en tu backend
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
        $('#ButgetButtonUpdate').on('click',function(){
            let thisform=$('#ButgetForm');
            if (thisform[0].checkValidity()) { 
                let data = new FormData(thisform[0]);
            Swal.fire({
                title: '¿Está seguro?',
                text: "Se Actualizara Los Datos Del Presupuesto",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Actualizar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('2025.Presupuestos.Update') }}', // Cambia esto por la URL del endpoint en tu backend
                        method: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $message=response.message ?? "Exito Actualizado"
                            Swal.fire({
                                icon: "success",
                                title: $message,
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            executeSearchdata();
                            mostrarvisorpresupuesto();
                        },
                        error: function (error) {
                            console.log(error)
                             if (error.status === 422) {
                                    thisform.find(".error-message").remove();
                                    let errors = error.responseJSON.errors;
                                    for (let field in errors) {
                                        let input = thisform.find(`[name="${field}"]`);
                                        let errorMessage =
                                            `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                                        input.after(errorMessage);
                                    }
                            }else{
                                Swal.fire({
                                icon:'error',
                                title:(error.responseJSON? error.responseJSON.message??'Conctate al de Sistemas, Ocurrio un problema en la respuesta':'Ocurrio un problema al Actualizar El Presupuesto'),
                                showConfirmButton: false,
                                timer: 1000,
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
        mostrarvisorpresupuesto = async function () {
            $('#Visorderechapresupuesto').removeAttr('hidden');
            let presupuestoID = document.getElementById("PreId").value;
            let pdfUrl = "/Zcrat/Presupuestos/PDF/Venta/" + presupuestoID + "#view=FitPage";
            console.log(typepresupuesto);
            if(typepresupuesto==2){
                pdfUrl = "/Zcrat/Presupuestos/PDF/Costo/" + presupuestoID + "#view=FitPage";
            }
            if(typepresupuesto==3){
                pdfUrl = "/Zcrat/Presupuestos/PDF/Acuse/" + presupuestoID + "#view=FitPage";
            }
            const visor = $('#VisorpdfPresupuesto');
            visor.removeAttr('src'); // Limpiar el visor antes de asignar un nuevo src
                setTimeout(() => {
            visor.attr('src', pdfUrl);
        }, 100);}
        ocultarvisorpresupuesto = async function () {
            const visor = $('#VisorpdfPresupuesto');
            visor.removeAttr('src');
            $('#Visorderechapresupuesto').attr('hidden', true);
        }
        $('.Cambiarpdf').on('click', function() {
            $('.Cambiarpdf').removeClass("active");
            $(this).addClass("active");
            typepresupuesto = $(this).attr('data-type');
            mostrarvisorpresupuesto();
        });
});
</script>
@endpush