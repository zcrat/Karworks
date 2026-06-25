<!-- Modal -->
<div class="modal fade" id="recepcionservicio" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="recepcionservicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recepcionservicioLabel">Nueva Recepcion Taller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="serviciorecepcionform">
                @csrf
                <div class="modal-body">
                    <!-- Datos del Vehículo -->
                    <p class="h5 text-uppercase font-weight-bold border-bottom">Datos del Vehículo</p>
                    <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                        
                        <div class="select2conlabel zdw-100pct zdrelative">
                            <label for="">Vehiculo <span class="spanrelleno">#Econonomico - Placas</span><strong>*</strong></label>
                            <select  id="vehiculopresupuesto" name="vehiculo" required></select>
                            <button class="btnin EditVehiculoTaller" id="reditcar" data-select2='vehiculopresupuesto' type="button"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                        </div>
                                
                        <input type='hidden' id="detallesgenerales_id" name="detallesgenerales_id">
            
                        <div class="zdw-45pct zdhidden">
                                <label for="tipo" class='zdfz-r08'>Tipo <strong class='zdfz-r08'>*</strong></label>
                                <select id="tipovehiculo2"name="tipovehiculo" required></select>
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="rsmodelo">Modelo</label>
                            <input required class="form-control" type="text" disabled id="rsmodelo">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="rsvin">VIN</label>
                            <input  class="form-control" type="text" disabled id="rsvin">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="rsplacas">Placas</label>
                            <input  class="form-control" type="text" disabled id="rsplacas">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="rsAño">Año</label>
                            <input  class="form-control" type="text" disabled id="rsAño">
                        </div>
                        <div class=" selectconlabel zdmg-r02">
                            <label for="rsMarca">Marca</label>
                            <input  class="form-control" type="text" disabled id="rsMarca">
                        </div>
                    </div>

                    <!-- Datos Generales de la Solicitud -->
                    <p class="h5 text-uppercase font-weight-bold border-bottom">Datos Generales de la Solicitud</p>
                    <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsFolio">Folio</label>
                            <input required class="form-control" type="text" id="rsFolio" name="rsFolio">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsordenseg">Ord. Seguimiento</label>
                            <input required class="form-control" type="text" id="rsordenseg" name="rsordenseg">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsnorden">Ord.servicio</label>
                            <input required class="form-control" type="text" id="rsnorden" name="rsnorden">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsubicacion">Ubicacion</label>
                            <input required class="form-control" type="text" id="rsubicacion" name="rsubicacion">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsFecha_Alta">Fecha Alta<strong>*</strong></label>
                            <input required class="form-control" type="datetime-local" id="rsFecha_Alta"
                                name="rsFecha_Alta">
                        </div>
                        <div class=" select2conlabel zdw-45pct  zdrelative">
                            <label for="rsKm_De_Ingreso">Km De Ingreso<strong>*</strong></label>
                            <input required class="form-control" type="number" id="rsKm_De_Ingreso" name="rsKm_De_Ingreso">
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="">Empresa<strong>*</strong></label>
                            <select id="empresasrecepcion2" name="empresasrecepcion" required></select>
                            <button id="newempresas2" class="btnin"disabled type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="">Clientes <strong>*</strong></label>
                            <select id="clientesrecepcion2"name="clientesrecepcion" required></select>
                            <button id="newcustomer2" class="btnin newcustomer"disabled type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="admintrasportes">Administrador de Trasportes <strong>*</strong></label></label>
                            <select id="admintrasportedemo2" name="admintrasporte" required></select>
                            <button data-origin="UserTaller1" data-label="Nombre" data-select2='admintrasportedemo2' data-title="Nuevo Administrador de Trasportes" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="jefedelproceso">Jefe de Proceso<strong>*</strong></label></label>
                            <select id="jefedelprocesodemo2"name="jefedelproceso" required></select>
                            <button data-origin="UserTaller2" data-label="Nombre" data-select2='jefedelprocesodemo2' data-title="Nuevo Jefe de Proceso" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                        </div>
                        
                        <div class="select2conlabel zdw-45pct  zdrelative">
                            <label for="Trabajador">Trabajador<strong>*</strong></label></label>
                            <select id="Trabajadordemo2"name="Trabajador" required></select>
                            <button data-origin="UserTaller3" data-label="Nombre" data-select2='Trabajadordemo2' data-title="Nuevo Trabajador" class="btnin NewElementOneAttribute PreBtnNewOption" type="button">+</button>
                        </div>
                        <div class="select2conlabel zdw-45pct  zdrelative">
                                <label for="rsServicio">Servicio</label>
                                <select class="form-control" id="rsServicio" name="rsServicio" required>
                                    <option value="">Seleccione el tipo de servicio</option>
                                    <option value="1">Preventivo</option>
                                    <option value="2">Correctivo</option>
                                    <option value="3">Ambos juntos</option>
                                </select>
                            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rsObservaciones">Descripcion Mano De Obra</label>
                                <textarea class="form-control" placeholder="Notas" cols="30" rows="5" id="rsObservaciones"
                                    name="rsObservaciones"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="guardarDatos">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(function() {
            $('#empresasrecepcion2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
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
            $('#clientesrecepcion2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
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
            $('#vehiculopresupuesto').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
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
            $('#tipovehiculo2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
                placeholder: 'Escribe para buscar...',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{route('Select2.Get.Available.Types.Concepts')}}',
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
            $('#admintrasportedemo2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
                placeholder: 'Escribe para buscar...',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{route('Select2.Get.User.RepairShop')}}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            term: params.term,
                            tipo :1
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
            $('#jefedelprocesodemo2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
                placeholder: 'Escribe para buscar...',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{route('Select2.Get.User.RepairShop')}}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            term: params.term,
                            tipo :2
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
            $('#Trabajadordemo2').select2({
                language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
                dropdownParent: $("#recepcionservicio"),
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
        });
    </script>
@endpush
