<div class="modal fade" id="usuarioStore" tabindex="-1" role="dialog" aria-labelledby="usuarioStoreLabel"
    data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalusertitle">Nuevo cliente</h5>
                <button type="button" class="btn-close closenewcustomer">
                </button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    @csrf
                    <input id="customer-id" class="form-control" type="hidden" name="customer-id">
                    <div class="form-group">
                        <div class="input-group">
                            <i class="fas fa-building"></i>
                            <label class="encabezadomodal ">Empresa</label>
                            <select id="customer-idempresa" class="form-control" name="customer-idempresa" required>
                               
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer-usuario">Nombre:<i class="ml-2 color-required fas fa-asterisk"></i></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input id="customer-usuario" class="form-control" type="text" name="customer-usuario"
                                placeholder="Ej. Alberto Esquivias Flores" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer-direccion">Dirección:<i
                                class="ml-2 color-required fas fa-asterisk"></i></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <input id="customer-direccion" class="form-control" type="text" name="customer-direccion"
                                placeholder="Ej. C. PUERTO DE ACAPULCO NO. 328, COL. TINIJARO, C.P. 58337" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer-ciudad">Ciudad:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input id="customer-ciudad" class="form-control" type="text"
                                        name="customer-ciudad" placeholder="Ej. Morelia" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer-estado">Estado:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input id="customer-estado" class="form-control" type="text"
                                        name="customer-estado" placeholder="Ej. Michoacán" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customer-cp">C.P.:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input id="customer-cp" class="form-control" type="text" name="customer-cp"
                                        placeholder="Ej. 58000" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="customer-tel_casa">Tel Casa:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input id="customer-tel_casa" class="form-control" type="text"
                                        name="customer-tel_casa" placeholder="Ej. 4431040746" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer-tel_oficina">Tel Oficina:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input id="customer-tel_oficina" class="form-control" type="text"
                                        name="customer-tel_oficina" placeholder="Ej. 4431040746" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer-tel_celular">Tel Celular:<i
                                        class="ml-2 color-required fas fa-asterisk"></i></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input id="customer-tel_celular" class="form-control" type="text"
                                        name="customer-tel_celular" placeholder="Ej. 4431040746" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer-email">Email:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-mail-forward"></i>
                            </div>
                            <input id="customer-email" class="form-control" type="email" name="customer-email"
                                placeholder="Ej. designapp.mx@gmail.com" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closenewcustomer"
                    data-dismiss="modal">Cancelar</button>
                <button type="submit" id="newuser" form="customerForm" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    
    $(function(){ 
        let ModalFather=null;
        let ThisModal=$('#usuarioStore');
        let DisparadorOtroModal=null;
        let form= $("#customerForm");
        $('#customer-idempresa').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            dropdownParent: ThisModal,
            ajax: {
                    url: '/select2/obtenerempresas',
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
        $(".NewCustomer").on('click',function(){
            DisparadorOtroModal=$(this).data('function')
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            resetinputs();
            ThisModal.modal('show');
            
        });
        $(".closenewcustomer").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            ThisModal.modal('hide');

            if(DisparadorOtroModal){
                DisparadorOtroModal=null;
            }
            if(ModalFather){
                ModalFather.modal('show');
                ModalFather=null;
            }
        }
        function resetinputs(){
            $('#customer-id').val('');
            $('#customer-idempresa').empty();
            $('#customer-usuario').val('');
            $('#customer-direccion').val('');
            $('#customer-ciudad').val('');
            $('#customer-estado').val('');
            $('#customer-cp').val('');
            $('#customer-tel_casa').val('');
            $('#customer-tel_oficina').val('');
            $('#customer-tel_celular').val('');
            $('#customer-email').val('');
        }
        $('#customerForm').submit(function(e){
            e.preventDefault();
            SaveUsuarioStore();
        })
        window.SaveUsuarioStore = function(idconcepto){
            Swal.fire({
                title: '¿Está seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Guardar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let data=  form.serialize();
                    $.ajax({
                        type: 'post',
                        url: '{{ route('cliente.register') }}',
                        data: data ,
                        success: function(response) {
                            mostramensajeexito(response.message??'Guardado Correctamente');
                            closethismodal();
                        },
                        error: function(xhr, status, error) {
                           if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).map((msgs) => {
                                    return msgs.join("<br>");
                                    // if (msgs && msgs !== "Este campo es obligatorio." && msgs !== "La opción no es válida") {
                                    // }
                                }).filter(Boolean).join("<br>");
                                Swal.fire({
                                    icon: "warning",
                                    title: "Información",
                                    html: errorMessages,
                                    timer: 10000
                                });
                            } else {
                                mensajefallo('Error: ' + (xhr.status ?? "Desconocido"),xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte");
                            }
                        }
                            
                    })
                };
            });
        };
});
</script>
@endpush