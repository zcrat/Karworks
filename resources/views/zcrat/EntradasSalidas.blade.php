@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
        <div class="card vanigrow">
            <div class="card-header">
                <i class="fa-solid fa-wallet"></i>&nbsp Entradas Y Salidas
            </div>
            <div class="card-body mycard ">
                <div class="vaniwidth vaniflex zdfd-column" id="dataupload" hidden>
                    <div class="zdflex zdjc-between">
                        <div class="zdflex zdgrow zdjc-stretch">
                            <div class='zdmg-r02'> 
                                <label for="">Modulo</label>
                                <select name="modulo" class="form-control" id="modulo">
                                    <option value="">Todos</option>
                                    <option value="3">CFE</option>
                                    <option value="4">CFB</option>
                                    <option value="5">ECO</option>
                                </select>
                            </div>
                            <div class='zdmg-r02'>    
                                <label for="">Zonas</label>
                                <select name="zona" class="form-control" id="zona">
                                    <option value="">Todos</option>
                                    @foreach ($Zonas as $zona => $name)
                                    <option value="{{ $zona }}" >{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='zdmg-r02'> 
                                <label for="">Contratos</label>
                                <select name="contrato" class="form-control" id="contrato">
                                    <option value="">Todos</option>
                                    @foreach ($Contratos as $contrato => $name)
                                        <option value="{{ $contrato }}" >{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='zdmg-r02'> 
                                <label for="">Estado</label>
                                <select name="status" class="form-control" id="status">
                                    <option value=''>Todos</option>
                                    <option value="0" selected>Entrada</option>
                                    <option value="1">Salida</option>
                                   
                                </select>
                            </div>
                            <div class="  zdmg-r02">
                                <label for="tipogasto">Fecha Inicio</label>
                                <input name="FechaInicio" id="FechaInicio" type="datetime-local" class="form-control">
                            </div>
                            <div class="  zdmg-r02">
                            <label for="tipogasto">Fecha Fin</label>
                            <input name="FechaFin" id="FechaFin" type="datetime-local" class="form-control">
                            </div>
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
                            <colgroup>
                                <col class="button_options"> 
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>OPCIONES</th>
                                    <th>EMPRESA</th>
                                    <th>ORD. SER</th>
                                    <th>ORD. SEG</th>
                                    <th>ECONOMICO</th>
                                    <th>PLACAS</th>
                                    <th>SERIE</th>
                                    <th>ENTRADA</th>
                                    <th>SALIDA</th>
                                    <th>DIAS</th>
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
        searchdata();
        function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('DetallesGenerales.Get.Elements') }}',
                data:{
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
                let presupuestoUrl =`{{ route('2025.Presupuestos.View') }}?contrato=${element.contrato}&modulo=${element.modulo}&anio=${element.anio}&zona=${element.zona}`;
                dropdownContent = `
                            <button type="button"class="opcionesdesplegables btn  btn-primary ">Opciones</button>
                                <ul class="detallesdesplegables zdw-r12 " hidden>
                                
                                <li><a href="#" onclick="OpenDetGenEntSalModal(`+element.id+`)">Editar</a></li>
                            </ul>`
                row.find('.Datatable-content').append(dropdownContent);
                row.append('<td><div class="">' + (element.empresa) + '</div></td>');
                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.OrdenServicio+'`,'+'`'+presupuestoUrl+'`)">' + (element.OrdenServicio) + '</a></div></td>');
                row.append('<td><div class="">' + (element.OrdenSeguimiento) + '</div></td>');
                row.append('<td><div class="">' + (element.economico) + '</div></td>');
                row.append('<td><div class="">' + (element.placas) + '</div></td>');
                row.append('<td><div class="">' + (element.serie) + '</div></td>');
                row.append('<td><div class="">' + (element.entrada) + '</div></td>');
                row.append('<td><div class="">' + (element.salida??'') + '</div></td>');
                row.append('<td><div class="">' + (element.dias) + '</div></td>');
                $('#tablarecepciones tbody').append(row);
            });
        }

        $('#modulo,#zona,#contrato,#FechaInicio,#FechaFin,#status').on('change', filtering);
        function filtering() { 
            let modulo = $('#modulo').val();
            let zona = $('#zona').val();
            let contrato = $('#contrato').val();
            let fechamin = $('#FechaInicio').val();
            let fechamax = $('#FechaFin').val();
            let estatus = $('#status').val();
            console.log(estatus)
            Page = 1
                elements = originalelements.filter(function(element) {
                return((modulo===''|| element.detalles_generales.modulo_id==modulo)
                        &&(zona===''|| element.zona_id==zona)
                        &&(contrato===''|| element.contrato_id==contrato)
                        &&(estatus==='' || (estatus=='0'? element.salida==null:element.salida!=null ))
                        &&(fechamin===''|| (estatus=='1'? element.salida>=fechamin:element.entrada>=fechamin))
                        &&(fechamax===''|| (estatus=='1'? element.salida<=fechamax:element.entrada<=fechamax)))
                

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
        
    });
</script>
@endsection