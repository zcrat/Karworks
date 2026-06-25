@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
        <div class="card vanigrow">
            <div class="card-header">
                <i class="fa-solid fa-wallet"></i>Almacen
                <button class="btn btn-success" onclick="newproductoinventario()"> <i class="fa-solid fa-circle-plus"></i>&nbsp; Nuevo</button>
            </div>
            <div class="card-body mycard ">
                <div class="vaniwidth vaniflex zdfd-column">
                    <div class="zdflex ">
                        <div class="zdflex">
                            <div class="  zdmg-r02">
                                <label for="">  </label>
                                <div class="iconoin zdmgr-r05 ">
                                    <input class="misearch zdw-r29" type="text" id="search" name="s"
                                        placeholder="Busqueda Por Descripcion, Codigo o Marca">
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                                </div>
                            </div>
                            
                        </div>
                        @if(auth()->user()->can('ver.taller.1') && auth()->user()->can('ver.taller.2'))
                            <div class="zdmg-r02">
                                <label for="taller">Taller</label>
                                <select name="taller" class="form-control" id="taller">
                                    <option value="">Todos</option>
                                    <option value="1">Altozano</option>
                                    <option value="2">Quiroga</option>
                                </select>
                            </div>
                        @endcan
                        <div class="zdmg-r02">
                                <label for="estatus">Estatus Existencias</label>
                                <select name="estatus" class="form-control" id="estatus">
                                    <option value="">Todos</option>
                                    <option value="1">🟢 Sobradas</option>
                                    <option value="2">🟡 Medias</option>
                                    <option value="3">🔴 Bajas</option>
                                </select>
                            </div>
                       
                        <div class="zdmg-r02">
                                <label for="tipo">Tipo Producto</label>
                                <select name="tipo" class="form-control" id="tipo">
                                    <option value="">Todos</option>
                                    <option value="1">Refacciones</option>
                                    <option value="2">Herramientas</option>
                                </select>
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
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th>Inicio</th>
                                    <th>Precio</th>
                                    <th>IVA</th>
                                    <th>Costo</th>
                                    <th>Entradas</th>
                                    <th>Salidas</th>
                                    <th>Existencias</th>
                                    <th>Precio</th>
                                    <th>IVA</th>
                                    <th>COSTO</th>
                                    <th>Provedor</th>
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
@include('modales.ProductoInventario');
@include('modales.EntradaInventario');
@include('modales.SalidaInventario');
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
        $('#taller').on('change', function () {
            searchdata();
        });
        $('#estatus').on('change', function () {
            searchdata();
        });
        $('#tipo').on('change', function () {
            searchdata();
        });
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
                    url: '{{ route('2025.Almacen.Read.Inventario') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        search: $('#search').val(),
                        estatus: $('#estatus').val(),
                        tipo: $('#tipo').val(),
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
                    let acciones = $(`<div class='zdflex gap-2 zdfw-w'></div>`);

                    acciones.append(
                        crearBoton(
                            'btn btn-danger btn-sm zdrelative',
                            'Eliminar Producto', 
                            '<i class="fa fa-trash"></i>',
                            () => DeleteProducto(element.id)
                        )
                    );
                    acciones.append(
                        crearBoton(
                            'btn btn-warning btn-sm zdrelative',
                            'Editar Producto', 
                            '<i class="fa fa-pencil"></i>',
                            () => newproductoinventario(element.id)
                        )
                    );
                    acciones.append(
                        crearBoton(
                            'btn btn-info btn-sm zdrelative',
                            'Entrada Producto', 
                            '<i class="fas fa-plus"></i>',
                            () => newentradainventario(element.id,element.descripcion)
                        )
                    );
                    acciones.append(
                        crearBoton(
                            'btn btn-info btn-sm zdrelative',
                            'Salida Producto', 
                            '<i class="fas fa-minus"></i>',
                            () => newsalidainventario(element.id,element.descripcion)
                        )
                    );
                    acciones1.append(acciones);
                    const clase=element.estatus == 1 ? 'imagenes_completas' : (element.estatus == 2 ? 'imagenes_proceso' : 'imagenes_incompletas') ;
                    row.append('<td><div class="">' + element.id+ '</div></td>');
                    row.append('<td><div class="">' + element.codigo+ '</div></td>');
                    row.append('<td><div class="">' + element.descripcion+ '</div></td>');
                    row.append('<td><div class="">' + element.tipo+ '</div></td>');
                    row.append('<td><div class="">' + element.marca+ '</div></td>');
                    row.append('<td><div class="">' + element.inicio+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.precio)+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.iva)+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.final)+ '</div></td>');
                    row.append('<td><div class="">' + zdformatnumber(element.entradas)+ '</div></td>');
                    row.append('<td><div class="">' + zdformatnumber(element.salidas)+ '</div></td>');
                    row.append('<td><div class="'+clase+' color-white">' + zdformatnumber(element.inventario)+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.precio2)+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.iva2)+ '</div></td>');
                    row.append('<td><div class="bg-info">' + zdformatnumber(element.final2)+ '</div></td>');
                    row.append('<td><div class="">' + element.proveedor+ '</div></td>');
                    row.append('<td><div class="">' + element.fecha+ '</div></td>');
                    row.append(acciones1);
                $('#tablaelementos tbody').append(row);
            });
        }

        window.DeleteProducto=function (id){
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
                        url: "{{route('2025.Almacen.Delete.Producto')}}",
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
    });
</script>
@endsection