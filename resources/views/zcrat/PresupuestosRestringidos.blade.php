@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Presupuestos Asignados
                    <button class="btn btn-success NuevoPresupuestoRes"  id="NuevoPresupuestoRes"><i class="fa-solid fa-circle-plus"></i>&nbsp;Nueva</button>
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
                        <div class="d-flex">
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29" type="text" id="search" name="s" placeholder="Busqueda Por Ord. Servicio Folio, Marca, Modelo, Vin, Economico, etc"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
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
                    <div id='loadingdata' class="carga" hidden>
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>

                </div>
            </div>
    </div>
    @include('modales.PresupuestoRestringido')
    @include('modales.VehiculoModel')
    @include('modales.ModalOneAttribute')
    @include('modales.SendFiles2')
    @include('modales.MensajesPresupuestosModal')
    @include('modales.clientes')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacion.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>

    $(function() {
        let elements = [];
        let originalelements = [];
        searchdata();   
        function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Presupuestos.Get.Elements.Restringidos') }}',
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
       
        window.executeshowElements = function() {
            eval("showElements()");
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
                let dropdownContent = `<div class="zdflex"><button type="button"class="opcionesdesplegables btn btn-primary ">Opciones</button><ul class="detallesdesplegables zdw-r12 " hidden>`;
                    console.log(element.Status_id);
                if (element.Status_id<=1){
                    dropdownContent += `<li><a href="#" onclick="executedeletepresupuestos(`+element.id+`)">Eliminar</a></li>`
                }
                dropdownContent += `
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 3)">Fotos Generales</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 4)">Presupuesto</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 4)">Reporte Anomalías</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 5)">Entrada</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 6)">Orden Servicio</a></li> 
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 7)">Factura PDF</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 8)">Factura XML</a></li>
                                <li><a href="#" onclick="executesubirarchivo(`+element.id+`, 9)">Acuse</a></li>
                            </ul>
                        </div>`
                let acciones =`<td><div class="Datatable-content ">`;
                acciones+=`<div class="zdrelative zdinline">
                        <button type="button" class="btn btn-warning btn-sm zdrelative" title="Mesajes" onclick="openmessagemodal(`+element.id+`)">
                            <i class="fa fa-comment-alt"></i>
                            </button>`+(element.mensajes_no_leidos.length >0 ?`<p class="notificationcount">`+element.mensajes_no_leidos.length+`</p></div>` : `</div>`);
                if (element.Status_id == 0) {
                        acciones += `
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                Enviar
                            </button>
                    `;
                }
                if (element.Status_id == 1 ) {
                        acciones += `
                            <button type="button" class="btn btn-secondary" title="Boton de terminar">
                            Por Autorizar
                            </button>
                        `;
                }
                if (element.Status_id == 2 ) {
                        acciones += `
                    
                            <button type="button" class="btn btn-success" title="Boton de terminar">
                            Autorizado
                            </button>
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,3)"title="Boton de terminar">
                                Terminar
                            </button>
                    `;
                }
                if (element.Status_id == 7 ) {
                        acciones += `
                    
                            <button type="button" class="btn btn-success" title="Boton de terminar">
                               Pago Rechazado 
                            </button>
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,3)"title="Boton de terminar">
                                Terminar
                            </button>
                    `;
                }
                if (element.Status_id == 6 ) {
                        acciones += `
                    
                            <button type="button" class="btn btn-danger" title="Boton de terminar">
                                DENEGADO
                            </button>
                            <button type="button" class="btn btn-warning" onclick="executecambiostatus(`+element.id+`,1)"title="Boton de terminar">
                                ENVIAR
                            </button>
                    `;
                }
                if (element.Status_id == 3 ) {
                        acciones += `
                    
                            <button type="button" class="btn btn-success" title="Boton de terminar">
                                Terminado Sin Pago
                            </button>
                    `;
                }
                if (element.Status_id == 4 || element.Status_id == 5 || element.Status_id == 8) {
                        acciones += `
                            <button type="button" class="btn btn-success" title="Boton de autorizar">
                            TERMINADO PAGADO
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
                $('#tablarecepciones tbody').append(row);
            });
        }
        $('#search').on('input', filtering);
        function filtering() { 
            let search = $('#search').val().toLowerCase();
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
                )
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
                    restringido:true
                },
                success: function(response) {
                    $('#tablemessage').empty();
                    $.each(response.success, function(index, element) {
                        let row = $('<div class="zdflex zdmg-r05 zditemscenter">');
                        //row.append(`<button type="button"class="btn  btn-danger btn-sm" onclick="deletemessage(`+element.id+`)"><i aria-hidden="true" class="fa-solid fa-trash"></i></button>`);
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
    };
    });
</script>
@endsection