<!-- Modal -->
<div class="modal fade" id="facturarmodal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-60pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Facturar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <!-- Datos del Vehículo -->
                <div class="vaniflex zditemscenter zdpd-r05 zdjc-between">  
                    <div class='zdw-100pct'>
                        <label class="zdmgr-r02">Empresas:</label>
                        <select  id="empresasfactura" required>
                            <option value="">Todas</option>
                        </select>
                    </div>
                    <div class='zdw-100pct vaniflex zdfd-column' id="numauto-div">
                        <label class="zdmgr-r02">Autorizacion</label>
                        <input class='form-control' type="text" id='NUM-AUTO'>
                    </div> 
                                
                </div>
                <div class="col-md-12">
                <input type="hidden" id="emisorrfc">
                <button id="Emisor3" class="elimarestilosboton emisor"><img src="/img/logo_cfb_button.png" alt="" class="ajustaraltura"></button>
                <button id="Emisor4" class="elimarestilosboton emisor"><img src="/img/logo_akumas_button.png" alt="" class="ajustaraltura"></button>
                <button id="Emisor5" class="elimarestilosboton emisor"><img src="/img/logo_kmg_button.jpeg" alt="" class="ajustaraltura"></button>
                <button id="Emisor6" class="elimarestilosboton emisor"><img src="/img/karworks_logotipo.jpeg" alt="" class="ajustaraltura"></button>
            </div>
                <div class="vaniflex zdmg-r05 zdjc-between zdfw-w">
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="Economico">Tipo de Comprobante</label>
                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                            <option selected value="I">I - Factura</option> 
                            <option value="E">E - Nota de credito</option> 
                            <option value="N">N - Nomina</option>
                        </select>
                    </div> 
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="rsmodelo">Uso de CFDI</label>
                        <select name="uso_cfdi" id="uso_cfdi" class="form-control"><option value="G01">G01 - Adquisicion de mercancias</option>
                            <option value="G02">G02 - Devoluciones, descuentos o bonificaciones</option>
                            <option value="G03">G03 - Gastos en general</option>
                            <option value="I01">I01 - Construcciones</option>
                            <option value="I02">I02 - Mobilario y equipo de oficina por inversiones</option>
                            <option value="I03">I03 - Equipo de transporte</option>
                            <option value="I04">I04 - Equipo de computo y accesorios</option>
                            <option value="I05">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                            <option value="I06">I06 - Comunicaciones telefonicas</option>
                            <option value="I07">I07 - Comunicaciones satelitales</option>
                            <option value="I08">I08 - Otra maquinaria y equipo</option>
                            <option value="D01">D01 - Honorarios medicos, dentales y gastos hospitalarios.</option>
                            <option value="D02">D02 - Gastos medicos por incapacidad o discapacidad</option>
                            <option value="D03">D03 - Gastos funerales.</option>
                            <option value="D04">D04 - Donativos.</option>
                            <option value="D05">D05 - Intereses reales efectivamente pagados por creditos hipotecarios (casa habitaci?n)</option>
                            <option value="D06">D06 - Aportaciones voluntarias al SAR.</option>
                            <option value="D07">D07 - Primas por seguros de gastos medicos.</option>
                            <option value="D08">D08 - Gastos de transportacion escolar obligatoria.</option>
                            <option value="D09">D09 - Depositos en cuentas para el ahorro, primas que tengan como base planes de pension</option>
                            <option value="D10">D10 - Pagos por servicios educativos (colegiaturas)</option>
                            <option value="P01">P01 - Por definir</option>
                        </select>
                    </div> 
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="rsvin">Tipo de Impuesto Local</label>
                        <select name="tipo_impuesto_local" id="tipo_impuesto_local" class="form-control"><option value="1">Sin Impuesto Local</option>
                            <option value="2">Inspeccion, Vigilancia, Control</option>
                            <option value="3">Impuesto Cedular</option>
                            <option value="4">Impuesto Sobre Remuneraciones al Trabajo Personal No Subordinado (RTP)</option>
                            <option value="5">Impuesto Sobre Nomina</option>
                        </select>
                    </div> 
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="rsplacas">Moneda</label>
                        <select name="moneda" id="moneda"class="form-control">
                            <option value="MXN">MXN - PESOS</option>
                            <option value="USD">USD - DOLARES</option>
                            <option value="EUR">EUR - EUROS</option>
                        </select>
                    </div> 
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="rsAño">Forma de Pago</label>
                        <select name="fpago" id="fpago"class="form-control">
                            <option value="01">01 - Efectivo</option>
                            <option value="02">02 - Cheque nominativo</option>
                            <option value="03">03 - Transferencia electronica de fondos</option>
                            <option value="04">04 - Tarjeta de credito</option>
                            <option value="05">05 - Monedero Electronico</option>
                            <option value="06">06 - Dinero electronico</option>
                            <option value="08">08 - Vales de despensa</option>
                            <option value="12">12 - Dacion en pago</option>
                            <option value="13">13 - Pago por subrogacion</option>
                            <option value="14">14 - Pago por consignacion</option>
                            <option value="15">15 - Condonacion</option>
                            <option value="17">17 - Compensacion</option>
                            <option value="23">23 - Novacion</option>
                            <option value="24">24 - Confusion</option>
                            <option value="25">25 - Remision de deuda</option>
                            <option value="26">26 - Prescripcion o caducidad</option>
                            <option value="27">27 - A satisfaccion del acredor</option>
                            <option value="28">28 - Tarjeta de debito</option>
                            <option value="29">29 - Tarjeta de servicios</option>
                            <option value="99">99 - Por definir.</option>
                        </select>
                    </div> 
                    <div class=" selectconlabel zdw-30pct zdmg-r02">
                        <label for="rsKilometraje">Metodo de Pago</label>
                        <select name="mpago" id="mpago" class="form-control">
                            <option value="PUE">PUE - Pago en una sola exhibicion</option> 
                            <option value="PPD">PPD - Pago en parcialidades o diferidos</option>
                        </select>
                    </div> 
                </div>

            <div>
                <table id="tablaconceptosfactura" class="table table-sm  table-striped">
                    <thead>
                    <tr><th>Articulo</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
                <div class="vaniflex zdjc-end">
                    <div class="vaniflex zdfd-column totalizacion"> 
                        <label class="" id="subtotalfactura" for="">Subtotal: $0</label>
                        <label class="" id="ivafactura" for="">Iva:      $0</label>
                        <label class="" id="totalfactura" for="">Total:    $0</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary unafactura" id="timbrarfactura" hidden>Timbrar</button>
                <button type="button" class="btn btn-primary variasfacturas" id="timbrarfacturas" hidden >Timbrar</button>
                <button type="button" class="btn btn-success variasfacturas" id="guardarfacturas" hidden>Guardar</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let DisparadorOtroModal=null;
        let emisor='';
        let modulo='';
        let anio='';
        let contrato='';
        
        $('#empresasfactura').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#facturarmodal"),
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
        $('.emisor').on('click',function(){
            $('.rfcactive').removeClass('rfcactive');
            $(this).addClass('rfcactive');
            $('#emisorrfc').val($(this).attr('id'));
            // if($(this).attr('id') == 'Emisor3'){
            //     $('#numauto-div').val('').attr('hidden',true);
            // }else{
            //     $('#numauto-div').val('').removeAttr('hidden');
            // }
        })
        $('#timbrarfactura').on('click', function (){
            button = $(this);
            let emisor= $('#emisorrfc').val();
            if(emisor == 'Emisor3'){
                numauto = null;
            }else{
                numauto =$('#NUM-AUTO').val() ;
            }
            let presupuesto=button.attr('data-id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Facturar',
                cancelButtonText: 'No, Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.attr('disabled', true);
                        $.ajax({
                            url: '{{route('2025.Facturar.Presuspuestos.Unitario')}}',
                            type: "post",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                empresa: $('#empresasfactura').val(),
                                tipo_comprobante: $('#tipo_comprobante').val(),
                                uso_cfdi: $('#uso_cfdi').val(),
                                moneda: $('#moneda').val(),
                                fpago: $('#fpago').val(),
                                mpago: $('#mpago').val(),
                                numauto: numauto,
                                emisor: emisor,
                                presupuesto: presupuesto,
                            },
                            success: function(response) {
                                var respuesta = response.id;
                                const mensaje=response.success;
                                Swal.fire({ html: `${mensaje}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                                $('#facturarmodal').modal('hide')
                                if(DisparadorOtroModal){
                                if (typeof window[DisparadorOtroModal] === "function") {
                                    window[DisparadorOtroModal]();
                                }
                                DisparadorOtroModal=null;
                            }else{
                                executeSearchdata();
                            }
                                button.attr('disabled', false);
                            },
                            error: function(xhr) {
                                button.attr('disabled', false);
                                let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                Swal.fire({ title: 'Error: '+ (xhr.status?? '000'), 
                                    html: `${errorMessage}<br>Detalles del error: ${xhr.responseJSON?.message ?? 'Desconocidos' }`, icon: 'error'});

                            }
                        })
                    }
            });
        });
        $('#timbrarfacturas').on('click', function (){
            button = $(this);
            let emisor= $('#emisorrfc').val();
            if(emisor == 'Emisor3'){
                numauto = null;
            }else{
                numauto =$('#NUM-AUTO').val() ;
            }
            let data = new FormData();
            let presupuestos = button.attr('data-id');
            data.append('empresa', $('#empresasfactura').val());
            data.append('tipo_impuesto_local', $('#tipo_impuesto_local').val());
            data.append('tipo_comprobante', $('#tipo_comprobante').val());
            data.append('uso_cfdi', $('#uso_cfdi').val());
            data.append('moneda', $('#moneda').val());
            data.append('fpago', $('#fpago').val());
            data.append('mpago', $('#mpago').val());
            data.append('emisor', emisor);
            data.append('contrato', contrato);
            data.append('numauto', numauto);
            data.append('_token', "{{ csrf_token() }}");
            presupuestos = presupuestos.split(",").map(Number)
            presupuestos.forEach(presupuesto => {
                data.append('presupuestos[]', presupuesto);
            });

            Swal.fire({
                title: '¿Estás seguro?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Facturar Presupuestos',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.attr('disabled', true);
                    $.ajax({
                        url: '{{route('2025.Facturar.Presuspuestos.Conjunto')}}',
                        type: "post",
                        data : data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({ html: `${response.message??'Facturados Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            $('#facturarmodal').modal('hide')
                            $('#prefacturasdiv').attr('hidden', true);
                            $('#dataupload').removeAttr('hidden');
                            if(DisparadorOtroModal){
                                if (typeof window[DisparadorOtroModal] === "function") {
                                    window[DisparadorOtroModal]();
                                }
                                DisparadorOtroModal=null;
                            }else{
                                executeSearchdata();
                            }
                            button.attr('disabled', false);
                        },
                        error: function(xhr) {
                            button.attr('disabled', false);
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                    })
                }
            });
        });
        $('#guardarfacturas').on('click', function (){
            let data = new FormData();
            let presupuestos = $(this).attr('data-id');
            data.append('empresa_id', $('#empresasfactura').val());
            data.append('tipo_impuesto_local', $('#tipo_impuesto_local').val());
            data.append('tipo_comprobante', $('#tipo_comprobante').val());
            data.append('uso_cfdi', $('#uso_cfdi').val());
            data.append('moneda', $('#moneda').val());
            data.append('fpago', $('#fpago').val());
            data.append('mpago', $('#mpago').val());
            data.append('modulo', modulo);
            data.append('anio', anio);
            data.append('contrato', contrato);
            data.append('zona', zona);
            data.append('_token', "{{ csrf_token() }}");
            presupuestos = presupuestos.split(",").map(Number)
            presupuestos.forEach(presupuesto => {
                data.append('presupuestos[]', presupuesto);
            });

            Swal.fire({
                title: '¿Estás seguro?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Guardar Facturas',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('2025.Facturar.Create.Prefactura')}}',
                        type: "post",
                        data : data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({ html: `${response.message??'Guardada Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            $('#facturarmodal').modal('hide')
                            
                            if(DisparadorOtroModal){
                                if (typeof window[DisparadorOtroModal] === "function") {
                                    window[DisparadorOtroModal]();
                                }
                                DisparadorOtroModal=null;
                            }else{
                                executeSearchdata();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                    })
                }
            });
        });
        window.FacturarUna = function (id) {
            $('.rfcactive').removeClass('rfcactive');
            const presupuesto = id;
            DisparadorOtroModal=null;

            $.ajax({
            type: 'GET',
            url: '{{ route('2025.Presupuestos.Get.Conceptos') }}',
            data:{
                presupuesto: presupuesto,
            },
            success: function(response) {
                modulo=response.data.moduloId;
                anio=response.data.anio;
                contrato=response.data.contratoId;
                zona=response.data.zonaId;
                listaconceptos=response.conceptos;
                let emisor = modulo;
                if(emisor == 4){
                    emisor=3;
                }
                if(emisor == 5){
                    emisor=4;
                }
                // if(emisor == 3){
                //     $('#numauto-div').val('').attr('hidden',true);
                // }else{
                //     $('#numauto-div').val('').removeAttr('hidden');
                // }
                const idemisor = 'Emisor' + emisor;

                $('#'+idemisor).addClass('rfcactive');
                $('#emisorrfc').val(idemisor);
                $('#tablaconceptosfactura thead').empty();
                $('#tablaconceptosfactura thead').append('<tr><th>Articulo</th><th>Precio</th><thCantidad</th><th>Total</th></tr>');
                $('#tablaconceptosfactura tbody').empty();
                $.each(listaconceptos, function(index, element) {
                    let row = $('<tr>'); 
                    row.append('<td><div class="Datatable-content">' + (element.datos_concepto.descripcion ) + '</div></td>');
                    row.append('<td><div class="Datatable-content">' + (element.Venta ) + '</div></td>');
                    row.append('<td><div class="Datatable-content">' + (element.Cantidad ) + '</div></td>');
                    row.append('<td><div class="Datatable-content">' + (element.Cantidad * element.Venta).toFixed(2) + '</div></td>');
                    $('#tablaconceptosfactura tbody').append(row);
                    
                });
                let subtotal = 0;
                listaconceptos.forEach(item => {
                    subtotal += item.Cantidad * item.Venta;
            });
            let iva=subtotal*0.16;
            let total=subtotal+iva;
            $('#subtotalfactura').text("Subtotal: $").append(subtotal.toFixed(2));
            $('#ivafactura').text("Iva:      $").append(iva.toFixed(2));
            $('#totalfactura').text("Total:    $").append(total.toFixed(2));

            $('#timbrarfactura').attr('data-id', presupuesto).removeAttr('hidden').attr('disabled', false);
            $('.variasfacturas').removeAttr('data-id').attr('hidden',true);
            $("#facturarmodal").modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
            });   
        }
        window.OpenPrefactura = function(id) {
            const prefactura = id;
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Facturar.Get.Detalles.Prefactura') }}',
                data: { id: prefactura },
                success: function(response) {
                    const valueMap = {
                    "1": "01",
                    "2": "02",
                    "3": "03",
                    "4": "04",
                    "5": "05",
                    "6": "06",
                    "7": "07",
                    "8": "08",
                    "9": "09",
                };

                    $('#tipo_comprobante').val(response.prefactura.tipo_comprobante);
                    $('#uso_cfdi').val(response.prefactura.uso_cfdi);
                    $('#moneda').val(response.prefactura.moneda);
                    $('#fpago').val(valueMap[response.prefactura.fpago]??response.prefactura.fpago);
                    $('#mpago').val(response.prefactura.mpago);
                    $("#empresasfactura").empty().append('<option value="' + response.empresa.id + '">' + response.empresa.nombre+'</option>');
                    FacturarVarias(response.ids,true,'executeviewprefacturas')
                    
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching prefactura:', error);
                }
            });
        };
        window.FacturarVarias = function(ids,prefactura, disparador=null) {
            $('.rfcactive').removeClass('rfcactive');
            DisparadorOtroModal=disparador;
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Facturar.Get.Multiples.Conceptos') }}',
                data:{
                    presupuestos: ids,
                },
                success: function(response) {
                    listaconceptos=response.conceptos;
                    modulo=response.data.moduloId;
                    anio=response.data.anio;
                    contrato=response.data.contratoId;
                    zona=response.data.zonaId;

                    const emisor= modulo;
                    const idemisor = 'Emisor' + emisor;
                    // if(emisor == 3){
                    //     $('#numauto-div').val('').attr('hidden',true);
                    // }else{
                    //     $('#numauto-div').val('').removeAttr('hidden');
                    // }
                    $('#'+idemisor).addClass('rfcactive');
                    $('#emisorrfc').val(idemisor);
                    $('#tablaconceptosfactura thead').empty();
                    $('#tablaconceptosfactura thead').append('<tr><th>Economico</th><th>Placas</th><th>No. Sol</th><th>KM</th><th>Servicio</th><th>Costo</th><th>Iva</th><th>Total</th></tr>');
                    $('#tablaconceptosfactura tbody').empty();
                    $.each(listaconceptos, function(index, element) {
                        let row = $('<tr>'); 
                        row.append('<td><div class="Datatable-content">' + (element.no_economico ?? '' ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (element.placas ?? '') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (element.Folio ?? '' ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (element.Kilometraje_entrada ?? '') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (element.Mano_Obra_Descripcion ?? '') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + Number(element.importe ?? 0).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + Number((element.importe ?? 0)*0.16 ).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + Number((element.importe ?? 0)*1.16 ).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</div></td>');
                        $('#tablaconceptosfactura tbody').append(row);
                        
                    });
                    let subtotal = 0;
                    listaconceptos.forEach(item => {
                        subtotal += item.importe;
                });
                let iva=subtotal*0.16;
                let total=subtotal+iva;
                $('#subtotalfactura').text("Subtotal: $").append( Number(subtotal).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#ivafactura').text("Iva:      $").append( Number(subtotal*0.16 ).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#totalfactura').text("Total:    $").append(Number(subtotal*1.16 ).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#timbrarfacturas').attr('data-id', ids).removeAttr('hidden').attr('disabled', false);
                $('.unafactura').attr('hidden',true);
                if (prefactura) {
                    $('#guardarfacturas').removeAttr('data-id').attr('hidden', true);
                }else {
                    $('#guardarfacturas').removeAttr('hidden').attr('data-id', ids).attr('disabled', false);
                }
                $("#facturarmodal").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            }); 
        };
        window.DeletePrefactura = (id) => { // Tu código aquí };
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Una vez eliminado, No lo podras revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('2025.Facturar.Delete.Prefactura')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                        },
                        success: function(response) {
                           Swal.fire({ html: `${response.message??'Eliminada Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                           executeviewprefacturas()
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });

                        }
                    });
                }
            });
        }
    })
</script>
@endpush