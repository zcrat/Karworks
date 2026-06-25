@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
        <div class="card vanigrow">
            <div class="card-header">
                <i class="fa-solid fa-wallet"></i>Almacen
                <button class="btn btn-success" onclick="newentradainventario()"><i class="fa-solid fa-circle-plus"></i>&nbsp; Entrada</button>
                <button class="btn btn-success" onclick="newsalidainventario()"><i class="fa-solid fa-circle-minus"></i>&nbsp; Salida</button>
            </div>
            <div class="card-body mycard ">
                <div class="vaniwidth vaniflex zdfd-column">
                    <div class="zdflex zdjc-between zdfd-column">
                        <div class="select2conlabel zdw-100pct" >
                                <label for="producto" >Producto Almacen<strong>*</strong></label>
                                <select id="producto"name="producto" required></select>
                            </div>
                        <div class="zdflex zdgrow zdjc-stretch">
                            <div class="  zdmg-r02">
                                <label for="">  </label>
                                <div class="iconoin zdmgr-r05 ">
                                    <input class="misearch zdw-r29" type="text" id="search" name="s"
                                         placeholder="Busqueda Por Ord. Servicio,Placas, Vin, Economico Y Proveedor" >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                                </div>
                            </div>
                            @if(auth()->user()->can('ver.taller.1') && auth()->user()->can('ver.taller.2'))
                                <div class="zdmg-r02">
                                    <label for="taller">Taller</label>
                                    <select name="taller" class="form-control" id="taller">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Altozano</option>
                                        <option value="2">Quiroga</option>
                                    </select>
                                </div>
                            @endcan
                            <div class="zdmg-r02">
                                <label for="tipo">TIPO</label>
                                <select name="tipo" class="form-control" id="tipo">
                                    <option value="">Todos</option>
                                    <option value="0">Salidas</option>
                                    <option value="1">Entradas</option>
                                    <option value="2">Compras</option>
                                </select>
                            </div>
                            <div class="zdmg-r02">
                                <label for="tipoproducto">T. Producto</label>
                                <select name="tipoproducto" class="form-control" id="tipoproducto">
                                    <option value="">Todos</option>
                                    <option value="1">Refacciones</option>
                                    <option value="2">Herramientas</option>
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
                        </div>
                       
                    </div>
                    <div  id="dataupload" hidden>
                        <div class="elementosporpagina">
                            <select  class="rounded" id="epp">
                                <option value="10" >10</option>
                                @for ($i = 15; $i <= 65; $i += 5)
                                    <option value="{{ $i }}" >{{ $i }}</option>
                                @endfor
                            </select>
                            <div id='pagination'></div>
                        </div>
                        <div class="mitabla vanigrow vaniflex zdfd-column" id='viewelements'>
                            <table id="tablaelementos" class="table table-sm  table-striped">
                                <thead>
                                    <th>Id</th>
                                    <th>#Num_Parte</th>
                                    <th>Descripcion</th>
                                    <th>Taller</th>
                                    <th>Cantidad</th>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                    <th>Proveedor</th>
                                    <th>#Orden</th>
                                    <th>Vehiculo</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    <div class="no-results-message" id="div-no-results-message">
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
   </div>
