<div class="modal fade" id="AsiganPresupuestoRestringido" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Asignar O Cambiar</h5>
                <button type="button" class="btn-close CloseAsiganPresupuestoRestringido"></button>
            </div>
            <div class="modal-body">
                <div class="select2conlabel zdw-100pct  zdrelative">
                    <h6>Presupuesto<strong>*</strong></h6>
                    <select id="PresupuestoIdRestringido" name="PresupuestoIdRestringido" required></select>
                </div>
                <div class="select2conlabel zdw-100pct  zdrelative">
                    <h6>Usuario<strong>*</strong></h6>
                    <select id="UsuarioIdRestringido" name="UsuarioIdRestringido" required></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseAsiganPresupuestoRestringido">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveAsiganPresupuestoRestringido" onclick="SaveAsignacionPresupuestoRestringido()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function(){
        $('#PresupuestoIdRestringido').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#AsiganPresupuestoRestringido"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Presupuestos') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
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
        $('#UsuarioIdRestringido').select2({
            language: { searching: ()=> "Buscando opciones...",noResults: () => "Sin Resultados",},
            dropdownParent: $("#AsiganPresupuestoRestringido"),
            placeholder: 'Escribe para buscar...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('Select2.Get.Users') }}',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
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
        let ModalFather=null;
        let ThisModal=$('#AsiganPresupuestoRestringido');
        let DisparadorOtroModal = null;
        $(".OpenAsiganPresupuestoRestringido").on('click',async function(){
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            resetform();
            ThisModal.modal('show');
            DisparadorOtroModal=$(this).data('disparador')
        });
        $(".CloseAsiganPresupuestoRestringido").on('click',function(){
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
        function resetform(){
            $('#PresupuestoIdRestringido').val(null).trigger('change');
            $('#UsuarioIdRestringido').val(null).trigger('change');
        }
        window.SaveAsignacionPresupuestoRestringido= function(){
            let PresupuestoIdRestringido=$('#PresupuestoIdRestringido').val();
            let UsuarioIdRestringido=$('#UsuarioIdRestringido').val();
            $.ajax({
                url:'{{ route('2025.Presupuestos.Save.Asignacion') }}',
                type:'POST',
                data:{
                    PresupuestoIdRestringido:PresupuestoIdRestringido,
                    UsuarioIdRestringido:UsuarioIdRestringido,
                    _token:'{{ csrf_token() }}'
                },
                success:function(response){
                    closethismodal();
                    if(DisparadorOtroModal){
                        DisparadorOtroModal.trigger('click');
                    }else{
                        try{executeSearchdata()}catch{console.log('no existe funcion de recarga')}
                    }
                },
                error:function(){
                    alertas('error','Error al guardar la asignación');
                }
            });
        }

    });
</script>
@endpush