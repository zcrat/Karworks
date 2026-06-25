@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>TODOS LOS VALES DE ALMACEN
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
                        <div class="d-flex">
                        
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29"
                                    type="text" id="search" name="s"
                                    placeholder="Busqueda Por Ord. Servicio, Ord. Seguimiento,Folio" >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>

                            <div class="zdmg-r02">
                                <label for="estatus">Estatus</label>
                                <select name="estatus" class="form-control" id="estatus">
                                    <option value="">Todos</option>
                                    <option value="0">Sin Entregar Al Almcen</option>
                                    <option value="3">Sin Confirmar</option>
                                    <option value="1" selected>Pendientes</option>
                                    <option value="2">Terminados</option>
                                </select>
                            </div>
                        </div>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="viewelements">
                            <div class="elementosporpagina">
                                <div id='pagination'></div>
                            </div>
                            <div class="mitabla vanigrow vaniflex zdfd-column">
                                <table id="tablarecepciones" class="table table-sm  table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ord. Servicio</th>
                                            <th>Ord. Seguimiento</th>
                                            <th>Folio</th>
                                            <th>Creado</th>
                                            <th>Entregado Al almcen </th>
                                            <th>Refacciones Confirmadas </th>
                                            <th>Refacciones Entregadas</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div  class="no-results-message"  id="div-no-results-message" hidden>
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

</main>
 @include('modales.ViewValeAlmacen')
 @include('modales.DetallesValeAlmacen')
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
        searchdata();
        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Vales.Almacen.Read') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        search: $('#search').val(),
                        estatus: $('#estatus').val(),
                        tipo:0
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
        function actualizarmenusfiltros(){

        }
        window.RedirectionFolio =function(orden,ruta){
            localStorage.setItem('ordenbuscar', orden);
            window.location.href = ruta
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
                let row = $('<tr class="zdrelative"></tr>');
                let presupuestoUrl = `{{ route('2025.Recepcion.Vehicular.View') }}?contrato=${element.contrato}&modulo=${element.modulo}&anio=${element.anio}&zona=${element.zona}`;

                let acciones1 = $('<td></td>');
                let acciones = $(`<div class='zdflex gap-2' id='OpcionesSalida${index}'></div>`);

                /* ================== BOTONES SUPERIORES ================== */
                let divbtn1 = document.createElement('div');
                divbtn1.className = 'zdflex flex-wrap justify-content-center align-items-center gap-1';
                    divbtn1.append(
                    crearBoton(
                        'btn btn-info zdrelative',
                        'Ver Detalles',
                        'Ver',
                        () => OpenViewValeAlmacenModal(element.id)
                    ));
                if(element.Entregado==null){
                    divbtn1.append(
                    crearBoton(
                        'btn btn-danger zdrelative',
                        'Mensajes',
                        'Entregar Al Almacen',
                        () => EntregarVale(element.id)
                    ));
                }else{
                    if(element.Confirmado==null){
                        divbtn1.append(
                        crearBoton(
                            'btn btn-danger zdrelative',
                            'Mensajes',
                            'Confirmar Refacciones ',
                            () => ConfirmarVale(element.id)
                        ));
                    }else{
                        if(element.completado){
                            divbtn1.append(
                                crearBoton(
                                    'btn btn-success zdrelative',
                                    'Mensajes',
                                    'Entregas',
                                    () => OpenDetallesValeAlmacenModal(element.id,2)
                                ));
                        }else{
                            divbtn1.append(
                                crearBoton(
                                    'btn btn-warning zdrelative',
                                    'Mensajes',
                                    'Entregas',
                                    () => OpenDetallesValeAlmacenModal(element.id,2)
                                ));
                            }
                                
                    }
                }

                acciones.append(divbtn1);
                acciones1.append(acciones);

                let entregado = element.Entregado != null ? element.Entregado  : 'Pendiente' 
                let confirmado = element.Confirmado != null ? element.Confirmado  : 'Pendiente' 
                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.OrdenServicio+'`,'+'`'+presupuestoUrl+'`)">' + element.OrdenServicio + '</a></div></td>');
                row.append('<td><div class="">' + element.OrdenSeguimiento+ '</div></td>');
                row.append('<td><div class="">' + element.Folio + '</div></td>');
                row.append('<td><div class="">' + element.Creado + '</div></td>');
                row.append('<td><div class="">' + entregado + '</div></td>');
                row.append('<td><div class="">' + confirmado + '</div></td>');
                row.append('<td><div class="">' + element.Surtido + '</div></td>');
                row.append(acciones1);
                $('#tablarecepciones tbody').append(row);
            });
        }

        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                Page = 1;
                await searchdata();
            }, typingDelay);
        });
        $('#estatus').on('change', async function () {
            Page = 1;
            await searchdata();
        });


       window.EntregarVale = async function(id) {
            const resultado = await Swal.fire({
                title: '¿Está seguro?',
                text: "No podrás Cancelarlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Ya se Entregó al Almacen',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
            if (!resultado.isConfirmed) return;
            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Entregar') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'post',
                data: {
                    id:id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "El Vale Se Marco Como Entregado Al Almacen",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    executeSearchdata();
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al Marcar El Vale. Verifique los datos ingresados',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
         window.ConfirmarVale = async function(id) {
            const resultado = await Swal.fire({
                title: '¿Está seguro?',
                text: "No podrás Cancelarlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Ya se Confirmaron Las Refacciones',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
            if (!resultado.isConfirmed) return;
            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Surtir') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'post',
                data: {
                    id:id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "El Vale Se Marco Como Refacciones Confirmadas",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    executeSearchdata();
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al Marcar El Vale. Verifique los datos ingresados',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
    });
</script>
@endsection