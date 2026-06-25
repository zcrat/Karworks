<div class="modal fade" id="tallermodal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar Taller</h5>
                <button type="button" class="btn-close closetallermodal"></button>
            </div>
            <form id="updatedtaller">
                @csrf
                <div class="modal-body">
                    <div class=" selectconlabel zdmg-r02">
                        <label for="talle_id_changer">Taller</label>
                        <select id="talle_id_changer" name="talle_id_changer" required class="talleres_select2"></select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closetallermodal">Cerrar</button>
                    <button type="submit" id="saveTaller" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>

    $(function(){
        let ModalFather=null;
        let ThisModal=$('#tallermodal');
        let DisparadorOtroModal=null;
        let IdOrdem=null
        let guardar = $("#saveTaller")
        $('#talle_id_changer').select2({
    language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
    placeholder: 'Escribe para buscar...',
     dropdownParent: ThisModal,
    allowClear: true,
    minimumInputLength: 0,
    ajax: {
        url: '/Zcrat/Select2/Get/talleres',
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
        window.CambiarTaller=async function(id,talleractual,tallername){
            if(id && talleractual){
                IdOrdem=id;
                $('#talle_id_changer').empty().append('<option value="' + talleractual + '">' + tallername+'</option>');

                ModalFather = $('.modal.show');
                if(ModalFather){
                    ModalFather.modal('hide');
                }
                ThisModal.modal('show');
            }else{
                Swal.fire({ 
                    title: 'Error', 
                    html: `Detalles del error:<br> Datos Corrompidos`, 
                    icon: 'error',
                    timer: 1000, 
                });
            }
        };
        $(".closetallermodal").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            ThisModal.modal('hide');
            guardar.attr("disabled", false);
            if(DisparadorOtroModal){
                executeSearchdata();
                DisparadorOtroModal=null;
            }
            if(ModalFather){
                ModalFather.modal('show');
                ModalFather=null;
            }
        }
        $("#updatedtaller").submit(function(e) {
            e.preventDefault();
            ThisModal.modal("hide");
            guardar.attr("disabled", true);
            
            Swal.fire({
                    icon: "question",
                    text: "¿Estás seguro de cambiar el taller?",
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
                        let url='{{ route('2025.Recepciones.Vehiculares.Update.Taller') }}';
                        $.ajax({
                            type: 'post',
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: IdOrdem,
                                taller: $('#talle_id_changer').val()
                            },
                    success: function(response) {
                        mostramensajeexito(response.message||'Actualizado')
                        DisparadorOtroModal=true;
                        closethismodal();
                    },
                    error: function(xhr) {
                            if (xhr.status === 422) {
                                errors = xhr.responseJSON.errors;
                                message='Errores de validación:<br>';
                                let errorMessages = Object.values(errors).map((msgs) =>{return msgs.join("<br>")}).filter(Boolean).join("<br>");
                                mensajefallo('Hay Un Error En Los Datos',errorMessages??'Contacte A Soporte')
                            } else {
                                mensajefallo('Ocurrio Un Error Inesperado',xhr.responseJSON.message??'Contacte A Soporte')
                            }
                           ThisModal.modal('show');
                            guardar.attr("disabled", true);

                        }
                    });
                } else {
                    ThisModal.modal("show");
                    guardar.attr("disabled", false);
                }
                });
        });
    })
    </script>
@endpush
