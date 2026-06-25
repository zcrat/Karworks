@extends ('layouts.admin2')
@section ('contenido')
<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Catalogo Coceptos
                    <!-- <button type="button"  class="boton1" onclick="limpiarmodaliye()">
                        <i class="fa-solid fa-circle-plus"></i>&nbsp;Nuevo
                    </button> -->
                    <div id="submenu"></div>
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
                        <div class="d-flex">
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r15"
                                    type="text" id="search" name="s"
                                    placeholder="Busqueda Por Concepto" >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>AÑO</label>
                                    <select  class="form-control"  id="aniofilter">
                                        <option value="">Todos</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>MODULOS</label>
                                <select  id="modulofilter">
                                <option value="">Todos</option>
                                </select>
                            </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>ZONAS</label>
                                <select  id="zonafilter"> <option value="">Todos</option></select>
                            </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>CONTRATOS</label>
                                <select  id="contratofilter"> <option value="">Todos</option></select>
                            </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>VEHICULOS</label>
                                <select  id="vehiculofilter"> <option value="">Todos</option></select>
                            </div>
                            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                                <label>CATEGORIA</label>
                                <select  id="ConCatFil"> <option value="">Todos</option></select>
                            </div>
                        </div>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="viewelements">
                            <div class="elementosporpagina">
                                <select   class="rounded" id="epp">
                                <option value="21" >21</option>
                                    @for ($i = 15; $i <= $elementostotales/3; $i += 5)
                                        <option value="{{ $i }}" >{{ $i }}</option>
                                    @endfor
                                </select>
                                <div id='pagination'></div>
                            </div>
                            <div id='cardstable' class="vanigrow vaniflex zdfd-column">
                                
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
   
    @include('modales.conceptoglogalmodal')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacionv1.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>
    $(function(){
        $('#modulofilter').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url:"{{route('Select2.Get.Modulos')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: $('#modulofilter').val(),
                        contrato: $('#contratofilter').val(),
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
        $('#ConCatFil').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Categories.Concepts')}}",
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
        $('#zonafilter').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Zonas')}}",
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
        $('#contratofilter').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Contratos')}}",
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
        $('#vehiculofilter').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.TiposConceptos')}}",
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
        let elements = [];
        let total = 0;

        async function searchdata(page = 1) {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            await $.ajax({
                type: 'GET',
                url: '{{ route('2025.Conceptos.Get.All.Elements') }}',
                data: {
                    'search': $('#search').val(),
                    'modulo': $('#modulofilter').val(),
                    'zona': $('#zonafilter').val(),
                    'contrato': $('#contratofilter').val(),
                    'vehiculo': $('#vehiculofilter').val(),
                    'categoria': $('#ConCatFil').val(),
                    'anio': $('#aniofilter').val(),
                    'itemsperpage': $('#epp').val(),
                    'page': page
                },
                success: function(response) {
                    elements = response.Conceptos;
                    total =  response.total;
                    document.getElementById('loadingdata').setAttribute('hidden', true);
                    document.getElementById('dataupload').removeAttribute('hidden');
                    showElements()
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        window.executeSearchdata = function(page) {
           eval("searchdata(" + page + ")");
        };
        executeSearchdata();
        window.executeshowElements = function() {
            eval("showElements()");
        };
        function showElements() {
            ShowPagination(total,8);
            $('#cardstable').empty();
            let table = $('<div class="tablacard vanigrow">');
            $.each(elements, function(index, element) {
                let row = $('<div class="cardelement ">');
                row.append('<label class="zdbold">' + element.ConPreDes + '</label>');
                row.append('<label>' + element.ConPreCatName + '</label>');
                row.append('<label>' + element.contratoName + '</label>');
                row.append('<label>' + (element.anio+'  '+element.ConPreCod ?? 'FC'+'  '+element.moduloName ) + '</label>');
                row.append('<label>' + element.ConPreTipName + '</label>');
                row.append('<label>' + element.ConPreCatSatName + '</label>');
                row.append('<label>' + (element.ConPreUniSatName+'  '+ element.ConPreUniSatCode) + '</label>');
                row.append('<label>Total: ' + element.ConPreTotal + '</label>');
                row.append(`<div class="zdrelative"><button type="button"class="opcionesdesplegables btn  btn-primary ">Opciones</button>
                    <ul class="detallesdesplegables ajustedeposicionmenudespleglaple zdw-r12" hidden>
                        <li><a href="#" onclick="executeeliminarconcepto(`+element.id+`)" ">Eliminar</a></li>
                        <li><a href="#" onclick="executeeditarconcepto(`+element.id+`)">Editar</a></li>
                    </ul></div>`);
                table.append(row);
            });
            $('#cardstable').append(table);
        }
        let typingTimer;
        const typingDelay = 1000; // 1 segundo

        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                await searchdata();
                 setTimeout(() => $('#search').focus(), 0); 
            }, typingDelay);
        });
        $('#modulofilter').on('change', ()=>{executeSearchdata();});
        $('#contratofilter').on('change', ()=>{executeSearchdata();});
        $('#zonafilter').on('change', ()=>{executeSearchdata();});
        $('#ConCatFil').on('change', ()=>{executeSearchdata();});
        $('#vehiculofilter').on('change', ()=>{executeSearchdata();});
        $('#aniofilter').on('change', ()=>{executeSearchdata();});

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
                                executeSearchdata();
                            },
                            error: function(xhr, status, error) {
                            if(xhr.status===499){
                                Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                            }else{
                                let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: <br>${xhr.responseJSON.message}`, icon: 'error'});
                            }
                            }
                        });
                    } 
                });
            }
            window.limpiarmodaliye = function() {
            $("#nuevosconceptos").find(".error-message").remove();
            $('#nuevosconceptos input').not('input[name="_token"]').val('').trigger('change');
            $('#nuevosconceptos select').val('').trigger('change'); 
            $('#nuevosconceptos').modal('show');  
        }
        window.executeeditarconcepto = (id)=>{
            $.ajax({
                url: "{{route('2025.Conceptos.Get.Data.Element')}}",
                type: "get",
                data:{
                    id:id,
                },
                success: function(response) {
                    
                    let concepto=response.element;
                    $('#anio').val(concepto.anio).trigger('change');
                    $('#modulo').val(concepto.modulo).trigger('change');
                    $("#contrato").empty().append('<option value="' + concepto.contrato + '">' + concepto.contratoName+'</option>');
                    $("#zona").empty().append('<option value="' + concepto.zona + '">' + concepto.zonaName+'</option>');
                    $("#ConCatSat").empty().append('<option value="' + concepto.ConPreCatSat + '">' + concepto.ConPreCatSatName+'</option>');
                    $('#ConCodSat').val(concepto.ConPreCatSatClave);
                    $("#ConUniSat").empty().append('<option value="' + concepto.ConPreUniSat + '">' + concepto.ConPreUniSatName + '</option>');
                    $('#ConCodUni').val(concepto.ConPreUniSatCode);
                    $('#ConCod').val(concepto.ConPreCod);
                    $("#ConCat").empty().append('<option value="' + concepto.ConPreCat + '">' + concepto.ConPreCatName + '</option>');
                    $("#ConTipVeh").empty().append('<option value="' + concepto.ConPreTip + '">' + concepto.ConPreTipName + '</option>');
                    $('#ConPreRef').val(concepto.ConPreRef);
                    $('#ConPreMO').val(concepto.ConPreMO);
                    $('#ConPreTot').val(concepto.ConPrePreTot);
                    $('#ConDes').val(concepto.ConPreDes);
                    $('#ConId').val(concepto.ConPreId);
                    $("#miModalLabel").text('Editar Concepto')
                    $('#nuevosconceptos').modal('show');
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
            window.limpiarmodaliye = function() {
            $("#nuevosconceptos").find(".error-message").remove();
            $('#nuevosconceptos input').not('input[name="_token"]').val('').trigger('change');
            $('#nuevosconceptos select').val('').trigger('change'); 
            $('#nuevosconceptos textarea').val(''); 
            $('#nuevosconceptos').modal('show');  
        }
    })
</script>
@endsection