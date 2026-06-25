@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>TODOS LOS PRESUPUESTOS
                </div>
                <div class="card-body mycard zdfd-column">
                    <div class="vaniwidth vaniflex zdfd-column">
                    <div class="d-flex">
                        <div class="iconoin zdmgr-r05">
                            <input class="misearch zdw-r29"
                                type="text" id="search" name="s"
                                placeholder="Busqueda Por Ord. Servicio Folio, Marca, Modelo, Vin, Economico, etc" >
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
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
                            <label for="estatus">Estatus</label>
                        <select name="estatus" class="form-control" id="estatus">
                            <option value="">Todos</option>
                            <option value="0">Pendientes</option>
                            <option value="1">Para Autorizar</option>
                            <!-- <option value="2">Finales</option>
                            <option value="3">Autorizacion En Proceso</option> -->
                            <option value="4">Para Facturar</option>
                            <option value="5">Facturados</option>
                            <option value="eliminado">Eliminados</option>
                        </select>
                        
                        </div>
                        <div class="zdmg-r02">
                                <label class="zdmgr-r02">Empresas:</label>
                                <select class="empresas-Select2" id="empresas">
                                    <option value="">Todas</option>
                                </select>
                            </div>
                            <div>
                                <button type='button'  id="ExportarDatos" class='btn btn-success'  onclick="reporteexcel()">Exportar</button>
                                <button type='button'  id="importpresupuestos" class='btn btn-primary'  onclick="ImportPresupuestos()">Importar</button>
                            </div>
                            @can('exportar-conceptos-historial')
                                <button type='button'  id="importpresupuestos" class='btn btn-primary OpenHisConEcoModel'>Vehiculo Historial</button>
                            @endcan
                    </div>
                    <div id="dataupload" >
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
                                            <th>FOLIO</th>
                                            <th>Ord. Servicio</th>
                                            <th>Ord. Seguimiento</th>
                                            <th>EMPRESA</th>
                                            <th>ECONOMICO</th>
                                            <th>MARCA</th>
                                            <th>MODELOS</th>
                                            <th>AÑO</th>
                                            <th>PLACAS</th>
                                            <th>VIN</th>
                                            <th>FECHA</th>
                                            <th>ESTATUS</th>
                                            <th>USER UP.</th>
                                            <th>ARCHIVOS</th>
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
                    </div>
                    <div id='loadingdata' class="carga" hidden>
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>

                </div>
            </div>
    </div>

    @include('modales.viewarchivopdf')
    @include('modales.CambiarModulo')
    @include('modales.CarritoAgregarConceptos')
    @include('modales.EditarPresupuestoGlobal')
    @include('modales.NuevConceptoGlobal')
    @include('modales.VehiculoModel')
    @include('modales.ModalOneAttribute')
    @include('modales.PagosPresupuestos')
    @include('modales.ImportPresupuestos')
    @include('modales.SendFiles2')
    @include('modales.HistorialConceptosEconomico')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacionv1.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>
    $(function() {
       
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
        let elements = [];
        let countelements = 0;
        searchdata(Page =1);
        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);

            let fechamin = $('#FechaInicio').val();
            let fechamax = $('#FechaFin').val();
            fechamin = fechamin ? fechamin + " 00:00:00" : '';
            fechamax = fechamax ? fechamax + " 23:59:59" : '';

            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Presupuestos.Get.Consulta.Elements') }}',
                    data: {
                        search: $('#search').val().toLowerCase(),
                        estatus: $('#estatus').val(),
                        empresas: $('#empresas').val(),
                        fechamin: fechamin,
                        fechamax: fechamax,
                        elements: $('#epp').val(),
                        page: Page,
                    },
                    success: async function (response) {
                        elements = response.elements;
                        countelements = response.countelements;
                        document.getElementById('loadingdata').setAttribute('hidden', true);
                        document.getElementById('dataupload').removeAttribute('hidden');
                        await showElements(response.elements);
                        resolve(); // Marca que terminó correctamente
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr);
                        reject(error); // Manejo de errores
                    }
                });
            });
        }
        window.RedirectionFolio =function(folio,ruta){
        console.log(folio);
        localStorage.setItem('foliobuscar', folio);
        console.log(localStorage.getItem('foliobuscar'))
        window.location.href = ruta
    }
        window.executeshowElements = function() {
            eval("showElements()");
        };
        window.executeSearchdata = function() {
            eval("searchdata()");
        };
        async function showElements(elements=[]) {
            ShowPagination(countelements,8);
            $('#tablarecepciones tbody').empty();
            if (countelements > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
                document.querySelector('.no-results-message').setAttribute('hidden',true);
                $('#no-results-message').text('');
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
                 document.querySelector('.no-results-message').removeAttribute('hidden');
                $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con  '+ $('#search').val().toLowerCase());
                
            }

            $.each(elements, function(index, element) {
                let row = $('<tr class="zdrelative"></tr>');
                let acciones =`<td><div class="Datatable-content ">`;
                let presupuestoUrl =`{{ route('2025.Presupuestos.View') }}?contrato=${element.detalles_generales.contrato?.nombre ?? ''}&modulo=${element.detalles_generales.modulo?.descripcion ?? ''}&anio=${element.detalles_generales.anio}&zona=${element.detalles_generales.zona?.nombre??''}`;
                acciones+=`<div class="zdrelative zdinline">
                    <button type="button" class="btn btn-primary btn-sm zdrelative presupuestopdf" title="Presupuesto Costo" data-id="`+element.id+`">C</button>
                    <button type="button" class="btn btn-info btn-sm zdrelative presupuestofinalpdf" title="Presupuesto Final" data-id="`+element.id+`">V</button>
                    <button type="button" class="btn btn-success btn-sm zdrelative" title="Editar Modulo" 
                    onclick="OpenChangerModuloCortana(`+
                    element.detalles_generales.id+`,`+
                    element.detalles_generales.modulo_cortana.id+`,\'`+
                    element.detalles_generales.modulo_cortana.descripcion+`\')"><i class="fas fa-table"></i></button>
                    </div>`;

                if (element.Status_id == 5) {
                        acciones += `
                            <button type="button" class="btn btn-danger btn-sm" title="Factura PDF" onclick="executefacturaPDF(`+element.Factura_id+`)">
                            <i class="fa fa-file-invoice"></i>
                            </button> 
                            <button type="button" class="btn btn-warning btn-sm" title="Factura PDF" onclick="executefacturaXML(`+element.Factura_id+`)">
                            <i class="fa fa-file-invoice"></i>
                            </button> `;
                }

                if(element.deleted_at != null){
                     acciones += `
                            <button type="button" class="btn btn-success btn-sm" title="Restaurar Presupuesto" onclick="RestaurarPresupuesto(`+element.id+','+element.OrdenServicio+`)">
                            R
                            </button> `
                }
                acciones += `
                        <button type="button" class="btn btn-warning btn-sm" title="editar Presupuesto" onclick="OpenEditBudGetWitRequest(`+element.id+`)">
                        <i class="fa fa-pencil-square-o"></i>
                        </button> `
                if(element.pagos.length === 0){
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

                acciones += `</div></td></tr>`;
                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.Folio+'`,'+'`'+presupuestoUrl+'`)">' + (element.Folio ? element.Folio : "Sin Folio") + '</a></div></td>');
                row.append('<td><div class="">' + (element.detalles_generales ? element.detalles_generales.OrdenServicio : "Sin Folio" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales ? element.detalles_generales.OrdenSeguimiento : "Sin Folio" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.empresa ? element.detalles_generales.empresa.nombre : "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.no_economico ? element.detalles_generales.vehiculo.no_economico : "Sin # Seguimiento")+ '</div></td>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.marca ? element.detalles_generales.vehiculo.marca.nombre : "marca") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.modelo ? element.detalles_generales.vehiculo.modelo.nombre : "Sin Modelo") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.anio ? element.detalles_generales.vehiculo.anio : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.placas ? element.detalles_generales.vehiculo.placas : "Sin Placas") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.detalles_generales.vehiculo.vim ? element.detalles_generales.vehiculo.vim : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.created_at ? element.created_at : "No Se Registro") + '</div></td></tr>');
                row.append('<td><div class="">' + (element.deleted_at == null? element.estatus ? element.estatus.nombre : "Desconocido":'Eliminado') + '</div></td></tr>');
                row.append('<td><div class="">' + (element.user ? element.user.name : "Desconocido") + '</div></td></tr>');
                row.append(acciones);
                ;
                $('#tablarecepciones tbody').append(row);
            });
        }
        let typingTimer;
        const typingDelay = 1000; // 1 segundo

        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                await searchdata();
            }, typingDelay);
        });


        $('#empresas,#estatus,#FechaInicio,#FechaFin').on('change',  searchdata);
        async function  filtering() { 
            await searchdata();
            return ;
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
                    element.detalles_generales.OrdenSeguimiento.toLowerCase().includes(search) || 
                    element.detalles_generales.vehiculo.placas.toLowerCase().includes(search) || 
                    element.detalles_generales.vehiculo.no_economico.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.vim.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.marca.nombre.toLowerCase().includes(search) ||
                    element.detalles_generales.vehiculo.modelo.nombre.toLowerCase().includes(search))
                    &&(fechamin===''|| element.created_at>=fechamin)
                    &&(fechamax===''|| element.created_at<=fechamax)
                    &&(empresas===''||element.detalles_generales.empresa.id==empresas)&&(estatus===''|| (estatus=='eliminado'?element.deleted_at!=null: element.Status_id==estatus)))
                

                });
            if (elements.length === 0) {
                document.querySelector('.no-results-message').removeAttribute('hidden');
                $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con La Busqueda');
                
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
        window.RestaurarPresupuesto = (id,busqueda)=>{
            let ruta = "{{ route('2025.Presupuestos.Restore') }}";
            Swal.fire({
                icon: "question",
                text: "¿Estás seguro de Restaurar El Presupuesto?",
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
                        method: "put",
                        data: { id: id, _token: "{{ csrf_token() }}" },
                        success: function (data) {
                                Swal.fire({
                                    icon: "success",
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                $('#search').val(busqueda);
                                $('#estatus').val('');
                                searchdata();

                           
                        },
                        error: function (xhr) {
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            console.log(xhr)
                            Swal.fire({
                                title: 'Error',
                                html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.error}`:``}`,
                                icon: 'error'
                                });
                        },
                    });
                }
            });
        }

        $(document).on('click', '.presupuestopdf', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/PDF/Costo/'+ id,'_blank');
        });
        $(document).on('click', '.presupuestofinalpdf', function(){
            const id=$(this).attr("data-id");
            window.open('/Zcrat/Presupuestos/PDF/Venta/'+ id,'_blank');
        });
        window.reporteexcel= function(){
            let url= '{{ route('2025.Presupuestos.Exportar.Excel2') }}'
            let fechamin = $('#FechaInicio').val();
            let fechamax = $('#FechaFin').val();
            fechamin = fechamin ? fechamin + " 00:00:00" : ''; // 00:00:00
            fechamax = fechamax ? fechamax + " 23:59:59" : ''; // 23:59:59
        $.ajax({
                type: 'post',
                url: url,
                data:{_token: "{{ csrf_token() }}",
                    search: $('#search').val().toLowerCase(),
                    estatus: $('#estatus').val(),
                    empresas: $('#empresas').val(),
                    fechamin: fechamin,
                    fechamax: fechamax,
                    },
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
@endsection