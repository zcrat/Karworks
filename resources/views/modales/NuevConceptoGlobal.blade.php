<div class="modal fade" id="ConceptosPresupuestoModal" tabindex="-1" aria-labelledby="miModalLabel" data-bs-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="ConceptosPresupuestoTitle">Catalogo de Conceptos</h5>
        <button type="button" class="btn-close ConceptosPresupuestoClose" aria-label="Cerrar"></button>
      </div>
      <!-- Cuerpo del modal -->
       <form id="ConceptosPresupuestoForm">
      <div class="modal-body">
        @csrf
        <input type="hidden" name="ConPreId" id="ConPreId">
        <div class="vaniflex">
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>Categoria Sat</label>
                <select id="ConPreCatSat"name="ConPreCatSat" required>
            </select>
            </div>
            <div class="selectconlabel">
                <label>Codigo Sat</label>
                <input id="ConPreCodSat" class="form-control" disabled>
            </div>
        </div>
        <div class="vaniflex">
            <div class="select2conlabel zdmg-r03 zdminw-45pct">
                <label for="ConPreUniSat">Unidad</label>
                <select id="ConPreUniSat" name="ConPreUniSat"></select>
            </div>
            <div class="select2conlabel zdmg-r03">
                <label for="ConPreCodUni">Codigo Unidad</label>
                <input id="ConPreCodUni" class="form-control" disabled value="H87" type="text">
            </div>
            <div class="select2conlabel zdmg-r03">
                <label for="ConPreCod">Codigo</label> 
                <input id="ConPreCod" name="ConPreCod" class="form-control" type="text" value="FC">
            </div>
        </div>
        <div class="vaniflex zdjc-between">
            <div class="select2conlabel zdw-45pct zdrelative">
                <label>Categoria</label>
                <select required id="ConPreCat"name="ConPreCat"></select>
            </div>
            <div class="select2conlabel zdrelative zdw-45pct">
                <label>Tipos</label>
                <select required id="ConPreTip"name="ConPreTip"></select>
            </div>
        </div>
        <div class="vaniflex zdjc-between zdfw-w">
            <div class="selectconlabel zdw-30pct">
            <label>P. Refaccion</label>
            <input required class="form-control"  type="number" id="ConPrePreRef" step="0.01" name="ConPrePreRef">
            </div>
            <div class="selectconlabel zdw-30pct">
            <label>P.M.O.</label>
            <input required class="form-control"  type="number" id="ConPrePreMan" name="ConPrePreMan">
            </div>
            <div class="selectconlabel zdw-30pct">
            <label>P. Total</label>
            <input class="form-control"  type="number" id="ConPrePreTot" disabled>
            </div>
        </div>
        <div class="textareaconlabel zdw-100pct">
            <label>Descripcion</label>
            <textarea required class="form-control"  name="ConPreDes" id="ConPreDes"></textarea>
        </div>
       
      </div>
      <!-- Pie del modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ConceptosPresupuestoClose">Cerrar</button>
        <button type="submit" id="ConceptosPresupuestoSave" class="btn btn-primary">Guardar</button>
      </div>
    </form>
    </div>
  </div>
</div>
@push('scripts')
    <script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#ConceptosPresupuestoModal');
        let DisparadorOtroModal = null;
        
        $(".NuevoConcepto").on('click',async function(){
            DisparadorOtroModal=$('#'+$(this).data('disparador'))
            OpenNewConcepts();
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisModal.modal('show');
        });
        window.OpenNewConcepts = function(){
            $('#ConceptosPresupuestoForm input').not('input[name="_token"]').val('').trigger('change');
            $('#ConceptosPresupuestoForm select').val('').trigger('change'); 
            $("#ConceptosPresupuestoForm").find(".error-message").remove();
        }
        $(".ConceptosPresupuestoClose").on('click',function(){
            closethismodal();
        })
        function closethismodal(){
            ThisModal.modal('hide');
            DisparadorOtroModal=null;
            if(ModalFather){
                ModalFather.modal('show');
                ModalFather=null;
            }
        }

        $('#ConPreCatSat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#ConceptosPresupuestoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Categories.Sat') }}',
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
        $('#ConPreUniSat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#ConceptosPresupuestoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Units.Sat') }}',
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
        $('#ConPreTip').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#ConceptosPresupuestoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Available.Types.Concepts') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo:modulo,
                        anio:anio,
                        contrato:contrato,
                        zona:zona,
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
        $('#ConPreCat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#ConceptosPresupuestoModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Categories.Concepts') }}',
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
        $('#ConPreUniSat').on('change',function(){
            if($(this).val()){
                $.ajax({
                    type: 'GET',
                    url: '{{ route('Sat.Get.Code.Unidad') }}',
                    data:{
                        id: $(this).val(),
                    },
                    success: function(response) {
                        $("#ConPreCodUni").val(response.Code)
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                }); 
            }else{
                $("#ConPreCodUni").val('')
            }
            
        })
        $('#ConPreCatSat').on('change',function(){
            if($(this).val()){
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Facturar.Get.Codigo_Sat') }}',
                    data:{
                        id: $(this).val(),
                    },
                    success: function(response) {
                        $("#ConPreCodSat").val(response.Code)
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                }); 
            }else{
                    $("#ConPreCodSat").val('')
            }
        })
        $('#ConPrePreRef, #ConPrePreMan').on('change',function(){
                let Refaccion = parseFloat($('#ConPrePreRef').val()) || 0; 
                let ManoObra = parseFloat($('#ConPrePreMan').val()) || 0; 
                let Total = Refaccion + ManoObra
                $('#ConPrePreTot').val(Total);
        });
        $("#ConceptosPresupuestoForm").submit(async function(e){
            e.preventDefault();
            const thisform = $(this);
            const formData = new FormData(this);
            formData.append('modulo', modulo);
            formData.append('contrato', contrato);
            formData.append('zona', zona);
            formData.append('anio', anio);
            const rutacreate="{{route('Conceptos.Presupuestos.Create')}}";
            const rutaedit="{{route('Conceptos.Presupuestos.Update')}}";
            let ruta=null;
            if($('#ConPreId').val()){
                ruta=rutaedit
            }else{
                 ruta=rutacreate
            }
            const button=$("#ConceptosPresupuestoSave");

            Swal.fire({
                icon: "question",
                text: "¿Estás Seguro?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn-primary",
                    cancelButton: "btn-light",
                },
            }).then(async (result) => {
                if (result.isConfirmed) {
                    button.attr('disabled', true);
                    try {
                        const response = await $.ajax({
                            url: ruta,
                            type: 'post',
                            data: formData,
                            processData: false,
                            contentType: false,
                        });
                        Swal.fire({
                            title: 'Éxito',
                            html: response.message,
                            icon: 'success',
                            timer: 1000
                        });
                        closethismodal();
                    } catch (xhr) {
                        console.error(xhr)
                        if (xhr.status === 422) {
                            thisform.find(".error-message").remove();
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).map((msgs) => {
                                if (msgs && msgs !== "Este campo es obligatorio." && msgs !== "La opción no es válida") {
                                    return msgs.join("<br>");
                                }
                            }).filter(Boolean).join("<br>");
                            for (let field in errors) {
                                let input = thisform.find(`[name="${field}"]`);
                                let errorMessage = `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                                input.after(errorMessage);
                            }
                            Swal.fire({
                                icon: "warning",
                                title: "Información",
                                html: errorMessages,
                                timer: 10000
                            });
                        } else {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                    } finally {
                        button.removeAttr('disabled');
                    }
                }
            });
        });
    })
    
    </script>
@endpush