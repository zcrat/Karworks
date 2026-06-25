
    <div class="modal fade" id="agregarconceptos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Conceptos</h5>
                    <button type="button" class="btn-close regresarmodal2" >
                    </button>
                </div>
                <div class="modal-body">
                    <div class="vaniflex zdjc-between">
                        <div class="select2conlabel zdw-45pct zdrelative">
                            <label>Categoria</label>
                            <select  id="Categoriaconceptos2_Select2"name="Categoriaconceptos2_Select2">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="select2conlabel zdw-45pct zdrelative">
                            <label>Tipo</label>
                            <select  id="Tipo_Vehiculo" name="Tipo_Vehiculo">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="iconoin zdmgr-r05 zdw-45pct">
                            <input class="misearch zdw-100pct"
                                type="text" id="searchservicio" name="searchservicio"
                                placeholder="Buscar por descripcion" >
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                        </div>
                    </div>
                    <div id='pagination2'></div>
                    <div id="lista" hidden>
                        <table id="tablaproductoslista" class="table table-sm  table-striped">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>ID</th>
                                    <th>Descripcion</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id='listacarga' class="carga" >
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>
                    <div  class="no-results-message2" hidden>
                        <span id="no-results-message2"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary regresarmodal2">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="{{asset('js/paginacion2.js')}}"></script>
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#agregarconceptos');
        let DisparadorOtroModal=null;
        let ThisPresupuesto=null
        let elements=[];
        let totalelements=[];
        let itemsPerPage = 10;

        $(".AddConcepto").on('click',function(){
            DisparadorOtroModal=$(this).data('function')
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisPresupuesto=$(this).attr('data-id')
            searchdata2()
            ThisModal.modal('show');
            
        });
        $(".regresarmodal2").on('click',closethismodal)
        function closethismodal(){
            ThisModal.modal('hide');
            ThisPresupuesto=null
            if(DisparadorOtroModal){
                if (typeof window[DisparadorOtroModal] === "function") {
                    window[DisparadorOtroModal]();
                }
                DisparadorOtroModal=null;
            }
            if(ModalFather){
                ModalFather.modal('show');
                ModalFather=null;
            }
            
        }
        $('#Categoriaconceptos2_Select2').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#agregarconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Available.Categories')}}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        id: ThisPresupuesto,

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
        window.searchdata2 = function(Page2=1) {
            document.getElementById('listacarga').removeAttribute('hidden');
            document.getElementById('lista').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Presupuestos.Get.CatalogosConceptos') }}',
                data:{
                    id:ThisPresupuesto,
                    categoria: $('#Categoriaconceptos2_Select2').val(),
                    tipo: $('#Tipo_Vehiculo').val(),
                    search: $('#searchservicio').val(),
                    page: Page2,
                    itemsPerPage: 10,
                },
                success: function(response) {
                    elements = response.elements;
                    totalelements = response.totalelements;
                    document.getElementById('listacarga').setAttribute('hidden', true);
                    document.getElementById('lista').removeAttribute('hidden');
                    const select = document.getElementById('Tipo_Vehiculo');
                    select.innerHTML = ''; // Clear previous options
                    select.innerHTML = '<option value=""></option>';

                    Object.entries(response.tipos).forEach(([id, nombre]) => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = nombre;
                        select.appendChild(option);
                    });

                    $(select).select2({
                        placeholder: 'Selecciona un tipo de vehículo',
                        allowClear: true,
                        dropdownParent: $("#agregarconceptos"),
                    });

                    $(select).val(response.tipo_pre).trigger('change');

                    showElements();
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        $('#searchservicio').on('input', filtering);
        $('#Categoriaconceptos2_Select2').on('change', searchdata2);
        $('#Tipo_Vehiculo').on('change', searchdata2);
        
        window.executeshowElements2 = function() {
            ShowPagination2(totalelements,5, itemsPerPage);
            $('#tablaproductoslista tbody').empty();

            $.each(elements, function(index, element) {
                let row = $('<tr>'); 
                row.append('<td><div class="Datatable-content">'+ (element.num ? element.num : "Sin Codigo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ (element.tipo_vehiculo ? element.tipo_vehiculo.nombre : "Sin tipo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ element.id  + '</div></td>');
                row.append('<td><div class="Datatable-content">'+(element.descripcion ? element.descripcion : "Sin descripcion" ) + '</div></td>');
                let col = $('<td>'); 
                col.append('<div class="Datatable-content">');
                col.append('<button type="button" class="btn btn-primary" onclick="executeAddConcepto('+element.id+','+element.producto_id+')"><i class="fa-solid fa-cart-plus"></i></button>');
                if(element.num && element.num=="FC"){ 
                    col.append('<button type="button" class="btn btn-danger" onclick="executeeliminarconcepto('+element.id+')"><i class="fa-solid fa-trash"></i></button>');
                }else{
                    col.append('<button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-trash"></i></button>');
                }
                row.append(col);
                $('#tablaproductoslista tbody').append(row);
            });
        }
        window.executeeliminarconcepto = (id)=>{
            Swal.fire({
            title: '¿Estás seguro?',
            text: "Una vez eliminado, no podrás recuperar este concepto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('Conceptos.Presupuestos.Delete')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                        },
                        success: function(response) {
                            Swal.fire('Éxito', 'El Concepto Fue Eliminado Correctamente', 'success');
                            searchdata2();
                        },
                        error: function(xhr, status, error) {
                        if(xhr.status===499){
                            Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                        }else{
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                        }
                        }
                    });
                } 
            });
        }
        window.executeAddConcepto = (id,producto)=>{
            if(!producto){
                s
            }else{
                $.ajax({
                    url: '{{ route('2025.Presupuestos.Add.Concepto') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        concepto: id,
                        Presupuesto_id: ThisPresupuesto,
                    },
                    success: function (response) {
                        mostrarToast('Producto Agregado', 'success',1000);
                        searchdata2();
                    },
                    error: function (xhr) {
                        mostrarToast(xhr.response.message, 'Error',2000);
                    },
                });
            }
            
        }
        function mostrarToast(mensaje, tipo = 'success', duracion = 3000) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: tipo, // 'success', 'warning', 'error', etc.
                title: mensaje,
                showConfirmButton: false,
                timer: duracion,
                timerProgressBar: true
            });
        }
    });
</script>
@endpush

