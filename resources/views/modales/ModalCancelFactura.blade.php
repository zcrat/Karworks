<div id="ModalCancelFacturas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"
    class="modal fade fade bd-example-modal-lg mostrarCancel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Registro de Motivos de Cancelación</h5>
                <button type="button" class="btn-close ModalCancelFacturasClose"></button>
            </div>
            <form id="FormCancelFacturas">
                @csrf
            <div class="modal-body">
                <input type="hidden" id="CanFacId" name="CanFacId" value="">
                <p>Para cada CFDI debe capturar el motivo de cancelación</p>
                <div class="form-group row">
                    <div class="col-12 col-md-8">
                        <label for="CanFacMot" class="col-12 col-md-12 form-control-label">
                            Motivo de cancelación</label>
                        <select required name="CanFacMot" id="CanFacMot" class="col-12 col-md-12 form-control">
                            <option value="">Seleccione el motivo</option>
                            <option value="01">01 - Comprobantes emitidos con errores con relación</option>
                            <option value="02">02 - Comprobantes emitidos con errores sin relación</option>
                            <option value="03">03 - No se llevó a cabo la operación</option>
                            <option value="04">04 - Operación nominativa relacionada en una factura global</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="CanFacFol" class="col-12 col-md-12 form-control-label">Folio Relacionado</label>
                        <input type="text" class="form-control" id='CanFacFol' name='CanFacFol' hidden>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary ModalCancelFacturasClose">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="CanFacSave">Cancelar Factura</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function(){
        let ModalFather=null;
        let DisparadorOtroModal=null;
        let ThisModal= $('#ModalCancelFacturas');
        $(".ModalCancelFacturasClose").on('click',function(){closethismodal();});
        function closethismodal(){
            ThisModal.modal('hide');
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
        $(document).on('click', ".OpenCancelarFactura", function() {
            factura=$(this).data("factura");
            $('#CanFacSave').attr('disabled', false);
            if(factura){
                $('#FormCancelFacturas').trigger("reset");
                $('#CanFacFol').prop('hidden', true);
                $("#CanFacId").val(factura);
                DisparadorOtroModal=$(this).data('function')
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
        });

        $('#FormCancelFacturas').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            $('#CanFacSave').attr('disabled', false);
            Swal.fire({
                title: '¿Estás seguro?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Cancelar Facturas',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('2025.Facturar.Cancelar.Factura')}}',
                        type: "post",
                        data : formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({ html: `${response.message??'Cancelada Correctamente'}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            closethismodal();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error: ' + (xhr.status ?? "Desconocido"),
                                html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                                icon: 'error'
                            });
                        }
                    })
                }
            });
            
        });
       
    });
</script>
@endpush