<div class="modal fade" id="DetallesValeAlmacenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="DetValAlmTitle">Detalles del Vale</h5>
                <button type="button" class="btn-close CloseDetValAlm"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="DetValAlmTable">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Cantidad</th>
                            <th><input type="checkbox" id="todosentregados"></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseDetValAlm">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="entregarConceptosAlmacen()">Entregar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function(){
        const ThisModal=$('#DetallesValeAlmacenModal')
        let thisid=null;
        let ids=[];
        let conceptos=[];
        let toggleall=false;

        $(".CloseDetValAlm").on('click',function(){
            closethismodal()
        })

        function closethismodal(){
            ids=[];
            conceptos=[];
            thisid=null;
            toggleall=false;
            ThisModal.modal('hide');
        }

        window.OpenDetallesValeAlmacenModal=function(id){
            thisid=id;
            ids=[];
            toggleall=false;
            getdata();
        }
        function getdata(){
            $.ajax({
                type: "GET",
                url: "{{ route('2025.Vales.Almacen.Detalles') }}",
                data: {
                    id: thisid,
                },
                success: function (response) {
                    conceptos=response.conceptos;
                    if(toggleall){
                        ids=conceptos.filter(c=>!c.entregado).map(c=>c.id);
                    }
                    PrintDetallesValeAlmacen();
                    checkedall();
                    ThisModal.modal('show');
                }
            });
        }
        function PrintDetallesValeAlmacen(){
            $('#DetValAlmTable tbody').empty();
            $.each(conceptos, function(index, element) {
                let row = $(`<tr></tr>`);
                 row.append(`<td>${element.cantidad}</td>`);
                 row.append(`<td>${element.descripcion}</td>`);
                 if(!element.entregado){
                    row.append(`<td><input type="checkbox" class="entregarconcepto" data-id="${element.id}" ${ids.includes(element.id) ? 'checked' : ''}></td>`);
                 }else{
                    row.append(`<td><div class="d-flex justify-content-between"><p>${element.entregado}</p>
                        <button class="anularentrega btn" data-id="${element.id}">X</button>
                        </div></td>`);
                 }

                 $('#DetValAlmTable tbody').append(row);
            });
        }
        function toggleIdInArray(id){
            const index = ids.indexOf(id);
            if (index > -1) {
                ids.splice(index, 1);
                toggleall=false;
            } else {
                ids.push(id);
                const ids2=conceptos.filter(c=>!c.entregado).map(c=>c.id);
                const faltantes = ids2.filter(el => !ids.includes(el));
                console.log(faltantes);
                console.log(ids2);
                if(faltantes.length===0 && ids2.length>0 ){
                    toggleall=true;
                }
            }
            checkedall();
            PrintDetallesValeAlmacen();
        }
        $(document).on('click', '.entregarconcepto', function() {
            const conceptoId = $(this).data('id');
            toggleIdInArray(conceptoId);
        });
        $(document).on('click', '.anularentrega', function() {
            const conceptoId = $(this).data('id');
            Swal.fire({
                title: '¿Queres Anular La Entrega?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Cancelar Entrega',
                cancelButtonText: 'No, Abortar'
            }).then((result) => {
                if (result.isConfirmed) {
                    anularentrega(conceptoId);
                }
            });
        });
        $('#todosentregados').on('change', function() {
            ToggleAllConcepts();
        });
        function ToggleAllConcepts(){
            const ids2=conceptos.filter(c=>!c.entregado).map(c=>c.id);
            const faltantes = ids2.filter(el => !ids.includes(el));
            if(toggleall){
                toggleall=false;
                ids=[];
            }else{
                toggleall=true;
                ids=ids2;
            }
            checkedall();
            PrintDetallesValeAlmacen();
        }
        function checkedall(){
            console.log(toggleall);
            if (toggleall) {
                $('#todosentregados').prop('checked', true);
                console.log('checked');
            } else {
                $('#todosentregados').prop('checked', false);
                console.log('no checked');
            }

        }
        window.anularentrega=function(conceptoId){
            $.ajax({
                type: "POST",
                url: "{{ route('2025.Vales.Almacen.CancelarEntregaConceptos') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: [conceptoId],
                },
                success: function (response) {
                    mostramensajeexito('La entrega del concepto fue cancelada exitosamente.');
                    executeSearchdata(1,false);
                    getdata();
                },
                error:function(){
                    mensajefallo('Ocurri un error al cancelar la entrega del concepto.');
                }
            });
        }
        window.entregarConceptosAlmacen=function(){
            if(ids.length===0){
                Swal.fire(
                    '¡Error!',
                    'No has seleccionado ningn concepto para entregar.',
                    'error'
                )
                return;
            }
            enviarEntregarConceptos();
            // Swal.fire({
            //     title: '¿No Podras Cancelarlo?',
            //     icon: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'S, Entregar',
            //     cancelButtonText: 'Cancelar'
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         enviarEntregarConceptos();
            //     }
            // });
            
        }
        function enviarEntregarConceptos(){
            $.ajax({
                type: "POST",
                url: "{{ route('2025.Vales.Almacen.EntregarConceptos') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids,
                },
                success: function (response) {
                    ids=[];
                    mostramensajeexito('Los conceptos fueron entregados exitosamente.');
                    executeSearchdata(1,false);
                    getdata();
                },
                error:function(){
                    mensajefallo('Ocurri un error al entregar los conceptos.');
                }
            });
        }
    });
</script>
@endpush