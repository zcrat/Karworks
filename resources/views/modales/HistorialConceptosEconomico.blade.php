<div class="modal fade" id="HisConEcoModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered zdmw-95pct ">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="HisConEcoModel-title">Historial De Conceptos Por Vehiculo</h5>
                <button type="button" class="btn-close CloseHisConEcoModel"></button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <div  class="zdmw-100pct zdflex zdfd-column">
                    <div class="zdmw-100pct zdflex zdfd-row">
                        <div class="select2conlabel zdw-50pct zdcambiartamanio zdrelative">
                            <label for="">Vehiculo <span class="spanrelleno">#Econonomico - Placas - vin</span></label>
                            <select  id="HisConEcoModel-vehiculo" name="HisConEcoModel-vehiculo" required></select>
                        </div>
                        <div class="select2conlabel zdw-50pct zdcambiartamanio zdrelative">
                            <label for="HisConEcoModel-Orden">Orden Servicio</label>
                            <select  id="HisConEcoModel-Orden" name="HisConEcoModel-Orden" required></select>
                        </div>
                    </div>
                    <div class="zdmw-100pct zdflex zdfd-row">
                        <div>
                            <label for="HisConEcoModel-FechaInicio"></label>
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29"
                                type="text" id="HisConEcoModel-search" name="HisConEcoModel-search"
                                placeholder="Busqueda por Concepto" >
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                        </div>
                        <div class=" zdmg-r02">
                            <label for="HisConEcoModel-FechaInicio">Fecha Inicio</label>
                            <input name="HisConEcoModel-FechaInicio" id="HisConEcoModel-FechaInicio" type="date" class="form-control">
                        </div>
                        <div class=" zdmg-r02">
                            <label for="HisConEcoModel-FechaFin">Fecha Fin</label>
                            <input name="HisConEcoModel-FechaFin" id="HisConEcoModel-FechaFin" type="date" class="form-control">
                        </div>
                    </div>

                </div>
                <div>
                    <div class="vaniwidth vaniflex zdfd-column" id="HisConEcoModel-dataupload" hidden>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="HisConEcoModel-viewelements">
                            <div class="elementosporpagina">
                                <select   class="rounded" id="HisConEcoModel-epp">
                                    <option value="5" >5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15" >15</option>
                                    <option value="20" >20</option>
                                </select>
                                <div id='HisConEcoModel-pagination'></div>
                            </div>
                            <div class="mitabla vanigrow vaniflex zdfd-column">
                                <table id="HisConEcoModel-table" class="table table-sm  table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ord. Servicio</th>
                                            <th>Codigo</th>
                                            <th>Cantidad</th>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Costo</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div  id="HisConEcoModel-no-results-message-div" class="no-results-message">
                        <span id="HisConEcoModel-no-results-message">Selecciona Un Vehiculo</span>
                        </div>
                    </div>
                    <div id='HisConEcoModel-loadingdata' class="carga" hidden >
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseHisConEcoModel">Cerrar</button>
                <button type="button" class="btn btn-primary" id="HisConEcoModel-save" onclick="ExportarHistorialConceptos()">Exportar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{asset('js/paginacionGlobal.js')}}"></script>
