<!-- Modal -->
<div class="modal fade" id="HojaConceptosModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="recepcionservicioLabel" >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ConSheTittle">Hoja De Conceptos</h5>
                
                <button type="button" class="btn-close ConSheModalClose" aria-label="Close"> </button>
            </div>
            <div class="modal-body ">
                <input type="range" class="w-100" id="barra">
                <div class="HojaConceptosCaptura verrsionhc2">

                    <div class="Visorizquierda" id='div1' style="width:50%">
                        <!-- <div class="zdflex zdai-start zdjc-between">
                                <p class="h5 text-uppercase font-weight-bold border-bottom">Lista De Conceptos</p> 
                                <button type="button" class="btn btn-primary" id="NewElementConShe"><i class="fa-solid fa-circle-plus"></i></i>&nbsp;Nuevo</button>
                        </div> -->
                        
                        <div id="ConSheDivTabla" class="TablaConceptos">
                            <input type="hidden" id="ConShePreID">
                            <table id="ConSheTable" class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th class='zdw-r1'>#</th>
                                        <th class='zdw-r5'>FECHA</th>
                                        <th class='zdw-r4'>REP</th>
                                        <th class='zdw-r4'>REMP</th>
                                        <th class='zdw-r4'>CLAVE</th>
                                        <th class="zdminw-10">DESCRIPCION</th>
                                        <th class='zdw-r4'>IVA INC</th>
                                        <th class='zdw-r6'>PARTES C/U</th>
                                        <th class='zdw-r6'>MANO O. C/U</th>
                                        <th class='zdw-r6'>SUB CONTR. C/U</th>
                                        <th class='zdw-r6'>OTROS C/U</th>
                                        <th class='zdw-r8'>SUBTOTAL</th>
                                        <th class='zdw-r4'>UTI %</th>
                                        <th class='zdw-r4'>VENTA C/U</th>
                                        <th class='zdw-r2'></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="Visorderecha" id='div2'  style="width:50%">
                        <iframe src="" frameborder="0" id='Visorpdf' class='Visorpdf'></iframe>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ConSheModalClose" >Cerrar</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        let listaconceptos=[];
        let ModalFather=null;
        let ThisModal=$('#HojaConceptosModal');
        let DisparadorOtroModal=null;
        let InputsWithError = [];
        let indexultimoInput = null;
        let valorinicial = null;
        const barra = $("#barra");
        const div1 = $("#div1");
        const div2 = $("#div2");
         barra.on('input',function(){
            let valor = parseInt(barra.val(), 10);
            if (valor < 20) valor = 0;
            if (valor > 80) valor = 100;

            let complemento = 100 - valor;

            div1.css("width", valor + "%");
            div2.css("width", complemento + "%");
            if (valor === 0) {
                div1.attr("hidden", true);
            } else {
                div1.removeAttr("hidden");
            }

            if (complemento === 0) {
                div2.attr("hidden", true);
            } else {
                div2.removeAttr("hidden");
            }
            barra.val(valor);
        })
        const atributosMap = {
            fecha: 'dateconcept',
            reparar: 'repconcept',
            remplazo: 'rempconcept',
            clave: 'keyconcept',
            descripcion: 'descriptionconcept',
            iva: 'hasivaconcept',
            partes: 'partesconcept',
            manoobra: 'manoobraconcept',
            subcontratados: 'subcontratadosconcept',
            otros: 'otrosconcept',
            porcentaje_utilidad: 'percentajeconcept',
            venta: 'priceconceptfinally'
        };

        window.OpenConceptsShet=async function(id,folio){
            if(id){
                const isSuccess = await GetElementsConceptsShet(id,folio);
                if (isSuccess) {
                    ModalFather = $('.modal.show');
                    if(ModalFather){
                        ModalFather.modal('hide');
                    }
                    nuevafila();
                    actualizarlista();
                    await mostrarvisor();
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
        $(".ConSheModalClose").on('click',function(){
            closethismodal()
        })
       
        

        async function GetElementsConceptsShet(id,folio) {
            try {
                const response = await $.ajax({
                    url: '{{ route('Presupuestos.Get.Conceptos.HojaConceptos') }}',
                    type: "get",
                    data: { id: id },
                });
                listaconceptos=response.elements;
                $("#ConSheTittle").text('Hoja Conceptos Para '+folio)
                $("#ConShePreID").val(id)
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

        window.DeleteElementConShe = function(index) {
            element=listaconceptos[index]
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el concepto de la lista.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("Presupuestos.Delete.Concepto.HojaConceptos") }}', // Cambia esto por la URL correcta
                        method: 'DELETE',
                        data: {
                            id: element.id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            let message = response.message ?? "Éxito al eliminar";
                            listaconceptos.splice(index, 1);
                            mostramensajeexito(message);
                            mostrarvisor();
                            indexultimoInput = null;
                            actualizarlista();
                        },
                        error: function (error) {
                            Swal.fire({
                                icon: 'error',
                                title: error.responseJSON.message ?? 'Ocurrió un problema al eliminar el concepto.',
                                showConfirmButton: false,
                                timer: 2000,
                            });
                        }
                    });
                }
            });
        };
        function actualizarlista(){
            $('#ConSheTable tbody').empty();
            $.each(listaconceptos, function(index, element) {
                const clases = ['dateconcept', 'repconcept', 'rempconcept', 'keyconcept', 'descriptionconcept','hasivaconcept', 'partesconcept', 'manoobraconcept','subcontratadosconcept','otrosconcept','subtotalconcept','percentajeconcept','priceconceptfinally'];
                const elementshtml = ['input', 'input', 'input', 'input', 'textarea','input', 'input','input','input', 'input','label','input','input'];
                const TYPE = ['date', 'number', 'number', 'text', 'textarea','checkbox','number','number','number', 'number','label','number','number'];
                const remplazo = parseFloat(element.remplazo) || 0;
                const repararar = parseFloat(element.reparar) || 0;
                const partes = parseFloat(element.partes) || 0;
                const manoobra = parseFloat(element.manoobra) || 0;
                const subcontratados = parseFloat(element.subcontratados) || 0;
                const otros = parseFloat(element.otros) || 0;
                const costo =  partes + manoobra + subcontratados + otros;
                const iva =  element.iva==1?1.16:1;
                const cantidad = remplazo +repararar
                const resultado = (costo/iva) * cantidad;
                const values=[
                    element.fecha,
                    element.reparar,
                    element.remplazo,
                    element.clave ,
                    element.descripcion ,
                    element.iva ,
                    element.partes,
                    element.manoobra,
                    element.subcontratados,
                    element.otros,
                    resultado,
                    element.porcentaje_utilidad,
                    element.venta
                ];

                let indexprincipal=index;
                const row = document.createElement('tr');
                row.id = 'FilaConShe' + indexprincipal; 
                let cell = document.createElement('td');
                cell.className = 'zdw-r1';
                cell.innerHTML = `<div class="Datatable-content">${indexprincipal + 1}</div>`;
                row.appendChild(cell);

                elementshtml.forEach((elementhtml, index) => {
                    cell = document.createElement('td');
                    const htmlele = document.createElement(elementhtml);
                    if(elementhtml=='input') {htmlele.setAttribute('type', TYPE[index]);}
                    htmlele.className = `${clases[index]} form-control fila-concepto`+indexprincipal;

                    if(elementhtml=='label'){
                        htmlele.innerHTML = Number(resultado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        htmlele.id = 'SubtotalConcept'+indexprincipal;
                    }else{
                        if(TYPE[index]=='date'){
                            htmlele.value=values[index]? values[index].split(" ")[0]: '';
                        }else if(TYPE[index]=='checkbox'){
                           htmlele.checked = values[index]==1;
                           htmlele.className = `${clases[index]} fila-concepto`+indexprincipal;
                        }else{
                            htmlele.value = values[index];
                        }
                    }
                    htmlele.setAttribute('index', indexprincipal);
                    cell.appendChild(htmlele);
                    row.appendChild(cell);


                })
                if(element.id){
                    const cellAcciones = document.createElement('td');
                    const btnDelete = document.createElement('button');
                    btnDelete.className = 'btn btn-danger btn-sm';
                    btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>';
                    btnDelete.onclick = function() {
                        DeleteElementConShe(indexprincipal);
                    };
                    cellAcciones.appendChild(btnDelete);
                    row.appendChild(cellAcciones);
                }
                $('#ConSheTable tbody').append(row);
            });
            putfocusinput();
        }
        function buscarclave(clave,index) {
            $.ajax({
                url: '{{ route('Presupuestos.search.key.HojaConceptos') }}',
                method: 'POST',
                data: {
                    clave: clave,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if(response.element){
                        element=response.element
                        listaconceptos[index].descripcion = element.descripcion;
                        listaconceptos[index].partes = element.partes;
                        listaconceptos[index].manoobra = element.manoobra;
                        listaconceptos[index].subcontratados = element.subcontratados;
                        listaconceptos[index].otros = element.otros;
                        listaconceptos[index].venta = element.venta;
                    }
                    actualizarlista();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
        $(document).on('change', '.dateconcept, .repconcept, .rempconcept, .keyconcept, .descriptionconcept, .partesconcept, .manoobraconcept,.subcontratadosconcept,.otrosconcept,.percentajeconcept ,.priceconceptfinally', function () {
            const val = $(this).val(); 
            const index = parseInt($(this).attr('index'));
            const clase = $(this).attr('class').split(' ')[0];

            const nombreEnEspañol = Object.keys(atributosMap).find(key => atributosMap[key] === clase);
            if (!isNaN(index) && nombreEnEspañol) {
                console.log('index', index, 'nombreEnEspañol', nombreEnEspañol, 'valor', val);
                listaconceptos[index][nombreEnEspañol] = val; 
                
                if(listaconceptos[index].id==null){
                    savenewElement(index, nombreEnEspañol);   
                }else{
                    updateelement(index, nombreEnEspañol);
                }
            }
        });
        $(document).on('click','.hasivaconcept',function() {
            const index = parseInt($(this).attr('index'));
            let inputs = $('#HojaConceptosModal #ConSheDivTabla tbody input, #HojaConceptosModal #ConSheDivTabla tbody textarea');
            indexultimoInput = inputs.index($(this));
            indexultimoInput++
            if ($(this).is(':checked')) { 
                listaconceptos[index]['iva'] = 1; 
            } else {
                listaconceptos[index]['iva'] = 0; 
            }
            if(listaconceptos[index].id==null){
                savenewElement(index, 'iva');   
            }else{
                updateelement(index, 'iva');
            }

        })
        function mostramensajeexito(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
                });
                Toast.fire({
                icon: "success",
                title: message
            });
        }
        function savenewElement(index, key) {
            let data = {
            ConShePreID: $('#ConShePreID').val(),
            _token: "{{ csrf_token() }}"
            };
            data[key] = listaconceptos[index][key];
            data['fecha'] = listaconceptos[index]['fecha'];

            $.ajax({
                url: '{{ route('Presupuestos.Create.Concepto.HojaConceptos') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'POST',
                data: data,
                success: function (response) {
                    $message=response.message ?? "Exito Guardado"
                    listaconceptos[index].id = response.id;

                    if(response.element){
                        element=response.element
                        listaconceptos[index].descripcion = element.descripcion;
                        listaconceptos[index].partes = element.partes;
                        listaconceptos[index].manoobra = element.manoobra;
                        listaconceptos[index].subcontratados = element.subcontratados;
                        listaconceptos[index].otros = element.otros;
                        listaconceptos[index].porcentaje_utilidad = element.porcentaje_utilidad;
                        listaconceptos[index].iva = element.iva;
                        listaconceptos[index].venta = element.venta;
                    }
                    mostramensajeexito($message);
                    mostrarvisor();
                    nuevafila();
                    actualizarlista();
                },
                error: function (error) {
                    if (error.status === 422) {
                        Object.keys(error.responseJSON.errors).forEach(key => {
                                InputsWithError.push({ index, key });
                        });
                    } else {
                        Swal.fire({
                            icon:'error',
                            title:error.responseJSON.message?? 'Ocurrio un problema al Actualizar El Listado De Conceptos.',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }
            });
        }
        function updateelement(index, key) {
            let data = {
                ConShePreID: $('#ConShePreID').val(),
                id: listaconceptos[index]['id'],
                _token: "{{ csrf_token() }}"
            };
            data[key] = listaconceptos[index][key];
            $.ajax({
                url: '{{ route('Presupuestos.Update.Concepto.HojaConceptos') }}', // Cambia esto por la URL del endpoint en tu backend
                method: 'POST',
                data: data,
                success: function (response) {
                    mostrarvisor();
                    $message=response.message ?? "Exito Actualizado"
                    mostramensajeexito($message);
                    if(response.element){
                        element=response.element
                        listaconceptos[index].descripcion = element.descripcion;
                        listaconceptos[index].partes = element.partes;
                        listaconceptos[index].manoobra = element.manoobra;
                        listaconceptos[index].subcontratados = element.subcontratados;
                        listaconceptos[index].otros = element.otros;
                        listaconceptos[index].porcentaje_utilidad = element.porcentaje_utilidad;
                        listaconceptos[index].iva = element.iva;
                        listaconceptos[index].venta = element.venta;
                    }
                    actualizarlista();
                },
                error: function (error) {
                    if (error.status === 422) {
                            Object.keys(error.responseJSON.errors).forEach(key => {
                                    InputsWithError.push({ index, key });
                            });
                    } else {
                        Swal.fire({
                            icon:'error',
                            title:error.responseJSON.message?? 'Ocurrio un problema al Actualizar El Listado De Conceptos.',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                }
            });
        }
        mostrarvisor = async function () {
            const presupuestoID = document.getElementById("ConShePreID").value;
            const timestamp = new Date().getTime(); // Valor único para evitar caché
            const pdfUrl = "/Zcrat/Presupuestos/Hoja/Conceptos/" + presupuestoID + "?v=" + timestamp + "#FitPage";

            const visor = $('#Visorpdf');
            visor.attr('src', pdfUrl);
        };
        function nuevafila() {
            let fecha=listaconceptos.length  > 0 ? listaconceptos[listaconceptos.length  - 1 ]['fecha'] : null;
            let newelement = {
                id: null,
                fecha: fecha,
                remplazo: null,
                reparar: null,
                clave: null,
                descripcion: null,
                iva:null,
                partes: null,
                manoobra: null,
                subcontratados: null,
                otros: null,
                porcentaje_utilidad: null,
                venta: null
            };
            listaconceptos.push(newelement);
        }
        $(document).on('click', '#HojaConceptosModal #ConSheDivTabla tbody input:not([type="checkbox"]), #HojaConceptosModal #ConSheDivTabla tbody textarea', function() {
            let inputs = $('#HojaConceptosModal #ConSheDivTabla tbody input, #HojaConceptosModal #ConSheDivTabla tbody textarea');
            indexultimoInput = inputs.index($(this));
            console.log('click'+indexultimoInput);
            valorinicial = $(this).val();
        });
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#HojaConceptosModal #ConSheDivTabla tbody input, #HojaConceptosModal #ConSheDivTabla tbody textarea').length) {
                valorinicial = null;
                indexultimoInput = null;
            }
        });

        $(document).on('focus', '#HojaConceptosModal #ConSheDivTabla tbody input:not([type="checkbox"]), #HojaConceptosModal #ConSheDivTabla tbody textarea', function() {
            valorinicial = $(this).val();
        });

        function putfocusinput(){
            console.log('focus en ' +indexultimoInput)
            if (indexultimoInput === null || indexultimoInput === undefined) {
                return;
            }
            let inputs = $('#HojaConceptosModal #ConSheDivTabla tbody input, #HojaConceptosModal #ConSheDivTabla tbody textarea');
            if (indexultimoInput !==-1 && indexultimoInput < inputs.length) {
                inputs.eq(indexultimoInput).focus(); // Mueve el foco al siguiente input dentro del modal
            }
        }
        $(document).on('keydown', '.dateconcept, .repconcept, .rempconcept, .keyconcept, .descriptionconcept, .partesconcept, .manoobraconcept, .subcontratadosconcept, .otrosconcept,.percentajeconcept,.priceconceptfinally', function (e) {
            let inputs = $('#HojaConceptosModal #ConSheDivTabla tbody input, #HojaConceptosModal #ConSheDivTabla tbody textarea');
            indexultimoInput = inputs.index($(this));
            if ( e.key === 'Tab' || e.key === 'ArrowRight'|| e.key === 'Enter' ) {
                e.preventDefault();
                const currentValue = $(this).val();

                if(indexultimoInput < inputs.length){
                        indexultimoInput++
                }

                if (currentValue === valorinicial) {
                    putfocusinput()
                } else {
                    $(this).blur(); 
                }
            }
            if ( e.key === 'ArrowLeft') {
                e.preventDefault();
                if( indexultimoInput != null && indexultimoInput != undefined  && indexultimoInput <= 0) {
                    return 
                }
                indexultimoInput--
                const currentValue = $(this).val();
                if (currentValue === valorinicial) {
                    putfocusinput();
                } else {
                    $(this).blur(); 
                }
            }
            if ( e.key === 'ArrowUp') {
                e.preventDefault();
                if( indexultimoInput != null && indexultimoInput != undefined  && indexultimoInput > 11) {
                    indexultimoInput-=11;
                    const currentValue = $(this).val();
                    if (currentValue === valorinicial) {
                        putfocusinput();
                    } else {
                        $(this).blur(); 
                    }
                }
            }
            if ( e.key === 'ArrowDown') {
                e.preventDefault();
                const index = parseInt($(this).attr('index'));

                if( indexultimoInput != null && indexultimoInput != undefined) {
                    const currentValue = $(this).val();
                    
                    if (currentValue === valorinicial) {
                         if(indexultimoInput < inputs.length-11){
                                indexultimoInput+=11
                                putfocusinput(); 
                        }
                    } else {
                        if(listaconceptos[index].id==null){
                                indexultimoInput+=11
                        }
                        $(this).blur(); 
                    }
                }
                
            }
        })
});
</script>
@endpush