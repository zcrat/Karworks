<div class="modal fade" id="ModuloCortanaModal" tabindex="-1" aria-labelledby="miModalLabel" data-bs-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Encabezado del modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel"></h5>
        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <!-- Cuerpo del modal -->
       <form id="ModuloCortanaForm">
        <div class="modal-body">
            @csrf
            <input type="hidden" name="DetGenID" id="ConId">
            <div class="vaniflex zdfw-w">
                <div class="selectconlabel zdmgx-r02 zdw-100pct"> 
                    <label>Modulo Cortana</label>
                    <select  class="form-control" required id="modulo_cortana"name="modulo_cortana"></select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" id="ButtonSaveModuloCortanaModal" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    </div>
  </div>
</div>
@push('scripts')
    <script>
    $(function(){
        $('#modulo_cortana').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#ModuloCortanaModal"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
            url: "{{route('Select2.Get.Modulos.Cortana')}}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term
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
                                id: item.value
                            };
                        })
                    };
                },
                cache: true
            }
        });
        window.OpenChangerModuloCortana = function(id, id_modulo, descripciom) {
            $('#miModalLabel').text('Modificar Modulo');
            $('#ConId').val(id);
            $('#modulo_cortana').empty().append('<option value="' + id_modulo + '">' + descripciom+ '</option>');
            $('#ModuloCortanaModal').modal('show');
        };
        $('#ModuloCortanaForm').submit(function(e){
            e.preventDefault();
            let ruta="{{ route('Detalles.Generales.Changer.Modulo.Cortana') }}";
            let form= $("#ModuloCortanaForm");
            let data=  form.serialize();
            let modal=$("#ModuloCortanaModal");
            let guardar=$("#ButtonSaveModuloCortanaModal")
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