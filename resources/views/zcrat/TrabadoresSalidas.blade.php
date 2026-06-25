@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
        <div class="card vanigrow">
            <div class="card-header">
                <i class="fa-solid fa-wallet"></i>&nbsp Reporte de Salidas Por Tecnico
            </div>
            <div class="card-body mycard ">
                <div class="vaniwidth vaniflex zdfd-column" id="dataupload" hidden>
                    <div class="zdflex zdjc-between">
                        <div class="zdflex zdgrow zdjc-stretch">
                            <div class="  zdmg-r02">
                                <label for="">  </label>
                                <div class="iconoin zdmgr-r05 ">
                                    <input class="misearch zdw-r29" type="text" id="search" name="s"
                                        placeholder="Busqueda Por #Orden, Tecnico y Economico">
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                                </div>
                            </div>
                            <div class='zdmg-r02'> 
                                <label for="">Estado</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="0" selected>Terminados</option>
                                    <option value="1">Pendientes</option>
                                   
                                </select>
                            </div>
                            <div class="  zdmg-r02">
                                <label for="tipogasto">Fecha Inicio</label>
                                <input name="FechaInicio" id="FechaInicio" type="date" class="form-control">
                            </div>
                            <div class="  zdmg-r02">
                            <label for="tipogasto">Fecha Fin</label>
                            <input name="FechaFin" id="FechaFin" type="date" class="form-control">
                            </div>
                            <button type='button'  id="ExportarDatos" class='btn btn-success'  onclick="reporteexcel()">Exportar</button>
                        </div>
                       
                    </div>
                    <div class="elementosporpagina">
                        <select   class="rounded" id="epp">
                            <option value="10" >10</option>
                            @for ($i = 15; $i <= $elementostotales/3; $i += 5)
                                <option value="{{ $i }}" >{{ $i }}</option>
                            @endfor
                        </select>
                        <div id='pagination'></div>
                    </div>
                    <div class="mitabla vanigrow vaniflex zdfd-column" id='viewelements'>
                        <table id="tablarecepciones" class="table table-sm  table-striped">
                            <thead>
                                <tr>
                                    <th>ORD. SER</th>
                                    <th>ECONOMICO</th>
                                    <th>TECNICO</th>
                                    <th>DESCRIPCION</th>
                                    <th>ENTRADA</th>
                                    <th>DIAGNOSTICO</th>
                                    <th>PEDIDO REF.</th>
                                    <th>ENTREGA REF.</th>
                                    <th>SALIDA</th>
                                    <th>HORAS</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                    <div  class="no-results-message" hidden>
                        <span id="no-results-message"></span>
                    </div>
                </div>
                <div id='loadingdata' class="carga" >
                    <h3 class="text-center m-2">Cargando Datos</h3>
                    <div class="spinnerp"></div>
                </div>
            </div>
        </div>
   </div>
</main>

@include('modales.EntradasSalidas')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('scripts')
<script src="{{asset('js/paginacion.js')}}"></script>
<script>
    $(function() {
        let elements = [];
        let originalelements = [];
        const NumerosFormat = new Intl.NumberFormat("es-MX", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        let fechaActual = new Date().toLocaleString("sv-SE", { timeZone: "America/Mexico_City" }).split(" ")[0];
        console.log( new Date().now)
        $("#FechaInicio").val(fechaActual);
        $("#FechaFin").val(fechaActual);


        searchdata();
        function searchdata(fecha=null) {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('DetallesGenerales.Salidas.Get.Elements') }}',
                data:{fecha:fecha
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

        window.RedirectionFolio =function(folio,ruta){
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
                let row = $('<tr class="zdrelative"></tr>');
                let presupuestoUrl =`{{ route('2025.Recepcion.Vehicular.View') }}?contrato=${element.contrato}&modulo=${element.modulo}&anio=${element.anio}&zona=${element.zona}`;
                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.OrdenServicio+'`,'+'`'+presupuestoUrl+'`)">' + (element.OrdenServicio) + '</a></div></td>');
                row.append('<td><div class="">' + (element.economico) + '</div></td>');
                row.append('<td><div class="">' + (element.tecnico) + '</div></td>');
                row.append('<td><div class="">' + (element.descripcion) + '</div></td>');
                row.append('<td><div class="">' + (element.entrada) + '</div></td>');
                row.append('<td><div class="">' + (element.diagnostico  ?? 'No registrado') + '</div></td>');
                row.append('<td><div class="">' + (element.pedidohecho ?? 'No registrado') + '</div></td>');
                row.append('<td><div class="">' + (element.pedidoentregado ?? 'No registrado') + '</div></td>');
                row.append('<td><div class="">' + (element.salida  ?? 'No registrado') + '</div></td>');
                row.append('<td><div class="">' + (element.horas) + '</div></td>');
                $('#tablarecepciones tbody').append(row);
            });
        }
$('#search').on('input', filtering);
        $('#FechaInicio,#FechaFin,#status').on('change', filtering);
        function filtering() { 
            let fechamin = $('#FechaInicio').val();
            let fechamax = $('#FechaFin').val();
            let estatus = $('#status').val();
            let fechaMin = fechamin ? fechamin + " 00:00:00" : null; // 00:00:00
            let fechaMax = fechamax ? fechamax + " 23:59:59" : null; // 23:59:59

            let search = $('#search').val();
            console.log(fechaMax)
            Page = 1
                elements = originalelements.filter(function(element) {
                    
                return( (estatus=='0' ? element.salida !=null : element.salida==null) && 
                        (fechamin===''|| (estatus=='0' ?  element.salida >=fechaMin : element.entrada >=fechaMin))&&
                        (fechamax===''|| (estatus=='0' ?  element.salida <=fechaMax: element.entrada<=fechaMax)) && 
                        (search === '' || element.economico.toLowerCase().includes(search.toLowerCase())||
                            element.tecnico.toLowerCase().includes(search.toLowerCase()) ||
                            element.OrdenServicio.toLowerCase().includes(search.toLowerCase())
                        )
                    )
                

                });
                
            if (elements.length === 0) {
                document.querySelector('.no-results-message').removeAttribute('hidden');
                $('#no-results-message').text('No Se Encontraron Presupuestos' );
                
            } else {
                document.querySelector('.no-results-message').setAttribute('hidden',true);
                $('#no-results-message').text('');
            }
            showElements();
            
        }
    window.reporteexcel= function(){
        let url= '{{ route('reporte.Salidas.Tecnicos') }}'
        $.ajax({
                type: 'post',
                url: url,
                data:{_token: "{{ csrf_token() }}",listasalidas:elements},
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