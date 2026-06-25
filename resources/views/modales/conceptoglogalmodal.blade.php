<div class="modal fade" id="nuevosconceptos" tabindex="-1" aria-labelledby="miModalLabel" data-bs-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel"></h5>
        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <!-- Cuerpo del modal -->
       <form id="FormConcept">
        <div class="modal-body">
            @csrf
        
        <input type="hidden" name="ConId" id="ConId">
        <div class="vaniflex">
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>Modulo</label>
                <select  class="form-control" required id="modulo"name="modulo">
                    <option value="">Seleccionar</option>
                    <option value="3">CFE</option>
                    <option value="4">CFB</option>
                    <option value="5">ECO</option>
                    <option value="6">KARWORKS</option>
                </select>
            </div>
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>AÑO</label>
                <select  class="form-control"  id="anio" name="anio">
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>Zona</label>
                <select  class="form-control" required id="zona"name="zona"></select>
            </div>
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>Contrato</label>
                <select  class="form-control" required id="contrato"name="contrato"></select>
            </div>
        </div>
        <div class="vaniflex">
            <div class="selectconlabel zdmgx-r02 zdw-70pct"> 
                <label>Categoria SAT</label>
                <select required id="ConCatSat"name="ConCatSat">
                    <option value=""></option>
            </select>
            </div>
            <div class="selectconlabel">
                <label>
                    Codigo Sat
                </label>
                <input id="ConCodSat" class="form-control" disabled>
            </div>
        </div>
        <div class="vaniflex">
            <div class="select2conlabel zdmg-r03"><label for="">Unidad</label><select class="form-control" name="ConUniSat" id="ConUniSat"></select></div>
            <div class="select2conlabel zdmg-r03"><label for="">Codigo Unidad</label><input id="ConCodUni" class="form-control" disabled type="text"></div>
            <div class="select2conlabel zdmg-r03"><label for="">Codigo</label> <input name="ConCod" id="ConCod" class="form-control" type="text" value="FC"></div>
        </div>
        <div class="vaniflex zdjc-between">
            <div class="select2conlabel zdw-45pct zdrelative">
                <label>Categoria</label>
                <select required id="ConCat"name="ConCat">
                    <option value=""></option>
                </select>
            </div>
            <div class="select2conlabel zdrelative zdw-45pct">
                <label>Tipos</label>
                <select required id="ConTipVeh"name="ConTipVeh">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="vaniflex zdjc-between zdfw-w">
            <div class="selectconlabel">
            <label>P. Refaccion</label>
            <input required class="form-control"  type="number" id="ConPreRef" step="0.01" name="ConPreRef">
            </div>
            <div class="selectconlabel">
            <label>P.M.O.</label>
            <input required class="form-control"  type="number" id="ConPreMO" name="ConPreMO">
            </div>
            <div class="selectconlabel">
            <label>P. Total</label>
            <input class="form-control"  type="number" id="ConPreTot" disabled>
            </div>
        </div>
        <div class="textareaconlabel zdw-100pct">
            <label>Descripcion</label>
            <textarea required class="form-control"  name="ConDes" id="ConDes"></textarea>
        </div>
       
        </div>
      <!-- Pie del modal -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" id="ButtonSaveConcept" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    </div>
  </div>
</div>
@push('scripts')
    <script>
    $(function(){
        $('#contrato').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Available.Contratos')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: $('#modulo').val(),
                        zona: $('#zona').val(),
                        anio: $('#anio').val(),
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
        $('#zona').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Available.Zonas')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: $('#modulo').val(),
                        anio: $('#anio').val(),
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
        $('#ConTipVeh').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Available.Types.Concepts')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                        modulo: $('#modulo').val(),
                        contrato: $('#contrato').val(),
                        anio: $('#anio').val(),
                        zona: $('#zona').val(),
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
        $('#ConCat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
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
        $('#ConUniSat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Units.Sat')}}",
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
        $('#ConCatSat').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#nuevosconceptos"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "{{route('Select2.Get.Categories.Sat')}}",
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
        $('#ConCatSat').on('change',function(){
            if($(this).val()){
            $.ajax({
            type: 'GET',
            url: '{{ route('2025.Facturar.Get.Codigo_Sat') }}',
            data:{
                id: $(this).val(),
            },
            success: function(response) {
                $("#ConCodSat").val(response.Code)
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
            });
            }else{
                $("#ConCodSat").val('')
            }
        })
        $('#ConUniSat').on('change',function(){
            if($(this).val()){
                $.ajax({
                    type: 'GET',
                    url: '{{ route('Sat.Get.Code.Unidad') }}',
                    data:{
                        id: $(this).val(),
                    },
                    success: function(response) {
                        console.log(response)
                        $("#ConCodUni").val(response.Code)
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                }); 
            }
        })
        $('#modulo, #zona, #contrato, #anio').on('change', function() {
        $('#ConTipVeh').empty().trigger('change');
        });
        $('#modulo, #zona, #anio').on('change', function() {
        $('#contrato').empty().trigger('change');
        });
        $('#modulo, #anio').on('change', function() {
            $('#zona').empty().trigger('change');
        });

        $('#FormConcept').submit(function(e){
            e.preventDefault();
            let ruta=($('#ConId').val() ? "{{ route('Conceptos.Presupuestos.Update') }}" : "{{ route('Conceptos.Presupuestos.Create.Global') }}");
            let form= $("#FormConcept");
            let data=  form.serialize();
            let modal=$("#nuevosconceptos");
            let guardar=$("#ButtonSaveConcept")
            guardar.attr("disabled", true);
            Swal.fire({
                icon: "question",
                text: "¿Estás seguro de guardar el concepto?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn-primary",
                    cancelButton: "btn-light",
                },
            })
            .then((result) => {
                if (result.isConfirmed) {
                $(".error-message").remove();
                var $request = $.post(ruta,data);
                $request.done(function(data) {
                    guardar.attr("disabled", false);
                   
                        Swal.fire({
                            icon: "success",
                            title:data.suucess ,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        modal.modal("hide");
                        executeSearchdata();
                    
                });
                $request.fail(function(error) {
                    guardar.attr("disabled", false);
                    if (error.status === 422) {
                    form.find(".error-message").remove();
                        let errors = error.responseJSON.errors;
                        let errorMessages = Object.values(errors)
                            .map((msgs) => msgs.join("<br>"))
                            .join("<br>");
                        for (let field in errors) {
                        let input = form.find(`[name="${field}"]`);
                        let errorMessage = `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                        input.after(errorMessage);
                        }
                        modal.modal("show");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ocurrió un error inesperado",
                        }).then(() => {
                            modal.modal("show");
                        
                        });
                    }
                });
                } else {
                    modal.modal("show");
                    guardar.removeAttr("disabled");
                    
                }
        });

        })
        $('#ConPreRef, #ConPreMO').on('change',function(){
         let prefaccionVal = parseFloat($('#ConPreRef').val()) || 0; 
            let pmoVal = parseFloat($('#ConPreMO').val()) || 0; 
            let total = prefaccionVal + pmoVal
            $('#ConPreTot').val(total);
        });
    });
    </script>
@endpush