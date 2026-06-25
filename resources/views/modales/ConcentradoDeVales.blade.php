<!-- Modal -->
<div class="modal fade" id="ConcentradoValesAlmacenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ConValAlmTittle">Cocentrado Vales De Almacen</h5>
                <button type="button" class="btn-close ConValAlmClose" aria-label="Close"></button>
            </div>
            <div class="modal-body Pruebavisor">
                <div class="Visorizquierda">
                    <div class="TablaConceptos">
                        <table id="ConValAlmTab" class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th class='zdw-r1'>Cantidad</th>
                                    <th class='zdw-r2'>Descripcion</th>
                                    <th class='zdw-r2'>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="Visorderecha">
                    <iframe src="" frameborder="0" id='VisorConValpdf' class='Visorpdf'></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ConValAlmClose" >Cerrar</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let ListaRefacciones=[];
        let ModalFather=null;
        let ThisModal=$('#ConcentradoValesAlmacenModal');
        let DisparadorOtroModal=null;
        let OrdenId=null;
        let InputEdit={
            id:null,
            cantidad:null,
            descripcion:null
        };
        let InputNew={
            cantidad:null,
            descripcion:null
        };

        $(".ConValAlmClose").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            ThisModal.modal('hide');
            if(DisparadorOtroModal){
                DisparadorOtroModal=null;
            }
        }

        window.OpenConcentradoValesAlmacenModal=async function(id){
            OrdenId=id;
            if(OrdenId){
                const isSuccess = await GetConValesAlmacen();
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    ThisModal.modal('show');
                }
            }else{
                ShowNotification('No se proporcionó un ID de orden válido.', 'error');
            }
        };
        async function GetConValesAlmacen() {

            try {
                const response = await $.ajax({
                    url: '{{ route('2025.Vales.Almacen.Concentrado') }}',
                    type: "get",
                    data: { id: OrdenId },
                });
                ListaRefacciones=response.elements; 
                mostrarvisor();
                mostrarinputs();
                return true;
            } catch (xhr) {
                console.log(xhr);
                ShowNotification(xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte", 'error');
                return false;
            }
        }
         function mostrarvisor() {
            const timestamp = new Date().getTime();
            const pdfUrl = "/Zcrat/Presupuestos/Diagnostico/Tecnico/Llena/" + OrdenId + "?v=" + timestamp + "#FitPage";
            const visor = $('#VisorConValpdf');
            visor.attr('src', pdfUrl);
        };
        function mostrarinputs() {
            const tablaBody = $('#ConValAlmTab tbody');
            tablaBody.empty(); // Limpiar el contenido existente

            ListaRefacciones.forEach((item) => {
                let fila = '';
                if(item.id == InputEdit.id){
                    InputEdit.cantidad=item.cantidad ?? '';
                    InputEdit.descripcion=item.descripcion ?? '';
                    fila = `
                    <tr>
                    <td class='zdw-r1'><input type="number" value="${InputEdit.cantidad ?? ''}" class="form-control cantidad-input"></td>
                    <td class='zdw-r2'><textarea class="form-control descripcion-input" >${InputEdit.descripcion ?? ''}</textarea></td>
                    <td class='zdw-r2'>
                    <button class="btn btn-sm btn-success" onclick="UpdateConcepto()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="CancelEdit()">X</button>
                    </td>
                    </tr>
                    `;
                }else{
                        fila = `
                    <tr>
                    <td class='zdw-r1'>${item.cantidad}</td>
                    <td class='zdw-r2'>${item.descripcion}</td>
                    <td class='zdw-r2'>
                    <button class="btn btn-sm btn-danger DeleteConcepto" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-sm btn-warning EditConcepto" data-id="${item.id}"><i class="fa-solid fa-file-pen"></i></button>
                    </td>
                    </tr>
                    `;
                }

                tablaBody.append(fila);
            });
                fila = `
                    <tr>
                    <td class='zdw-r1'><input type="number" value="${InputNew.cantidad ?? ''}" class="form-control cantidad-New-input"></td>
                    <td class='zdw-r2'><textarea class="form-control descripcion-New-input" >${InputNew.descripcion ?? ''}</textarea></td>
                    <td class='zdw-r2'>
                    <button class="btn btn-sm btn-success" onclick="NewConcepto()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="DeleteNew()"><i class="fas fa-trash"></i></button>
                    </td>
                    </tr>
                    `;
            tablaBody.append(fila);
        }
        window.UpdateConcepto=async function(){
            if(InputEdit.cantidad && InputEdit.descripcion && InputEdit.id ){
                try {
                    const response = await $.ajax({
                        url: '{{ route('2025.Vales.Almacen.UpdateConcepto') }}',
                        type: "post",
                        data: { ...InputEdit, "_token": "{{ csrf_token() }}" },
                    });
                GetConValesAlmacen()
                return true;
            } catch (xhr) {
                console.log(xhr);
                ShowNotification(xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte", 'error');
                return false;
            }
            }else{
                ShowNotification('Por favor complete todos los campos antes de guardar.', 'warning');
            }
        }
        window.NewConcepto= async function(){
            if(InputNew.cantidad && InputNew.descripcion && OrdenId ){
                try {
                    const response = await $.ajax({
                        url: '{{ route('2025.Vales.Almacen.CreateConcepto') }}',
                        type: "post",
                        data: { ...InputNew, orden_id:OrdenId, "_token": "{{ csrf_token() }}" },
                    });
                InputNew.cantidad=null;
                InputNew.descripcion=null;
                GetConValesAlmacen();
                return true;
            } catch (xhr) {
                console.log(xhr);
                ShowNotification(xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte", 'error');
                return false;
            }
            }else{
                ShowNotification('Por favor complete todos los campos antes de guardar.', 'warning');
            }
        }
        window.DeleteConcepto=async function(id){
          if(id && OrdenId ){
                try {
                    const response = await $.ajax({
                        url: '{{ route('2025.Vales.Almacen.DeleteConcepto') }}',
                        type: "post",
                        data: { "_token": "{{ csrf_token() }}",id:id, orden_id:OrdenId },
                    });
                GetConValesAlmacen();
                return true;
            } catch (xhr) {
                console.log(xhr);
                ShowNotification(xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte", 'error');
                return false;
            }
            }else{
                ShowNotification('No se pudo eliminar el concepto.', 'error');
            }
        }
        window.DeleteNew=function(id){
            
            InputNew.cantidad=null;
            InputNew.descripcion=null;
            mostrarinputs();
        }
        window.CancelEdit=function(){
            InputEdit.id=null;
            InputEdit.cantidad=null;
            InputEdit.descripcion=null;
            mostrarinputs();
        }
        $(document).on('input', '.cantidad-input', function() {
            InputEdit.cantidad = $(this).val();
        });
        $(document).on('input', '.descripcion-input', function() {
            InputEdit.descripcion = $(this).val();
        });
        $(document).on('input', '.cantidad-New-input', function() {
            InputNew.cantidad = $(this).val();
        });
        $(document).on('input', '.descripcion-New-input', function() {
            InputNew.descripcion = $(this).val();
        });
        $(document).on('click', '.DeleteConcepto', function() {
            const valeId = $(this).data('id');
            DeleteConcepto(valeId);
        });
        $(document).on('click', '.EditConcepto', function() {
            InputEdit.id = $(this).data('id');
            mostrarinputs();
        });
});
</script>
@endpush