<div class="modal fade" id="DetGenModModal" tabindex="-1" aria-labelledby="miModalLabel" data-bs-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel"></h5>
        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <!-- Cuerpo del modal -->
       <form id="DetGenModForm">
        <div class="modal-body">
            @csrf
            <input type="hidden" name="DetGenID" id="ConId">
            <div class="vaniflex zdfw-w">
                <div class="selectconlabel zdmgx-r02 zdw-100pct"> 
                    <label>Modulo</label>
                    <select  class="form-control" required id="modulo"name="modulo">
                        <option value="">Seleccionar</option>
                        <option value="3">CFE</option>
                        <option value="4">CFB</option>
                        <option value="5">ECO</option>
                        <option value="6">KARWORKS</option>
                    </select>
                </div>
                <div class="selectconlabel zdmgx-r02 zdw-100pct"> 
                    <label>AÑO</label>
                    <select  class="form-control"  id="anio" name="anio">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
                <div class="selectconlabel zdmgx-r02 zdw-100pct"> 
                    <label>Zona</label>
                    <select  class="form-control" required id="zona"name="zona"></select>
                </div>
                <div class="selectconlabel zdmgx-r02 zdw-100pct"> 
                    <label>Contrato</label>
                    <select  class="form-control" required id="contrato"name="contrato"></select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" id="ButtonSaveDetGenModModal" class="btn btn-primary">Guardar</button>
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
            dropdownParent: $("#DetGenModModal"),
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
                        anio:$('#anio').val()
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
            dropdownParent: $("#DetGenModModal"),
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
                        anio:$('#anio').val()
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
        $('#modulo, #zona ,#anio').on('change', function() {
            $('#contrato').empty().trigger('change');
        });
        $('#modulo,#anio').on('change', function() {
            $('#zona').empty().trigger('change');
        });
        window.OpenDetGenModModal = function(id, modulo, zona,zonalabel, contrato,contratolabel,anio) {
            $('#DetGenModModal').modal('show');
            $('#miModalLabel').text('Modificar Detalles Generales');
            $('#ConId').val(id);
            $('#modulo').val(modulo).trigger('change');
            $('#anio').val(anio);
            $('#zona').empty().append('<option value="' + zona + '">' + zonalabel+ '</option>');
            $('#contrato').empty().append('<option value="' + contrato + '">' + contratolabel+ '</option>');
            $('#DetGenModModal').modal('show');
        };
        $('#DetGenModForm').submit(function(e){
            e.preventDefault();
            let ruta="{{ route('Detalles.Generales.Update.Modulo') }}";
            let form= $("#DetGenModForm");
            let data=  form.serialize();
            let modal=$("#DetGenModModal");
            let guardar=$("#ButtonSaveDetGenModModal")
            guardar.attr("disabled", true);
            Swal.fire({
                icon: "question",
                text: "¿Estás Seguro de Guardar el Cambio?",
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
                            title:data.message ,
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
    });
    </script>
@endpush