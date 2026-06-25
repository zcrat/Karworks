@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Presupuestos Taller
                    <button class="btn btn-success NuevoPresupuesto"  id="NuevoPresupuesto"><i class="fa-solid fa-circle-plus"></i>&nbsp;Nueva</button>
                    <div id="submenudemo"></div>
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
                        <div class="d-flex">
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29" type="text" id="search" name="s" placeholder="Busqueda Por Ord. Servicio Folio, Marca, Modelo, Vin, Economico, etc"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            <div class="zdflex">
                                <div class="  zdmg-r02">
                                <label for="tipogasto">Fecha Inicio</label>
                                <input name="FechaInicio" id="FechaInicio" type="date" class="form-control">
                            </div>
                            <div class="  zdmg-r02">
                            <label for="tipogasto">Fecha Fin</label>
                            <input name="FechaFin" id="FechaFin" type="date" class="form-control">
                            </div>
                            <div class="zdmg-r02">
                                <label for="estatus">Estatus</label>
                                <select name="estatus" class="form-control" id="estatus">
                                    <option value="">Todos</option>
                                    <option value="0">Por Enviar</option>
                                    <option value="1">Pendientes</option>
                                    <option value="2">Terminados</option>
                                </select>
                            </div>
                            </div>
                            <div class="zdmg-r02">
                                <label class="zdmgr-r02">Empresas:</label>
                                <select class="empresas-Select2" id="empresas">
                                    <option value="">Todas</option>
                                </select>
                            </div>
                            <button type='button'  id="ExportarDatos" class='btn btn-success'  onclick="reporteexcel()">Exportar</button>
                        </div>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="viewelements">
                            <div class="elementosporpagina">
                                <select   class="rounded" id="epp">
                                    <option value="10" >10</option>
                                        @for ($i = 15; $i <= $elementostotales/3; $i += 5)
                                            <option value="{{ $i }}" >{{ $i }}</option>
                                        @endfor
                                </select>
                                <div id='pagination'></div>
                            </div>
                            <div class="mitabla vanigrow vaniflex zdfd-column">
                                <table id="tablarecepciones" class="table table-sm  table-striped">
                                <colgroup>
                                <col class="button_options"> <!-- Columna con ancho fijo del 20% -->
                                    
                                </colgroup>
                                    <thead>
                                        <tr>
                                            <th>OPCIONES</th>
                                            <th>FOLIO</th>
                                            <th>Ord. Servicio</th>
                                            <th>EMPRESA</th>
                                            <th>ECONOMICO</th>
                                            <th>MARCA</th>
                                            <th>MODELOS</th>
                                            <th>AÑO</th>
                                            <th>PLACAS</th>
                                            <th>VIN</th>
                                            <th>FECHA</th>
                                            <th>ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div  class="no-results-message" hidden>
                        <span id="no-results-message"></span>
                        </div>
                    </div>
                    <div class="vaniwidth vaniflex zdfd-column" id="prefacturasdiv" hidden >
                        <div class="d-flex">
                            
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r25"
                                    type="text" id="searchprefacturas" name="s"
                                    placeholder="Busqueda Por Usuario, Cliente" >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            <div class=" selectconlabel zdmg-r02">
                       <label for="tipogasto">Fecha Inicio</label>
                       <input name="fechamovimientoinicio" id="fechamovimientoinicio" type="datetime-local" class="form-control">
                    </div><div class=" selectconlabel zdmg-r02">
                       <label for="tipogasto">Fecha Fin</label>
                       <input name="fechamovimientofin" id="fechamovimientofin" type="datetime-local" class="form-control">
                    </div>
                            
                        </div>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="viewprefacturas">
                            <div class="elementosporpagina">
                                <div id='pagination3'></div>
                            </div>
                            <div class="mitabla vanigrow vaniflex zdfd-column">
                                <table id="tablaprefacturas" class="table table-sm  table-striped">
                                <colgroup>
                                <col class="button_options"> <!-- Columna con ancho fijo del 20% -->
                                    
                                </colgroup>
                                    <thead>
                                        <tr>
                                            <th>OPCIONES</th>
                                            <th>Cliente</th>
                                            <th>Usuario</th>
                                            <th>Forma de Pago</th>
                                            <th>Moneda</th>
                                            <th>Tipo Comprobante</th>
                                            <th>Uso CFDI</th>
                                            <th>FECHA</th>
                                            <th>Subtotal</th>
                                            <th>Iva</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div  class="no-results-message" hidden>
                        <span id="no-results-message2"></span>
                        </div>
                    </div>
                    <div id='loadingdata' class="carga" hidden>
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>

                </div>
            </div>
    </div>

    @include('modales.MensajesPresupuestosModal')
    @include('modales.CarritoAgregarConceptos')
    @include('modales.PresupuestoTaller')
    @include('modales.DetallesGeneralesModal')
    @include('modales.NuevoConceptoModal')
    @include('modales.VehiculoModel')
    @include('modales.ModalOneAttribute')
    @include('modales.SendFiles2')
    @include('modales.clientes')
    @include('modales.HojaConceptos2')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacion.js')}}"></script>
