<!-- Modal -->
<div class="modal fade" id="ValeAlmacenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ValAlmTittle">Vales De Almacen</h5>
                <button type="button" class="btn-close ValAlmClose" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id='ValAlmDivMen'>

                </div>
                <div id="ValAlmDivTab">
                    <input type="hidden" id="ValAlmDetGenId">
                    <div id='datosextra' class="zdflex">
                        <div>
                            <label for="">Motor</label>
                            <input type="text" name="motorvalealmacen" id="motorvalealmacen" class="form-control">
                        </div>
                        <div>
                            <label for="destinovale">Destino</label>
                            <select name="destinovale" id="destinovale" class="form-control">
                                <option value="0">Almacen</option>
                                <option value="1">Subcontratado</option>
                            </select>
                        </div>
                        <div id="destinovalealmacendiv">
                            <label for="destinovalealmacen">Especificar</label>
                            <input type="text" name="destinovalealmacen" id="destinovalealmacen"  class="form-control">
                        </div>

                    </div>
                    <table id="ValAlmTable" class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th class='zdw-r2'>CANTIDA</th>
                                <th class='zdw-r40'>DESCRIPCION</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    </div>
                <div id="ValAlmDivPdf" class="zdminw-50vw">

                    <iframe src="" frameborder="0" id='VisorValepdf' class='Visorpdf'></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn " hidden id="ValeEntrega" onclick="EntregarVale()" >Entregar</button>
                <button type="button" class="btn " hidden id="ValeSurtido" onclick="SurtirVale()" >Confirmar</button>
                <button type="button" class="btn btn-danger" hidden id="DeleteVale" onclick="DeleteVale()" >Eliminar</button>

                <button type="button" class="btn btn-success" hidden id="EditVale" onclick="EditarVale()" >Editar</button>
                <button type="button" class="btn btn-secondary ValAlmClose" >Cerrar</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let ListaVales=[];
        let ListaRefacciones=[];
        let ModalFather=null;
        let ThisModal=$('#ValeAlmacenModal');
        let DisparadorOtroModal=null;
        let InputsWithError = [];
        let indexultimoInput = null;
        let valorinicial = null;
        let VALEID=null;
        let Entregado=null;
        let Surtido=null;
        let Tipo=0;
        let destino=null;
        let motor=null;
        let executesearch=false

        $(".ValAlmClose").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            ThisModal.modal('hide');

            console.log('Cerrando Modal');
            console.log('ListaVales', ModalFather);
            if(DisparadorOtroModal){
                DisparadorOtroModal=null;
            }

        }
        window.OpenValeAlmacenModal=async function(id=false,ordserv=false,execute=false){
            VALEID=null;
            executesearch=execute;
            Entregado=null;
            if(id && ordserv){
                const isSuccess = await GetValesAlmacen(id,ordserv);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    ThisModal.modal('show');
                }
            }else{
                Swal.fire({ 
                    title: 'Error', 
                    html: `Detalles del error:<br> Datos Corrompidos`, 
                    icon: 'error',
                    timer: 1000, 
                });
            }
        };
        async function GetValesAlmacen(id,ordserv=null) {
            if(ordserv){
                $("#ValAlmTittle").text('Vales De Almacen Para '+ordserv)  
            }
            $("#ValAlmDetGenId").val(id)
            try {
                const response = await $.ajax({
                    url: '{{ route('2025.Vales.Almacen.GetDataOne') }}',
                    type: "get",
                    data: { id: id },
                });
                ListaVales=response.elements; 
                ListaRefacciones=[];
                ActualizarMenuVales();
                if(ListaVales.length > 0){
                    const lastVale = ListaVales[ListaVales.length - 1];
                    if(VALEID){
                        OpenValePDF(VALEID);
                    }else{
                        OpenValePDF(lastVale.id);
                    }
                }
                else{
                    NuevoVale();
                }
                return true;
            } catch (xhr) {
                console.log(xhr);
                Swal.fire({ 
                    title: 'Error: ' + (xhr.status ?? "Desconocido"), 
                    html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado, Contacte A Soporte"}`, 
                    icon: 'error' 
                });
                return false;
            }
        }
        function ShowNotification(message,icon='error') {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
                });
                Toast.fire({
                icon: icon,
                title: message
            });
        }
        function ActualizarMenuVales(){
            const menudiv=$('#ValAlmDivMen')
            menudiv.empty()
            const newvalebtn = document.createElement('button');
            newvalebtn.className = `btn btn-success`;
            newvalebtn.innerHTML = '<i class="fa-solid fa-circle-plus"></i>&nbsp; Nuevo Vale';
            newvalebtn.id = 'NewValebtn';

            newvalebtn.onclick = function() {
                    NuevoVale();
                };
            menudiv.append(newvalebtn)
            const newvalebtn1 = document.createElement('button');
            newvalebtn1.className = `btn btn-primary`;
            newvalebtn1.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>&nbsp; Guardar Vale';
            newvalebtn1.id = 'SaveValebtn';
            newvalebtn1.hidden = true;
            newvalebtn1.onclick = function() {
                    savevale();
                };
            menudiv.append(newvalebtn1)
            $.each(ListaVales, function(index, Vale) {
                const valebtn = document.createElement('nutton');
                valebtn.className = `btn btn-danger`;
                valebtn.innerHTML = '<i aria-hidden="true" class="fa-solid fa-file"></i>&nbsp; Vale'+(index + 1);
                valebtn.onclick = function() {
                    OpenValePDF(Vale.id);
                };
                menudiv.append(valebtn)
            })
        }
        OpenValePDF = async function (id) {
            const pdfUrl = "/Zcrat/Vales/Almacen/pdf/" + id + "#FitPage";
            const visor = $('#VisorValepdf');
            visor.attr('src', pdfUrl);
            $('#DeleteVale').removeAttr('hidden');
            $('#EditVale').removeAttr('hidden');
            VALEID=id;
            verificarentregado();
            $('#ValAlmDivPdf').removeAttr('hidden');
            $('#NewValebtn').removeAttr('hidden');
            $('#SaveValebtn').attr('hidden',true);
            $('#ValAlmDivTab').attr('hidden',true);
        };
        function NuevoVale(){
            $('#ValeEntrega').attr('hidden',true);
            $('#ValeSurtido').attr('hidden',true);
            $('#DeleteVale').attr('hidden',true);
            $('#EditVale').attr('hidden',true);
            $('#destinovale').val('').trigger('change');
            VALEID=null;
            Entregado=null;
            Tipo=0;
            ListaRefacciones=[];
            motor=null;
            destino=null;
            print();
        }
        $('#destinovale').on('change',function(e){
            const value=$(this).val();
            if(value){
                $('#destinovalealmacendiv').removeAttr('hidden')
            }else{
                $('#destinovalealmacendiv').attr('hidden',true)
            }
        })
        async function savevale() {
            if(ListaRefacciones.length == 0 ){
                Swal.fire({
                    title: 'No Hay Conceptos Que Guardar',
                    icon: 'warning',
                    timer:10000
                });
                return;
            }
            const resultado = await Swal.fire({
                title: '¿Está seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Guardar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });

            if (!resultado.isConfirmed) return;
            sendrequest()
        }
       async function sendrequest() {
            $.ajax({
                url: VALEID ? '{{ route('2025.Vales.Almacen.Edit') }}':'{{ route('2025.Vales.Almacen.Created') }}' , // Cambia esto por la URL del endpoint en tu backend
                method: 'POST',
                data: {
                    ValAlm_DetGen:$("#ValAlmDetGenId").val(),
                    ValAlm_TipMot:motor,
                    ValAlm_Des:destino,
                    ValAlm_Tip:$('#destinovale').val(),
                    listofconcepts:ListaRefacciones,
                    id:VALEID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $message=response.message ?? "Exito Actualizado"
                    Swal.fire({
                        icon: "success",
                        title: $message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    GetValesAlmacen($("#ValAlmDetGenId").val(),ordserv=null)
                    if(executesearch){
                        executeSearchdata(1,false);
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al crear El Vale. Verifique los datos ingresados',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
       window.DeleteVale = async function() {
        const resultado = await Swal.fire({
            title: '¿Está seguro?',
            text: "No podrás Recuperarlo",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        });
        if (!resultado.isConfirmed) return;

            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Delete') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'delete',
                data: {
                    id:VALEID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $message=response.message ?? "Exito Eliminado"
                    Swal.fire({
                        icon: "success",
                        title: $message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    VALEID=null;
                    Entregado=null;
                    GetValesAlmacen($("#ValAlmDetGenId").val(),ordserv=null)
                    if(executesearch){
                        executeSearchdata(1,false);
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al crear El Vale. Verifique los datos ingresados',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
        window.EntregarVale = async function() {
            if(Entregado){
                let texto=Tipo == 0 ? 'Se Entrego Al Almacen : ' : 'El Trabajo Fue Enviado : ';
                Swal.fire({
                    icon:'info',
                    title:texto+Entregado,
                    showConfirmButton: false,
                    timer: 1500,
                });
                return;
            }
            const resultado = await Swal.fire({
                title: '¿Está seguro?',
                text: "No podrás Cancelarlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
            if (!resultado.isConfirmed) return;
            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Entregar') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'post',
                data: {
                    id:VALEID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    Entregado = response.fecha_entrega ?? "Desconocido";
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    actualizarbotonentregado()
                    if(executesearch){
                        executeSearchdata(1,false);
                    }
                    
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al crear El Vale. Verifique los datos ingresados',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
        window.SurtirVale = async function() {
            if(Surtido){
                let texto=Tipo==0? 'Las Refacciones Se Confirmaron : ' : 'El Trabajo Fue Autorizado : ';
                Swal.fire({
                    icon:'info',
                    title:texto+Surtido,
                    showConfirmButton: false,
                    timer: 1000,
                });
                return;
            }
            const resultado = await Swal.fire({
                title: '¿Está seguro?',
                text: "No podrás Cancelarlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
            if (!resultado.isConfirmed) return;

            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Surtir') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'post',
                data: {
                    id:VALEID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    Surtido =response.fecha_surtido ?? "Desconocido";

                    Swal.fire({
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });

                    actualizarbotonentregado();

                    if(executesearch){
                        executeSearchdata(1,false);
                    }
                    
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al Marcar El Vale. Intentelo de Nuevo',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
        function verificarentregado(){
            $.ajax({
                url: '{{ route('2025.Vales.Almacen.Verificar.Entregado') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'get',
                data: {
                    id:VALEID,
                },
                success: function (response) {
                    Entregado = response.Entregado ?? null;
                    Tipo = response.Tipo ?? 0 ;
                    Surtido = response.Surtido ?? null;
                    actualizarbotonentregado();
                },
                error: function (error) {
                    Swal.fire({
                        icon:'error',
                        title:'Ocurrio un problema al Intentar Verificar el Estado de Entrega del Vale',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        }
        function actualizarbotonentregado(){
            let texto1=Tipo == 0 ? 'Entregar Al Almacen' : 'Enviar Trabajo';
            let texto2=Tipo == 0 ? 'Entregado Al Almacen' : 'Trabajo Enviado';
            let texto3=Tipo == 0 ? 'Confirmar Refacciones' : 'Autorizar Trabajo';
            let texto4=Tipo == 0 ? 'Refacciones Confirmadas' : 'Trabajo Autorizado';

            if(Entregado){
                $('#ValeEntrega').text(texto2).addClass('btn-success').removeClass('btn-warning').removeAttr('hidden');
                if(Tipo == 0){
                    if(Surtido){
                        $('#ValeSurtido').text(texto4).addClass('btn-success').removeClass('btn-warning').removeAttr('hidden');
                    }else{
                        $('#ValeSurtido').text(texto3).addClass('btn-warning').removeClass('btn-success').removeAttr('hidden');
                    }
                }else{
                    $('#ValeSurtido').text(texto3).addClass('btn-warning').removeClass('btn-success').attr('hidden',true);
                }
            }else{
                    $('#ValeSurtido').text(texto3).addClass('btn-warning').removeClass('btn-success').attr('hidden',true);
                    $('#ValeEntrega').text(texto1).addClass('btn-warning').removeClass('btn-success').removeAttr('hidden');
            }
            
        }
       window.CancelarEdicion = async function() {
            OpenValePDF(VALEID);
            ActualizarMenuVales();

       }
       window.EditarVale = async function() {
        $.ajax({
            url: '{{ route('2025.Vales.Almacen.Read.Data') }}', // Cambia esto por la URL del endpoint en tu backend
            method: 'get',
            data: {
                id:VALEID,
            },
            success: function (response) {
                ListaRefacciones=response.elements ?? [];
                motor=response.motor??null;
                destino=response.destino??null;
                const menudiv=$('#ValAlmDivMen')
                menudiv.empty()

                const newvalebtn = document.createElement('button');
                newvalebtn.className = `btn btn-danger`;
                newvalebtn.innerHTML = '<i class="fa-solid fa-circle-plus"></i>&nbsp; Cancelar';
                newvalebtn.id = 'CancelBtnEdit';
                newvalebtn.onclick = function() {
                    CancelarEdicion();
                };
                const newvalebtn1 = document.createElement('button');
                newvalebtn1.className = `btn btn-success`;
                newvalebtn1.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>&nbsp; Actualizar Vale';
                newvalebtn1.id = 'SaveValebtn';
                newvalebtn1.hidden = true;
                newvalebtn1.onclick = function() {
                    savevale();
                };
                    menudiv.append(newvalebtn)
                menudiv.append(newvalebtn1)
                    $('#destinovale').val(response.tipo??'').trigger('change');
                    print();
                    $('#DeleteVale').attr('hidden',true);
                    $('#ValeEntrega').attr('hidden',true);
                    $('#ValeSurtido').attr('hidden',true);
                    $('#EditVale').attr('hidden',true);
            },
            error: function (error) {
                Swal.fire({
                    icon:'error',
                    title:'Ocurrio un problema al Intentar Obtener los Conceptos del Vale',
                    showConfirmButton: false,
                    timer: 1000,
                });
            }
        });

        }
        $('#motorvalealmacen').on('input', function(e){
            motor = $(this).val(); // o motor = e.target.value;
        });

        $('#destinovalealmacen').on('input', function(e){
            destino = $(this).val(); // o destino = e.target.value;
        });

        const print=()=>{
            $('#motorvalealmacen').val(motor);
            $('#destinovalealmacen').val(destino);
            const tbody = document.querySelector('#ValAlmTable tbody');
            tbody.innerHTML = '';

            for (let i = 0; i < 10; i++) {
                const cantidad=ListaRefacciones[i]? ListaRefacciones[i]['cantidad'] :null;
                const descripcion=ListaRefacciones[i]?ListaRefacciones[i]['descripcion'] :null;

                const row = document.createElement('tr');
                const cell2 = document.createElement('td');
                const numberInput = document.createElement('input');
                numberInput.type = 'number';
                numberInput.className = `form-control fila-concepto`;
                numberInput.setAttribute('index', i);
                numberInput.id=`fila-concepto-cantidad${i}`;
                numberInput.setAttribute('tipo', 'cantidad');
                if(cantidad){
                    numberInput.value=cantidad;
                }
                cell2.appendChild(numberInput);
                row.appendChild(cell2);
                
                const cell1 = document.createElement('td');
                const textarea = document.createElement('textarea');
                textarea.className = `form-control fila-concepto`;
                textarea.setAttribute('index', i);
                textarea.setAttribute('tipo', 'descripcion');
                textarea.id=`fila-concepto-descripcion${i}`;
                if(descripcion){
                    textarea.value=descripcion;
                }
                cell1.appendChild(textarea);
                row.appendChild(cell1);

                tbody.appendChild(row)
            }
            if(VALEID){
                
            }
            $('#SaveValebtn').removeAttr('hidden');
            $('#VisorValepdf').attr('src','')
            $('#ValAlmDivTab').removeAttr('hidden');
            $('#NewValebtn').attr('hidden',true);
            $('#ValAlmDivPdf').attr('hidden',true);
        }
        $(document).on('change','.fila-concepto', function () {
            const val = $(this).val(); 
            const index = parseInt($(this).attr('index'));
            const tipo = $(this).attr('tipo');
            if (!ListaRefacciones[index]) {
                ListaRefacciones[index] = {
                    cantidad: null,
                    descripcion: null
                };

            }

            ListaRefacciones[index][tipo] = val;
            if (!val) {
                const fila = ListaRefacciones[index];
                const tieneDatos = Object.values(fila).some(valor => valor);
                if (!tieneDatos) {
                    delete ListaRefacciones[index];
                }
            }
            
        });
    });
</script>
@endpush