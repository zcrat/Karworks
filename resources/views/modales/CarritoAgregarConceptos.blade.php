
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
                                    <th></th>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>ID</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
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
                    <button type="button" class="btn btn-primary" id="agregarconceptosalista">Agregar</button>
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
        let originalelements=[];
        let productosSeleccionados= new Map();
        $(".AddConcepto").on('click',function(){
            DisparadorOtroModal=$(this).data('function')
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisPresupuesto=$(this).attr('data-id')
            productosSeleccionados= new Map();
            searchdata()
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
        window.searchdatconceptos = function(){searchdata();}
        function searchdata() {
            document.getElementById('listacarga').removeAttribute('hidden');
            document.getElementById('lista').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Presupuestos.Get.CatalogosConceptos') }}',
                data:{
                    id:ThisPresupuesto
                },
                success: function(response) {
                    console.log(response);
                    originalelements = elements = response.conceptos;
                    document.getElementById('listacarga').setAttribute('hidden', true);
                    document.getElementById('lista').removeAttribute('hidden');
                    const select = document.getElementById('Tipo_Vehiculo');
                    select.innerHTML = '<option value=""></option>';

                    // Agregar las nuevas opciones
                    Object.entries(response.tipos).forEach(([index, tipo]) => {
                        const option = document.createElement('option');
                        option.value = tipo.id;
                        option.textContent = tipo.nombre;
                        select.appendChild(option);
                    });

                    // Inicializar Select2 (si aún no está aplicado)
                    $(select).select2({
                        placeholder: 'Selecciona un tipo de vehículo',
                        allowClear: true,
                        dropdownParent: $("#agregarconceptos"),
                    });
                    $(select).val(response.tipo_pre).trigger('change');
                    filtering();
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        $('#searchservicio').on('input', filtering);
        $('#Categoriaconceptos2_Select2').on('change', filtering);
        $('#Tipo_Vehiculo').on('change', filtering);
        
        function filtering() {
            let search2 = $('#searchservicio').val().toLowerCase();
            let categoria = $('#Categoriaconceptos2_Select2').val();
            let Tipo = $('#Tipo_Vehiculo').val();
            elements = originalelements.filter(function(element) {
                return (categoria === '' || element.Categorias_id == categoria) &&(Tipo === '' || element.Tipos_id == Tipo) &&(search2 === '' || element.descripcion.toLowerCase().includes(search2));
            }); 
            if (elements.length <= 0) {
                document.querySelector('.no-results-message2').removeAttribute('hidden');
                $('#no-results-message2').text('No Se Encontraron Conceptos');
            } else {
                document.querySelector('.no-results-message2').setAttribute('hidden', true);
                $('#no-results-message2').text('');
            }
            showElements();
        }
        window.executeshowElements2 = function() {
            eval("showElements()");
        };
        function showElements() {
            ShowPagination2(elements.length,5,10);
            let startIndex = (Page2 - 1) * itemsPerPage;
            let endIndex = startIndex + itemsPerPage;
            let paginatedElements = elements.slice(startIndex, endIndex);
            console.log()
            $('#tablaproductoslista tbody').empty();
            $.each(paginatedElements, function(index, element) {
                let row = $('<tr>'); 
                row.append('<td><div class="zdh-100pct zdflex  zdpd-r05 " ><input type="checkbox" class="concepto zdw-r1 zdh-r1" data-id="'+element.id+'" data-descripcion="'+element.descripcion+'" title="Agregar" ' + (productosSeleccionados.has(element.id) ? 'checked' : '') + '></div></td>');
                row.append('<td><div class="Datatable-content">'+ (element.num ? element.num : "Sin Codigo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ (element.tipo_vehiculo ? element.tipo_vehiculo.nombre : "Sin tipo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ element.id  + '</div></td>');
                row.append('<td><div class="Datatable-content">'+(element.descripcion ? element.descripcion : "Sin descripcion" ) + '</div></td>');
                row.append('<td><div class="Datatable-content"><input type="number" value="1" class="cantidad zdw-r3" data-id="'+element.id+'"></input></div></td>');
                row.append('<td><div class="Datatable-content"><input type="number" value="1" class="precio  zdw-r5" data-id="'+element.id+'"></input></div></div></td>');
                if(element.num && element.num=="FC"){ row.append('<td><div class="Datatable-content"><button type="button" class="btn btn-danger" onclick="executeeliminarconcepto('+element.id+')"><i class="fa-solid fa-trash"></i></button></div></div></td>');}else{
                row.append('<td><div class="Datatable-content"><button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-trash"></i></button></div></div></td>');}

                $('#tablaproductoslista tbody').append(row);
            });
        }
        $(document).on('change', '.concepto', function () {
            let id = $(this).data('id');
            let cantidad = $(`.cantidad[data-id="${id}"]`).val();
            let precio = $(`.precio[data-id="${id}"]`).val(); // Obtener el valor del checkbox
            if ($(this).is(':checked')) { // Verificar si el checkbox está marcado
                productosSeleccionados.set(id, {
                    id: id,
                    cantidad: parseFloat(cantidad) || 1, // Parsear cantidad como número
                    precio: parseFloat(precio) || 1      // Parsear precio como número
                });
            } else {
                productosSeleccionados.delete(id);
            }
            console.log(Array.from(productosSeleccionados.values()));
        });
        $(document).on('input', '.precio, .cantidad', function () {
            let id = $(this).data('id');
            let cantidad = parseFloat($(`.cantidad[data-id="${id}"]`).val()) || 1;
            let precio = parseFloat($(`.precio[data-id="${id}"]`).val()) || 1;
            if (productosSeleccionados.has(id)) {
                productosSeleccionados.set(id, { 
                    id: id,
                    cantidad: cantidad,
                    precio: precio
                });
            }
        });
        $("#agregarconceptosalista").on('click',function(){
            const productosArray = Array.from(productosSeleccionados.values());
            $.ajax({
                url: '{{ route('2025.Presupuestos.add.Concepto') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    productos: productosArray,
                    Presupuesto_id: ThisPresupuesto,
                },
                success: function (response) {
                    if (response.existen.length === 0) {
                        Swal.fire('Éxito', 'Todos los productos fueron agregados correctamente.', 'success');
                    } else {
                        Swal.fire(
                            'Los siguientes productos no se agregaron ya que ya estaban registrados:',
                            `${response.existen.join('<br>')}`,
                            'warning'
                        );
                    }
                    closethismodal()
                },
                error: function () {
                    Swal.fire('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
                },
            });
        });
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
                            searchdatconceptos();
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
    });
</script>
@endpush