</main>
@include('modales.SalidaInventario');
@include('modales.EntradaInventario');
@stack('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacionv1.js')}}"></script>
<script>
     $(function() {
        let elements = [];
        let titles = [];
        let totalelements = 0;
        let Page = 1;
        let itemsPerPage = 10;
        let typingTimer;
        const typingDelay = 1000;

        searchdata();
        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                Page = 1;
                await searchdata();
            }, typingDelay);
        });
        $('#tipo').on('change',function(){
           executeSearchdata();
        })
        $('#FechaInicio').on('change',function(){
           executeSearchdata();
        })
        $('#FechaFin').on('change',function(){
           executeSearchdata();
        })
        $('#producto').on('change',function(){
           executeSearchdata();
        })
        $('#taller').on('change',function(){
           executeSearchdata();
        })
        $('#tipoproducto').on('change',function(){
           executeSearchdata();
        })
        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            
            return new Promise((resolve, reject) => {
                let x ='';
                try {
                   x= $('#taller').val();
                } catch (error) {
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Almacen.Read.Movimientos') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        producto: $('#producto').val(),
                        fecha1: $('#FechaInicio').val(),
                        fecha2: $('#FechaFin').val(),
                        search: $('#search').val(),
                        tipo: $('#tipo').val(),
                        tipoproducto: $('#tipoproducto').val(),
                        taller: x
                    },
                    success: function(response) {
                        elements = response.elements;
                        totalelements = response.totalelements;
                        console.log(totalelements);
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
        window.executeshowElements = function() {
            eval("showElements()");
        };
        window.executeSearchdata = function(newpage = 1,changePage= true) {
            if(changePage){
                Page = newpage;
            }
            eval("searchdata()");
        };
        function showElements() {
            ShowPagination(totalelements,8,Page);
            const user_id = {{ auth()->id() }}

            $('#tablaelementos tbody').empty();

            if (totalelements > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
                document.getElementById('div-no-results-message').setAttribute('hidden', true);
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
                document.getElementById('div-no-results-message').removeAttribute('hidden');
                if($('#search').val()==''){
                    $('#no-results-message').text('No Hay Resultados Disponibles');
                }else{
                    $('#no-results-message').text('No Se Encontraron Resultados Con  '+$('#search').val() );
                }
            }
            let rows=$('')
            $.each(elements, function(index, element){
                let row = $('<tr></tr>');
                    let acciones1 = $('<td></td>');
                    let acciones = $(`<div class='zdflex gap-2'></div>`);

                    acciones.append(
                        crearBoton(
                            'btn btn-danger btn-sm zdrelative',
                            'Eliminar Producto', 
                            '<i class="fa fa-trash"></i>',
                            () => DeleteMovimiento(element.id)
                        )
                    );
                    if(element.tipo != 'Salida'){
                        acciones.append(
                            crearBoton(
                                'btn btn-warning btn-sm zdrelative',
                                'Editar Entrada o Compra', 
                                '<i class="fa fa-pencil"></i>',
                                () => editmovimientoentrada(element.id)
                            )
                        );

                    }
                    acciones1.append(acciones);
                    row.append('<td><div class="">' + element.id+ '</div></td>');
                    row.append('<td><div class="">' + element.parte+ '</div></td>');
                    row.append('<td><div class="">' + element.descripcion+ '</div></td>');
                    row.append('<td><div class="">' + element.taller+ '</div></td>');
                    row.append('<td><div class="">' + element.cantidad+ '</div></td>');
                    row.append('<td><div class="">' + element.tipo+ '</div></td>');
                    row.append('<td><div class="">' + element.precio+ '</div></td>');
                    row.append('<td><div class="">' + element.proveedor+ '</div></td>');
                    row.append('<td><div class="">' + element.orden+ '</div></td>');
                    row.append('<td><div class="">' + element.vehiculo+ '</div></td>');
                    row.append('<td><div class="">' + element.fecha+ '</div></td>');
                    row.append(acciones1);
                $('#tablaelementos tbody').append(row);
            });
        }


        window.DeleteMovimiento=function (id){
             Swal.fire({
                title: '¿Estás seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('2025.Almacen.Delete.Movimiento')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                        },
                        success: function(response) {
                            mostramensajeexito(response.message ?? 'eliminado')
                            executeSearchdata();
                        },
                        error: function(error) {
                            mensajefallo('Ocurrio Un Error Inesperado',error.responseJSON.message??'Contacte A Soporte')
                        }
                    });
                } 
            });
        }
        $('#producto').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar por descripcion o clave...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Productos.Inventarios') }}',
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
                                text: item.descripcion,
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
@endsection