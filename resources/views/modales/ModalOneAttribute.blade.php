<div class="modal fade" id="OneAttributeModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="OneAttributeTitle">Nuevo</h5>
                <button type="button" class="btn-close OneAttributeClose"></button>
            </div>

            <!-- Cuerpo del Modal -->
             <form id="OneAttributeForm" method="POST">
                @csrf            
            <div class="modal-body">
                <div class=" selectconlabel zdmg-r02">
                    <label for="atributo" id="OneAttributeLabel">Nombre<strong>*</strong></label>
                    <input class="form-control" type="text" id="OneAttributeInput" name="OneAttributeInput" required>
                    <input class="form-control" type="hidden" id="OneAttributeOrigin" name="OneAttributeOrigin" required>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary OneAttributeClose">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="OneAttributeSave">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    //Logica para Cerrar Modal anidado
    $(function(){
        let ModalOneAttributeFather=null;
        let DisparadorOtroModal=null;
        let origin, title, label,atribute2;
        $(".NewElementOneAttribute").on('click',function(){
           
            origin=$(this).data("origin");
            title=$(this).data("title");
            label=$(this).data("label");
            atribute2=$(this).data("atribute2") || null; // Atributo adicional opcional
            if(origin && title && label){
                if(origin=='ModeloVehiculo'){
                    if(!atribute2){
                        Swal.fire({ 
                            title: 'Error', 
                            html: `Detalles del error:<br> La Marca es requerida para crear un Modelo de Vehiculo`, 
                            icon: 'error',
                            timer: 1000, 
                        });
                        return;
                    }
                }
                $("#OneAttributeTitle").text(title);
                $("#OneAttributeLabel").text(label);
                $("#OneAttributeOrigin").val(origin);
                $("#OneAttributeInput").val('');
                DisparadorOtroModal=$('#'+$(this).data('select2'))
                ModalOneAttributeFather = $('.modal.show');
                if(ModalOneAttributeFather){
                    ModalOneAttributeFather.modal('hide');
                }
                $('#OneAttributeModal').modal('show');
            }else{
                Swal.fire({ 
                    title: 'Error', 
                    html: `Detalles del error:<br> Datos Corrompidos`, 
                    icon: 'error',
                    timer: 1000, 
                });
            }
        });
        $(".OneAttributeClose").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            $('#OneAttributeModal').modal('hide');
            if(DisparadorOtroModal){
                DisparadorOtroModal=null;
            }
            if(ModalOneAttributeFather){
                ModalOneAttributeFather.modal('show');
                ModalOneAttributeFather=null;
            }
        }
        $("#OneAttributeForm").submit(async function(e){
            e.preventDefault();
            const thisform = $(this);
            const formData = new FormData(this);
            if(origin=='ModeloVehiculo'){
                formData.append('marca', atribute2);
            }
            const ruta="{{route('2025.Create.Element.OneAttribute')}}";
            const button=$("#OneAttributeSave");

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
                        if(DisparadorOtroModal){
                            DisparadorOtroModal.empty().append('<option value="' + response.id + '">' + response.nombre + '</option>').val(response.id).trigger('change');
                        }
                        closethismodal();
                    } catch (xhr) {
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
    });
</script>
@endpush