<script>
    $(function(){

        const thismodal=$('#HisConEcoModel');
        const thistable=$('#HisConEcoModel-table');
        const search=$('#HisConEcoModel-search');
        const fecha1=$('#HisConEcoModel-FechaInicio');
        const fecha2=$('#HisConEcoModel-FechaFin');
        const ordenes=$('#HisConEcoModel-Orden');
        const vehiculo=$('#HisConEcoModel-vehiculo');

        let ModalOneAttributeFather=null;
        let DisparadorOtroModal=null;
        let IdVehiculo=null;
        let itemsPerPage = 10;
        let currentPage = 1;
        let totalElements=0;
        let elements=[];

        $(".OpenHisConEcoModel").on('click',function(){
            DisparadorOtroModal=$(this).data('disparador')
            ModalOneAttributeFather = $('.modal.show');
            if(ModalOneAttributeFather){
                ModalOneAttributeFather.modal('hide');
            }
            if(IdVehiculo){
                vehiculo.val('').trigger('change');
            }else{
                reset()
            }
            thismodal.modal('show');
        });

        function reset(){
            document.getElementById('HisConEcoModel-loadingdata').setAttribute('hidden', true);
            document.getElementById('HisConEcoModel-dataupload').removeAttribute('hidden');
            IdVehiculo=null;
            itemsPerPage = 10;
            currentPage = 1;
            totalElements=0;
            elements=[];
            fecha1.val("");
            fecha2.val("");
            search.val("");
            showElements(elements,'HisConEcoModel',"Selecciona Un Vehiculo");
        }
        $(".CloseHisConEcoModel").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            thismodal.modal('hide');
            if(DisparadorOtroModal){
                DisparadorOtroModal=null;
            }
            if(ModalOneAttributeFather){
                ModalOneAttributeFather.modal('show');
                ModalOneAttributeFather=null;
            }
        }
        vehiculo.select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#HisConEcoModel"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Vehicles.History')}}",
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
                                text: item.label,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
        ordenes.select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#HisConEcoModel"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Vehicles.Ordenes')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        id:IdVehiculo
                    };
                    return query;
                },
                delay: 500,
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.label,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
        vehiculo.on('change',function(){
            IdVehiculo=$(this).val()
            ordenes.empty()
            if(IdVehiculo){
                BuscarHistorialConceptos();
            }else{
                reset() 
            }
        })
        fecha1.on('change',function(){
            BuscarHistorialConceptos();
        })
        fecha2.on('change',function(){
            BuscarHistorialConceptos();
        })
        ordenes.on('change',function(){
            BuscarHistorialConceptos();
        })
        let typingTimer;
        const typingDelay = 1500; // 1 segundo

        search.on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                BuscarHistorialConceptos();
            }, typingDelay);
        });
        $('#HisConEcoModel-pagination').on('click', '.pagina', function() {
            currentPage = parseInt($(this).data('page'));
            BuscarHistorialConceptos();
        });
        $('#HisConEcoModel-epp').change(function() {
            itemsPerPage = parseInt($(this).val(), 10);
            BuscarHistorialConceptos();
        });
        window.ExportarHistorialConceptos= function(){
            if(IdVehiculo){

                let url= '{{ route('Vehiculos.Conceptos.Historial.Exportar') }}'
                let fechamin = fecha1.val();
                let fechamax = fecha2.val();
                fechamin = fechamin ? fechamin + " 00:00:00" : '';
                fechamax = fechamax ? fechamax + " 23:59:59" : '';
            $.ajax({
                    type: 'post',
                    url: url,
                    data:{_token: "{{ csrf_token() }}",
                    search: search.val().toLowerCase(),
                    fechamin: fechamin,
                    fechamax: fechamax,
                    orden:ordenes.val(),
                    vehiculo:IdVehiculo
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
        }
        window.BuscarHistorialConceptos= function() {
            document.getElementById('HisConEcoModel-loadingdata').removeAttribute('hidden');
            document.getElementById('HisConEcoModel-dataupload').setAttribute('hidden', true);
            let fechamin = fecha1.val();
            let fechamax = fecha2.val();
            fechamin = fechamin ? fechamin + " 00:00:00" : '';
            fechamax = fechamax ? fechamax + " 23:59:59" : '';
            $.ajax({
                type: 'GET',
                url: '{{ route('Vehiculos.Get.Conceptos.Historial') }}',
                data: {
                    search: search.val().toLowerCase(),
                    fechamin: fechamin,
                    fechamax: fechamax,
                    itemsPerPage: itemsPerPage,
                    currentPage: currentPage,
                    orden:ordenes.val(),
                    vehiculo:IdVehiculo
                },
                success: async function (response) {
                    elements = response.elements;
                    totalElements = response.totalelements;
                    document.getElementById('HisConEcoModel-loadingdata').setAttribute('hidden', true);
                    document.getElementById('HisConEcoModel-dataupload').removeAttribute('hidden');
                    showElements(elements,'HisConEcoModel',response.message??"");
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        function showElements(elements,idhtmml,messageemmpty) {
            ShowPaginationGlobal(totalElements,8,itemsPerPage,currentPage,idhtmml+'-pagination');
            $('#'+idhtmml+'-table tbody').empty();

            if (totalElements > 0) {
                document.getElementById(idhtmml+'-viewelements').removeAttribute('hidden');
                document.getElementById(idhtmml+'-no-results-message-div').setAttribute('hidden',true);
            } else {
                document.getElementById(idhtmml+'-viewelements').setAttribute('hidden', true);
                document.getElementById(idhtmml+'-no-results-message-div').removeAttribute('hidden');
            }
            $('#'+idhtmml+'-no-results-message').text(messageemmpty);
            $.each(elements, function(index, element) {
                let row = $('<tr class="zdrelative"></tr>');
                row.append('<td><div class="">' + (element.orden ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.codigo ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.cantidad ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.fecha ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.concepto ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.costo ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.precio ?? "" ) + '</div></td>');
                row.append('<td><div class="">' + (element.total ?? "" ) + '</div></td>');
                 $('#'+idhtmml+'-table tbody').append(row);
            });
        }

    });
</script>
@endpush