@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>usuarios del sistema
                    <button type="button"  class="boton1" onclick="OpenUserAdmin(null)">
                        <i class="fa-solid fa-circle-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body mycard zdfd-column">
                    <div class="d-flex">
                        <div class="iconoin zdmgr-r05">
                            <input class="misearch zdw-r15"
                                type="text" id="search" name="s"
                                placeholder="Busqueda por Usuario" >
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                        </div>
                    </div>
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
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
                                <table id="tablamovimientos" class="table table-sm  table-striped">
                                    <thead>
                                        <tr>
                                            <th>N#</th>
                                            <th>Usuario</th>
                                            <th>Nombre</th>
                                            <th>Taller</th>
                                            <th>Fecha Registro</th>
                                            <th>Acciones</th>
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
</main>

    
    @include('modales.UserEdit')
    @include('modales.TallerUser')
    @include('modales.ModalOneAttribute')

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
        let itemsPerPage = parseInt($('#epp').val()) || 8;
        let typingTimer;
        const typingDelay = 1000; // 1 segundo
        searchdata();
        $('#epp').on('change', function() {
            itemsPerPage = parseInt($(this).val());
            Page = 1; 
            searchdata();
        });
        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('User.GetAll') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        search: $('#search').val(),
                        estatus: $('#estatus').val(),
                    },
                    success: function(response) {
                        elements = response.elements;
                        totalelements = response.totalelements;
                        console.log(elements);
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
        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                Page = 1;
                await searchdata();
            }, typingDelay);
        });
         window.executeSearchdata = function(newpage = 1,changePage= true) {
            if(changePage){
                Page = newpage;
            }
            eval("searchdata()");
        };
        window.executeshowElements = function() {
            eval("showElements()");
        };
        function showElements() {
            ShowPagination(totalelements,8,Page);
            $('#tablamovimientos tbody').empty();
            if (totalelements > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
            }
            
            $.each(elements, function(index, element) {
                const tallename=element.taller_id == 1 ? 'Altozano' : (element.taller_id == 2 ? 'Puerto De Acapulco(Quiroga)' : 'Externo');
                let row = $('<tr class="zdrelative">');
                row.append('<td><div class="">' + (element.id) + '</div></td>');
                row.append('<td><div class="">' + (element.usuario) + '</div></td>');
                row.append('<td><div class="">' + (element.name) + '</div></td>');
                row.append('<td><div class="">' + (element.taller) + '</div></td>');
                row.append('<td><div class="">' + (element.fecha ?? 'no registrada') + '</div></td>');
                let acciones1 = $('<td></td>');
                let acciones = $(`<div class='zdflex gap-2' id='OpcionesSalida${index}'></div>`);

                /* ================== BOTONES SUPERIORES ================== */
                let divbtn1 = document.createElement('div');
                divbtn1.className = 'zdflex flex-wrap justify-content-center align-items-center gap-1';
                divbtn1.append(
                    crearBoton(
                        'btn btn-warning btn-sm zdrelative',
                        'Cambiar Taller',
                        '<i class="fas fa-wrench"></i>',
                        () => CambiarTallerUser(element.id,element.taller_id,element.taller)
                    )
                );

                divbtn1.append(
                    crearBoton(
                        'btn btn-warning zdrelative',
                        'Mensajes',
                        'Permisos',
                        () => OpenUserAdmin(element.id)
                    )
                );
                
                
                acciones.append(divbtn1);
                acciones1.append(acciones);
                row.append(acciones1)
                $('#tablamovimientos tbody').append(row);
            });
        }
    });
</script>
@endsection