<script src="{{asset('js/paginacion3.js')}}"></script>
<script src="{{asset('js/NestedModals.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>

    $(function() {
        let elements = [];
        let originalelements = [];
        const modulop = @json($modulo);
        const aniop = @json($anio);
        const contratop = @json($contrato);
        const zona = @json($zona);
        $('.empresas-Select2').select2({
    language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
    placeholder: 'Escribe para buscar...',
    allowClear: true,
    minimumInputLength: 0,
    ajax: {
        url: '/select2/obtenerempresas',
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
        searchdata();
        window.limpiarmodaltalleres = function() {
            $("#formnewrecepcion").find(".error-message").remove();
            $('#recepcionservicio input').not('input[name="_token"]').val('');
            $('#recepcionservicio textarea').val('');
            $('#recepcionservicio select').val('').trigger('change');;  // O puedes usar $('#RecepcionVehicular select').prop('selectedIndex', -1); 
        }
        function recepciondelete(id) {
            let ruta = "{{ route('2025.cfe.recepcion.delete') }}";
            Swal.fire({
                icon: "question",
                text: "¿Estás seguro de eliminar la recepción?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn-primary",
                    cancelButton: "btn-light",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ruta,
                        method: "POST",
                        data: { id: id, _token: "{{ csrf_token() }}" },
                        success: function (data) {
                            if (data === "eliminado") {
                                Swal.fire({
                                    icon: "success",
                                    title: "Recepción eliminada correctamente",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                searchdata();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    html: data,
                                });
                            }
                        },
                        error: function (error) {
                            console.log(error)
                            if (error.status === 422) {
                                Swal.fire({
                                    icon: "warning",
                                    title:error.responseJSON.error,
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Ocurrió un error inesperado. Por favor, inténtalo nuevamente.",
                                });
                            }
                        },
                    });
                }
            });
        }      
        function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Presupuestos.Get.Elements') }}',
                data:{
                    modulo:modulop,
                    anio:aniop,
                    contrato:contratop,
                    zona:zona,
                },
                success: function(response) {
                    originalelements = elements = response.elements;
                    document.getElementById('loadingdata').setAttribute('hidden', true);
                    document.getElementById('dataupload').removeAttribute('hidden');
                    filtering()
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        window.executeSearchdata = function() {
            eval("searchdata()");
        };
        window.executedelete = function(id) {
            eval("recepciondelete("+id+")");
        };
        window.executeshowElements = function() {
            eval("showElements()");
        };
        window.executereporte = function(id) {
            eval("reporte("+id+")");
        };
        window.executeservicio = function(id) {
            $('#recepcionservicio').modal("show");
        };
        function reporte(id){
            window.open('/recepcion/reporte/'+ id,'_blank');
        };
        function showElements() {
            ShowPagination(elements.length,8);
            let startIndex = (Page - 1) * itemsPerPage;
            let endIndex = startIndex + itemsPerPage;
            let paginatedElements = elements.slice(startIndex, endIndex);
            $('#tablarecepciones tbody').empty();
            if (paginatedElements.length > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
            }
            $.each(paginatedElements, function(index, element) {
                let row = $('<tr class="zdrelative"><td><div class="Datatable-content" ></div></td></tr>');
                let acciones =`<td><div class="Datatable-content ">`;
                let dropdownContent = ``;
                acciones+=`<div class="zdrelative zdinline"><button type="button" class="btn btn-warning btn-sm zdrelative" title="Mesajes" onclick="openmessagemodal(`+element.id+`)">
                            <i class="fa fa-comment-alt"></i>
                            </button>`+(element.mensajes.length >0 ?`<p class="notificationcount">`+element.mensajes.length+`</p></div>` : `</div>`);
                if( (element.Status_id==0)||(element.Status_id==1)){
                        dropdownContent = `
                        <div class="zdflex">
                    <button type="button"class="opcionesdesplegables btn  btn-primary ">Opciones</button>
                        <ul class="detallesdesplegables zdw-r12 " hidden>
                        <li><a href="#" onclick="executedeletepresupuestos(`+element.id+`)" ">Eliminar</a></li>
                        <li><a href="#" onclick="OpenEditBudGetWitRequest(`+element.id+`)">Editar</a></li>
                        <li><a href="#" class="reportevehicular" data-id="`+element.DetallesGenerales_id+`">Recepción Vehicular</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 3)">Fotos Viejas</a></li>
                        <li><a href="#" class="presupuestopdf" data-id="`+element.id+`">Presupuesto Costo</a></li>
                        
                        <li><a href="#" onclick="OpenConceptsShet(`+element.id+`,'`+element.Folio+`')">Hoja Conceptos</a></li> 
                        <li><a href="#" class="diagnosticotecnico" data-id="`+element.id+`">Diagnostico Tecnico</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 4)">Reporte Anomalías</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 5)">Entrada</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 6)">Orden Servicio</a></li> 
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 1)">Fotos Nuevas</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 2)">Fotos Instaladas</a></li>
                        </ul>
                        </div>`
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 7)">Factura PDF</a></li>
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 8)">Factura XML</a></li>
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 9)">Acuse</a></li>
                }else{	
                    dropdownContent = `
                        <div class="zdflex">
                    <button type="button"class="opcionesdesplegables btn  btn-primary ">Opciones</button>
                        <ul class="detallesdesplegables zdw-r12 " hidden>
                        <li><a href="#" class="reportevehicular" data-id="`+element.DetallesGenerales_id+`">Recepción Vehicular</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 3)">Fotos Viejas</a></li>
                        <li><a href="#" class="presupuestopdf" data-id="`+element.id+`">Presupuesto Costo</a></li>
                       
                        <li><a href="#" onclick="OpenConceptsShet(`+element.id+`,'`+element.Folio+`')">Hoja Conceptos</a></li> 
                        <li><a href="#" class="diagnosticotecnico" data-id="`+element.id+`">Diagnostico Tecnico</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 4)">Reporte Anomalías</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 5)">Entrada</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 6)">Orden Servicio</a></li> 
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 1)">Fotos Nuevas</a></li>
                        <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 2)">Fotos Instaladas</a></li>
                        </ul>
                        </div>`
                        
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 7)">Factura PDF</a></li>
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 8)">Factura XML</a></li>
                        // <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 9)">Acuse</a></li>
                }


                // <li><a href="#" class="presupuestoacusepdf" data-id="`+element.id+`">Presupuesto Acuse</a></li>
                if (element.Status_id == 0) {
                        acciones += `
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                Enviar
                            </button>
                    `;
                }
                if (element.Status_id <= 3 && element.Status_id>0 ) {
                             acciones += `
                            
                                 <button type="button" class="btn btn-secondary" title="Boton de terminar">
                                    PENDIENTE
                                    </button>
                            `;
                        }
                        if (element.Status_id > 3) {
                             acciones += `
                                 <button type="button" class="btn btn-success" title="Boton de autorizar">
                                    TERMINADO
                                    </button> 
                            `;
                        }

                acciones += `</div></td></tr>`;
                row.find('.Datatable-content').append(dropdownContent);
                row.append('<td><div class="">' + (element.Folio ? element.Folio : "Sin Folio" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales ? element.detalles_generales.OrdenServicio : "Sin Folio" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.empresa ? element.detalles_generales.empresa.nombre : "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.no_economico ? element.detalles_generales.vehiculo.no_economico : "Sin # Seguimiento")+ '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.marca ? element.detalles_generales.vehiculo.marca.nombre : "marca") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.modelo ? element.detalles_generales.vehiculo.modelo.nombre : "Sin Modelo") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.anio ? element.detalles_generales.vehiculo.anio : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.placas ? element.detalles_generales.vehiculo.placas : "Sin Placas") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.vim ? element.detalles_generales.vehiculo.vim : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.created_at ? element.created_at : "No Se Registro") + '</div></td></tr>');
                row.append(acciones);
                ;
                $('#tablarecepciones tbody').append(row);
            });
        }
        $('#search').on('input', filtering);
        $('#empresas,#estatus,#FechaInicio,#FechaFin').on('change', filtering);
        function filtering() { 
            let search = $('#search').val().toLowerCase();
            let estatus = $('#estatus').val();
            let empresas = $('#empresas').val();
            let fechamin = $('#FechaInicio').val();
            let fechamax = $('#FechaFin').val();
            fechamin = fechamin ? fechamin + " 00:00:00" : ''; // 00:00:00
            fechamax = fechamax ? fechamax + " 23:59:59" : ''; // 23:59:59
            console.log(estatus)
            Page = 1
                elements = originalelements.filter(function(element) {

                    return ((search === '' || 
                    element.Folio.toLowerCase().includes(search) || 
                    element.detalles_generales.OrdenServicio.toLowerCase().includes(search) || 
                    element.detalles_generales.vehiculo.placas.toLowerCase().includes(search) || 
                    element.detalles_generales.vehiculo.no_economico.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.vim.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.marca.nombre.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.modelo.nombre.toLowerCase().includes(search))
                    &&(fechamin===''|| element.created_at>=fechamin)
                    &&(fechamax===''|| element.created_at<=fechamax)
                    &&(empresas===''||element.detalles_generales.empresa.id==empresas)&&(estatus=='1'?element.Status_id>0 && element.Status_id<=3:estatus=='2'?element.Status_id>3:estatus=='0'?element.Status_id==0:true))
                

                });
            if (elements.length === 0) {
                document.querySelector('.no-results-message').removeAttribute('hidden');
                $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con  '+search );
                
            } else {
                document.querySelector('.no-results-message').setAttribute('hidden',true);
                $('#no-results-message').text('');
            }
            showElements();
            
        }
        window.executefacturaPDF = (id)=>{
            $.ajax({
                url: "{{route('Facturacion.obtener.factura.pdf')}}",
                type: "get",
                data:{
                    id:id,
                },
                success: function(response) {
                    var respuesta = '/facturas/'+response.success;
                    $('#pdf_factura').attr('src',respuesta);
                    $('#viewarchivomodal').modal('show');
                },
                error: function(xhr, status, errors) {
                    console.log(xhr)
                        let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                        Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error:<br> ${xhr.responseJSON.errors}`, icon: 'error'});
                   
                }
            })
        }
        window.executefacturaXML = (id)=>{
            $.ajax({
                url: "{{route('Facturacion.obtener.factura.xml')}}",
                type: "get",
                data:{
                    id:id,
                },
                success: function(response) {
                    var respuesta = '/facturas/'+response.success;
                    window.open('/download/'+ response.success,'_blank');
                },
                error: function(xhr, status, errors) {
                    console.log(xhr)
                        let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                        Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error:<br> ${xhr.responseJSON.errors}`, icon: 'error'});
                   
                }
            })
        }
        
        $(document).on('click', '.reportevehicular', function(){
            const id=$(this).data("id");
            $.ajax({
                        url: "{{ route('2025.Recepcion.Vehicular.PDF') }}",
                        method: 'GET',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                                const newWindow = window.open('', '_blank');
                                newWindow.document.write(response.html);
                                newWindow.document.close();
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                    });
        });
        $(document).on('click', '.presupuestopdf', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/PDF/Costo/'+ id,'_blank');
        });
        $(document).on('click', '.presupuestofinalpdf', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/PDF/Venta/'+ id,'_blank');
        });
        $(document).on('click', '.presupuestoacusepdf', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/PDF/Acuse/'+ id,'_blank');
        });
        $(document).on('click', '.hojaconceptos', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/Hoja/Conceptos/'+ id,'_blank');
        });
        $(document).on('click', '.diagnosticotecnico', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/Diagnostico/Tecnico/'+ id,'_blank');
        });
        window.executedeletepresupuestos = (id) => { // Tu código aquí };
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
                        url: "{{route('2025.Presupuestos.Delete')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                        },
                        success: function(response) {
                            const mensaje=response.success
                            Swal.fire({ html: `${mensaje}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            executeSearchdata()
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            console.log(xhr)
                            Swal.fire({
                                title: 'Error',
                                html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.error}`:``}`,
                                icon: 'error'
                                });

                        }
                    });
                }
            });
        }
        window.executecambiostatus = (id,status)=>{
            Swal.fire({
                title: '¿Estás seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('2025.Presupuestos.Change.Status')}}",
                        type: "put",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                            estatus:status,
                        },
                        success: function(response) {
                            executeSearchdata()
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            Swal.fire({
                                title: 'Error',
                                html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.message}`:``}`,
                                icon: 'error'
                            });

                        }
                    });
                } else {
                    
                }
            });
        }
        window.openmessagemodal=(id)=>{
            $('#presupuesto_id').val(id);
            $.ajax({
                url: "{{route('2025.Presupuestos.Get.Messages')}}",
                type: "get",
                data:{
                    id:id,
                },
                success: function(response) {
                    $('#tablemessage').empty();
                    $.each(response.success, function(index, element) {
                        let row = $('<div class="zdflex zdmg-r05 zditemscenter">');
                        row.append(`<button type="button"class="btn  btn-danger btn-sm" onclick="deletemessage(`+element.id+`)"><i aria-hidden="true" class="fa-solid fa-trash"></i></button>`);
                        row.append('<div class="zdmgl-r05"><label class="zdbold zdblock">' + (element.mensaje ? element.mensaje : 'Nulo') + '</label>'+
                        '<label>' + (element.created_at ? element.created_at : 'Nulo') + ' &nbsp&nbsp&nbsp</label>'+
                        '<label>' + (element.usuarios ? element.usuarios.name : 'Nulo') + '</label></div>')
                        $('#tablemessage').append(row);
                    });
                    $('#messagemodal').modal('show');
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                    console.log(xhr)
                    Swal.fire({
                        title: 'Error',
                        html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.message}`:``}`,
                        icon: 'error'
                        });

                }
            });
    
        }
        $(document).on('click','.presupuestoid',function() {
            id=$(this).val();
            if ($(this).is(':checked')) { // Verificar si el checkbox está marcado
                if(!facturasselected.includes(id)){
                    facturasselected.push(id);
                }
            } else {
                if(facturasselected.includes(id)){
                    index=facturasselected.indexOf(id);
                    facturasselected.splice(index, 1);
                }
            }
        })
        window.reporteexcel= function(){
        let url= '{{ route('2025.Presupuestos.Exportar.Excel') }}'
        $.ajax({
                type: 'post',
                url: url,
                data:{_token: "{{ csrf_token() }}",elements:elements.map(e=>e.id)},
                dataType: 'json',
                success: function(response) {
                    const url = response.excel;
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'filename.xlsx'; // Nombre del archivo para descargar
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
    }
    });
</script>
@endsection                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  