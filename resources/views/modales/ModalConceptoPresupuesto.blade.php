
    <div class="modal fade" id="AgregarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Conceptos</h5>
                    <button type="button" class="btn-close CerrarModalAgregarProducto" >
                    </button>
                </div>
                <div class="modal-body">
                    <div class="vaniflex zdjc-between">
                        <div class="select2conlabel zdw-45pct zdrelative">
                            <label>Priveedor</label>
                            <select  id="Proveedorselect2"name="Proveedorselect2">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="iconoin zdmgr-r05 zdw-45pct">
                            <input class="misearch zdw-100pct"
                                type="text" id="searchproducto" name="searchproducto"
                                placeholder="Buscar por descripcion" >
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                        </div>
                    </div>
                    <div id='pagination2'></div>
                    <div id="listaProductos" hidden>
                        <table id="tablaproductoslistaProductos" class="table table-sm  table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Clave</th>
                                    <th>Proveedor</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id='listaProductoscarga' class="carga" >
                        <h3 class="text-center m-2">Cargando Datos</h3>
                        <div class="spinnerp"></div>
                    </div>
                    <div  class="no-results-message2" hidden>
                        <span id="no-results-message2"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary CerrarModalAgregarProducto">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="AgregarProductoalistaProductos">Agregar</button>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="{{asset('js/paginacion_C_V.js')}}"></script>
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#AgregarProducto');
        let DisparadorOtroModal=null;
        let ThisConcepto=null
        let elements=[];
        let totalElements=[];
        let productosSeleccionados= new Map();

        $(".AddProduct").on('click',function(){
            DisparadorOtroModal=$(this).data('function')
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisConcepto=$(this).attr('data-id')
            productosSeleccionados= new Map();
            SearchProductos()
            ThisModal.modal('show');
            
        });
        window.openmodallistaproductos=function(conseptoid, functionname=null){
            DisparadorOtroModal= functionname;
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisConcepto=conseptoid
            productosSeleccionados= new Map();
            SearchProductos()
            ThisModal.modal('show');
            
        };

        $(".CerrarModalAgregarProducto").on('click',closethismodal)
        function closethismodal(){
            ThisModal.modal('hide');
            ThisConcepto=null
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
        $('#Proveedorselect2').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#AgregarProducto"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{route('Select2.Get.Proveedores')}}',
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
                                text: item.nombre,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
        window.SearchProductos = function (page = 1) {
            document.getElementById('listaProductoscarga').removeAttribute('hidden');
            document.getElementById('listaProductos').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('Productos.Almacen.Get') }}',
                data:{
                    Search: $('#searchproducto').val(),
                    Proveedor: $('#Proveedorselect2').val(),
                    Page: page,
                    itemsPerPage: 10,
                },
                success: function(response) {
                    document.getElementById('listaProductoscarga').setAttribute('hidden', true);
                    document.getElementById('listaProductos').removeAttribute('hidden');
                    elements = response.conceptos;
                    totalElements = response.totalElements;
                    ShowListaConceptos();
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        $('#searchproducto').on('input', () => {
            SearchProductos();
        });
        $('#Proveedorselect2').on('change', () => {
            SearchProductos();
        });
        
        window.ShowListaConceptos = function() {
            PaginatioCustomerServer(totalelements,5,10);
            $('#tablaproductoslistaProductos tbody').empty();
            $.each(elements, function(index, element) {
                let row = $('<tr>'); 
                row.append('<td><div class="zdh-100pct zdflex  zdpd-r05 " ><input type="checkbox" class="productoalmacen zdw-r1 zdh-r1" data-id="'+element.id+'" data-descripcion="'+element.descripcion+'" title="Agregar" ' + (productosSeleccionados.has(element.id) ? 'checked' : '') + '></div></td>');
                row.append('<td><div class="Datatable-content">'+element.clave + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ (element.proveedor ? element.proveedor.nombre : "Sin tipo" ) + '</div></td>');
                row.append('<td><div class="Datatable-content">'+element.descripcion+ '</div></td>');
                row.append('<td><div class="Datatable-content"><input type="number" value="'+(productosSeleccionados.has(element.id) ? productosSeleccionados[element.id].cantidad ??1: 1) +'" class="cantidadproducto zdw-r3" data-id="'+element.id+'"></input></div></td>');
                
                $('#tablaproductoslistaProductos tbody').append(row);
            });
        }
        $(document).on('change', '.productoalmacen', function () {
            let id = $(this).data('id');
            let cantidad = $(`.cantidadproducto[data-id="${id}"]`).val();
            if ($(this).is(':checked')) { // Verificar si el checkbox está marcado
                productosSeleccionados.set(id, {
                    id: id,
                    cantidad: parseFloat(cantidad) || 1,
                });
            } else {
                productosSeleccionados.delete(id);
            }
        });
        $(document).on('input', '.cantidadproducto', function () {
            let id = $(this).data('id');
            let cantidad = parseFloat($(`.cantidadproducto[data-id="${id}"]`).val()) || 1;
            if (productosSeleccionados.has(id)) {
                productosSeleccionados.set(id, { 
                    id: id,
                    cantidad: cantidad,
                });
            }
        });
        $("#AgregarProductoalistaProductos").on('click',function(){
            const productosArray = Array.from(productosSeleccionados.values());
            $.ajax({
                url: '{{ route('2025.Concepto.add.Productos') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    productos: productosArray,
                    concepto_id: ThisConcepto,
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
    });
</script>
@endpush

