@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <button class="btn "  id="Refresh" onclick="executeSearchdata()"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;</button>
                    <i class="fa fa-align-justify"></i>Presupuestos Asignados
                    <button class="btn btn-success NuevoPresupuestoRes"  id="NuevoPresupuestoRes"><i class="fa-solid fa-circle-plus"></i>&nbsp;Nueva</button>
                    <button class="btn btn-info"  id="IsAdminButton"><i class="fa-solid fa-user"></i>&nbsp;Admin</button>
                    <button class="btn btn-warning OpenAsiganPresupuestoRestringido"  id="OpenAsiganPresupuestoRestringido"><i class="fa-solid fa-user"></i>&nbsp;</button>
                    @can('exportar-conceptos-historial')
                        <button type='button'  id="importpresupuestos" class='btn btn-primary OpenHisConEcoModel'>Vehiculo Historial</button>
                    @endcan
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column">
                        <div class="d-flex">
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29" type="text" id="search" name="s" placeholder="Busqueda Por Ord. Servicio Folio, Marca, Modelo, Vin, Economico, etc"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            <div class="  zdmg-r02">
                                <label for="tipogasto">Fecha Inicio</label>
                                    <input name="FechaInicio" id="FechaInicio" type="date" class="form-control">
                                </div>
                                <div class="  zdmg-r02">
                                    <label for="tipogasto">Fecha Fin</label>
                                    <input name="FechaFin" id="FechaFin" type="date" class="form-control">
                                </div>
                                <div class="zdmg-r02">
                                    <label class="zdmgr-r02">Usuarios:</label>
                                    <select class="usuarios-select2" id="usuarios">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="zdmg-r02">
                                <label class="zdmgr-r02">Empresas:</label>
                                <select id="empresas">
                                    <option value="">Todas</option>
                                </select>
                            </div>
                                <div class="zdmg-r02">
                                    <label for="estatus">Estatus</label>
                                    <select name="estatus" class="form-control" id="estatus">
                                        <option value="">Todos</option>
                                        <option value="0">Sin Enviar</option>
                                        <option value="1">Por Autorizar</option>
                                        <option value="2">Autorizados</option>
                                        <option value="6">Denegados</option>
                                        <option value="3">Por Aprobar</option>
                                        <option value="7">Pago Denegado</option>
                                        <option value="4">Para Terminar</option>
                                        <option value="5">Facturados</option>
                                        <option value="8">Solo Terminados</option>
                                    </select>
                                </div>
                                <div class="zdmg-r02">
                                    <label for="estatusarchivo">Archivos</label>
                                    <select name="estatusarchivo" class="form-control" id="estatusarchivo">
                                        <option value="">Todos</option>
                                        <option value="1">🟢 Completo</option>
                                        <option value="2">🟡 En proceso</option>
                                        <option value="3">🔴 Incompleto</option>
                                    </select>
                                </div>
                            </div>
                            <div  id="dataupload">
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
                                                    <th>Creado</th>
                                                    <th>usuario</th>
                                                    <th>ESTADO</th>
                                                    <th>ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div  id='div-no-results-message' class="no-results-message" hidden>
                                <span id="no-results-message"></span>
                                </div>
                            </div>
                            <div id='loadingdata' class="carga" hidden>
                                <h3 class="text-center m-2">Cargando Datos</h3>
                                <div class="spinnerp"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    @include('modales.PresupuestoRestringido')
    @include('modales.ModalFactura')
    @include('modales.ModalCancelFactura')
    @include('modales.SendFiles2')
    @include('modales.MensajesPresupuestosModal')
    @include('modales.CompararPresupuesto')
    @include('modales.viewarchivopdf')
    @include('modales.CambiarModulo')
    @include('modales.CarritoAgregarConceptos')
    @include('modales.EditarPresupuestoGlobal')
    @include('modales.NuevConceptoGlobal')
    @include('modales.VehiculoModel')
    @include('modales.ModalOneAttribute')
    @include('modales.PagosPresupuestos')
    @include('modales.ImportPresupuestos')
    @include('modales.AsiganPresupuestoRestringido')
    @include('modales.clientes')
    @include('modales.HistorialConceptosEconomico')
    @include('modales.HojaConceptos')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacionv1.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>

    $(function() {
        let elements = [];
        let totalelements = 0;
        let Page = 1;
        let itemsPerPage = 10;
        let typingTimer;
        const typingDelay = 1000; // 1 segundo
        let isadmin=true;
        searchdata();   

        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Presupuestos.Get.Elements.Restringidos') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        search : $('#search').val().toLowerCase(),
                        estatus : $('#estatus').val(),
                        empresa : $('#empresas').val(),
                        estatusarchivo : $('#estatusarchivo').val(),
                        usuarios : $('#usuarios').val(),
                        fechamin : $('#FechaInicio').val(),
                        fechamax : $('#FechaFin').val(),
                    },
                    success: function(response) {
                        elements = response.elements;
                        totalelements = response.totalelements;
                        document.getElementById('loadingdata').setAttribute('hidden', true);
                        document.getElementById('dataupload').removeAttribute('hidden');
                        showElements()
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            });
        }
        window.executeSearchdata = function(newpage = 1,changePage= true) {
            if(changePage){
                Page = newpage;
            }
            eval("searchdata()");
        };
       
        window.executeshowElements = function() {
            eval("showElements()");
        };
        $('#empresas').select2({
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
        $('.usuarios-select2').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                    url: '/select2/obtenerusuariosrewstringidos',
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
        function showElements() {
            ShowPagination(totalelements,8,Page);
    
            $('#tablarecepciones tbody').empty();
            if (totalelements > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
                document.getElementById('div-no-results-message').setAttribute('hidden', true);
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
                document.getElementById('div-no-results-message').removeAttribute('hidden');
                if($('#search').val()==''){
                    $('#no-results-message').text('No Hay Resultados Disponibles');
                }else{
                    $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con  '+$('#search').val() );
                }
            }
            $.each(elements, function(index, element) {
                console.log('entra')
                let row = $('<tr class="zdrelative"><td><div class="Datatable-content" ></div></td></tr>');
                let dropdownContent = `<div class="zdflex"><button type="button"class="opcionesdesplegables btn btn-primary ">Opciones</button><ul class="detallesdesplegables zdw-r12 " hidden>`;
                let userId = {{ auth()->id() }}
                let estadosemmaforo=statusupimages(element.archivos ?? []);
                let semaforo =`<div class='semaforocontent'>
                            <span class="semaforo-verde ${ estadosemmaforo==1 ? 'semaforo-active' : '' }"></span>
                            <span class="semaforo-amarillo ${ estadosemmaforo==2 ? 'semaforo-active' : '' }"></span>
                            <span class="semaforo-rojo ${ estadosemmaforo==3 ? 'semaforo-active' : '' }"></span>
                    </div>`;

                let estado =`<td><div class="Datatable-content">`;

                let acciones =`<td><div class="Datatable-content">
                        <div class="zdrelative zdinline">
                            <button type="button" class="btn btn-warning btn-sm zdrelative" title="Mesajes" onclick="openmessagemodal(`+element.id+`)">
                                <i class="fa fa-comment-alt"></i>
                            </button>
                            ${(element.mensajes_no_leidos.length > 0 ? '<p class="notificationcount">'+element.mensajes_no_leidos.length+'</p>': '')}
                        </div>`

                if(isadmin){
                    acciones+=`<button type="button" class="btn btn-warning btn-sm" title="editar Presupuesto" onclick="OpenEditBudGetWitRequest(${element.id})">
                            <i class="fa fa-pencil-square-o"></i>
                         </button>`;
                    if(userId == 1 || userId == 170 || userId == 153){
                        acciones+=`<button type="button" class="btn btn-success btn-sm zdrelative" title="Editar Modulo" 
                            onclick="OpenChangerModuloCortana(`+
                            element.detalles_generales.id+`,`+
                            element.detalles_generales.modulo_cortana.id+`,\'`+
                            element.detalles_generales.modulo_cortana.descripcion+`\')"><i class="fas fa-table"></i></button>
                            </div>`;
                    }
                    if (element.Status_id == 0) {
                        estado += `
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                Enviar
                            </button>
                        `;
                    }
                    if (element.Status_id == 1) {
                            estado += `
                                <button type="button" class="btn btn-success" onclick="executecambiostatus(`+element.id+`,2)"title="Boton de terminar">
                                    Autorizar
                                </button>
                                <button type="button" class="btn btn-danger" onclick="executecambiostatus(`+element.id+`,6)"title="Boton de terminar">
                                    Denegar
                                </button>
                        `;
                    }
                    if (element.Status_id == 2) {
                            estado += `
                                <button type="button" class="btn btn-success" onclick="executecambiostatus(`+element.id+`,2)"title="Boton de terminar">
                                    Pendiente Terminar
                                </button>
                        `;
                    }
                    if (element.Status_id == 3) {
                            estado += `
                                <button type="button" class="btn btn-success" onclick="executecambiostatus(`+element.id+`,4)"title="Boton de terminar">
                                    Aprobar Pago
                                </button>
                                <button type="button" class="btn btn-danger" onclick="executecambiostatus(`+element.id+`,7)"title="Boton de terminar">
                                    Denegar Pago
                                </button>
                        `;
                    }
                    if (element.Status_id == 6) {
                            estado += `
                                <button type="button" class="btn btn-danger" title="Boton de terminar">
                                    Denegado
                                </button>
                                <button type="button" class="btn btn-success" onclick="executecambiostatus(`+element.id+`,2)"title="Boton de terminar">
                                    Autorizar Parte 1
                                </button>
                        `;
                    }
                    if (element.Status_id == 7) {
                            estado += `
                                <button type="button" class="btn btn-danger" title="Boton de terminar">
                                    Pago Denegado
                                </button>
                                <button type="button" class="btn btn-success" onclick="executecambiostatus(`+element.id+`,4)"title="Boton de terminar">
                                    Autorizar Pago
                                </button>
                        `;
                    }
                    
                    if (element.Status_id == 4) {
                        estado += `
                                <button type="button" class="btn btn-success" onclick="TerminarUnidad(`+element.id+`)" title="Boton de Facturar">
                                    Terminar
                                </button>

                        `;
                        if(userId == 1 ){
                            estado += `
                                <button type="button" class="btn btn-primary" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de Reversa Estatus">
                                    R
                                </button>
                        `;
                    }
                    }
                    if (element.Status_id == 5) {
                            estado += `
                                <button type="button" class="btn btn-success">
                                Facturado
                                </button>`
                            acciones +=`
                                <button type="button" class="btn btn-danger btn-sm" title="Factura PDF" onclick="executefacturaPDF(`+element.Factura_id+`)">
                                <i class="fa fa-file-invoice"></i>
                                </button> 
                                <button type="button" class="btn btn-warning btn-sm" title="Factura PDF" onclick="executefacturaXML(`+element.Factura_id+`)">
                                <i class="fa fa-file-invoice"></i>
                                </button>
                        `;
                        if(userId == 1 || userId == 170 || userId == 153){
                            estado += `
                                <button type="button" class="btn btn-primary OpenCancelarFactura" data-factura="`+element.Factura_id+`" data-function="executeSearchdata" title="Boton de Reversa Estatus">
                                    R
                                </button>
                            
                        `;}
                    }
                    if (element.Status_id == 8) {
                            estado += `
                                <button type="button" class="btn btn-success">
                                Terminado
                                </button>`
                            
                        if(userId == 1 || userId == 170 || userId == 153){
                            estado += ` <button type="button" class="btn btn-primary" onclick="executecambiostatus(`+element.id+`,4)"title="Boton de Reversa Estatus">
                                    R
                                </button> `
                        }
                    }
                    
                    if(userId == 1 || userId == 170 ){
                        if(element.pagos.length==0){
                            acciones += `
                                <button type="button" class="btn btn-danger btn-sm presupuestopagar" title="Pagar Presupuesto" data-id=`+element.id+`>
                                <i class="fa-solid fa-money-check"></i>
                                </button> `
                        }else{
                            acciones += `
                                <button type="button" class="btn btn-success btn-sm presupuestopagado" title="Presupuesto Pagado" data-id=`+element.id+` data-fecha=`+element.Fecha_Pagado+` data-importe=`+element.Importe_Pagado+`>
                                    <i class="fa-solid fa-money-check"></i>
                                </button> `  
                            }
                    }
                    acciones += `
                        <button type="button" class="btn btn-danger btn-sm" title="Presupuestos Comparacion" onclick="opencompararPresupuesto(`+element.id+`)">
                            <i class="fa fa-file-invoice"></i>
                        </button> `  
                }else{
                    if (element.Status_id == 0) {
                            estado += `
                                <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                    Enviar
                                </button>
                        `;
                    }
                    if (element.Status_id == 1 ) {
                            estado += `
                                <button type="button" class="btn btn-secondary" title="Boton de terminar">
                                Por Autorizar
                                </button>
                            `;
                    }
                    if (element.Status_id == 2 ) {
                            estado += `
                        
                                <button type="button" class="btn btn-success" title="Boton de terminar">
                                Autorizado
                                </button>
                                <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,3)"title="Boton de terminar">
                                    Terminar
                                </button>
                        `;
                    }
                    if (element.Status_id == 7 ) {
                            estado += `
                        
                                <button type="button" class="btn btn-success" title="Boton de terminar">
                                Pago Rechazado 
                                </button>
                                <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,3)"title="Boton de terminar">
                                    Terminar
                                </button>
                        `;
                    }
                    if (element.Status_id == 6 ) {
                            estado += `
                        
                                <button type="button" class="btn btn-danger" title="Boton de terminar">
                                    DENEGADO
                                </button>
                                <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                    ENVIAR
                                </button>
                        `;
                    }
                    if (element.Status_id == 3 ) {
                            estado += `
                        
                                <button type="button" class="btn btn-success" title="Boton de terminar">
                                    Terminado Sin Pago
                                </button>
                        `;
                    }
                    if (element.Status_id == 4 || element.Status_id == 5 || element.Status_id == 8 ) {
                            estado += `
                                <button type="button" class="btn btn-success" title="Boton de autorizar">
                                    TERMINADO PAGADO
                                </button> 
                        `;
                    }
                }
                
                dropdownContent += `
                            <li><a href="#" onclick="executedeletepresupuestos(`+element.id+`)">Eliminar</a></li>
                            <li><a href="#" class="reportevehicular" data-id="`+element.DetallesGenerales_id+`">Recepción Vehicular</a></li>
                            <li><a href="#" onclick="OpenConceptsShet(`+element.id+`,'`+element.Folio+`')">Hoja Conceptos</a></li>
                            <li><a href="#" class="diagnosticotecnico" data-id="`+element.id+`">Diagnostico Tecnico</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 3)">Fotos Generales</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 4)">Presupuesto</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 5)">Entrada</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 6)">Orden Servicio</a></li> 
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 7)">Factura PDF</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 8)">Factura XML</a></li>
                            <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 9)">Acuse</a></li>
                        </ul>
                    </div>`
                estado +=semaforo+`</div></td></tr>`;

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
                row.append('<td><div class="">' + (element.detalles_generales.user.name?element.detalles_generales.user.name : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.user_restringido ? element.user_restringido.user.name : "Sin Asignar Usuario") + '</div></td></tr>');
                row.append(estado);
                row.append(acciones);
                $('#tablarecepciones tbody').append(row);
            });
        }
         $(document).on('click', '.diagnosticotecnico', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/Diagnostico/Tecnico/'+ id,'_blank');
        });
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
        function statusupimages(images) {
            const tiposRequeridos = [3, 4, 7, 8, 9];

            // Tipos subidos (sin duplicados)
            const tiposSubidos = [...new Set(
                images.map(img => img.Tipo_archivo_id)
            )];

            const encontrados = tiposRequeridos.filter(tipo =>
                tiposSubidos.includes(tipo)
            );

            if (encontrados.length === tiposRequeridos.length) {
                return 1;
            }

            if (encontrados.length > 0) {
                return 2;
            }

            return 3;

        }
        $('#usuarios,#estatus,#FechaInicio,#FechaFin,#estatusarchivo,#empresas').on('change',  searchdata);
        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                await searchdata();
            }, typingDelay);
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
                        url: "{{route('2025.Presupuestos.Delete.Restringido')}}",
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
                            const reset=$('#estatus').val() != ''
                            executeSearchdata(1,reset)
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
        window.TerminarUnidad = (id) => {
            Swal.fire({
                title: '¿Deseas Facturar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Facturar',
                cancelButtonText: 'Solo Terminar'
            }).then((result) => {
                if (result.isConfirmed) {
                   FacturarUna(id);
                }else if (result.dismiss === Swal.DismissReason.cancel){
                   executecambiostatus(id,8)
                }
            });
        }
        $('#IsAdminButton').on('click',function(){
            isadmin=!isadmin;
            if(isadmin){
                $('#IsAdminButton').removeClass('btn-warning').addClass('btn-info').text('Admin')
            }else(

                $('#IsAdminButton').removeClass('btn-info').addClass('btn-warning').text('User')
            )
            showElements();

        })
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
    });
</script>
@endsection