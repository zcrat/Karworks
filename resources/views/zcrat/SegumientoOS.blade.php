@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>TODOS LAS UNIDADES EN SEGUIMIENTO DE ORDENES DE SERVICIO
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" >
                        <div class="d-flex">
                        
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29"
                                    type="text" id="search" name="s"
                                    placeholder="Busqueda Por Ord. Servicio, Ord. Seguimiento,placas, Vin y Economico," >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            <div class="zdflex zdfd-column zdjc-end">
                                <button class="btn btn-sm btn-danger" onclick="ReporteSeguimiento()"><i class="fa fa-file"></i></button>
                            </div>
                            @if (in_array(Auth::user()->id,[1,170,36]))
                             <div class="zdmg-r02">
                                <label class="zdmgr-r02">Empresas:</label>
                                <select class="empresas-Select2" id="empresas_seguimiento">
                                    <option value="">Todas</option>
                                </select>
                            </div>
                             
                            
                            <div class="zdmg-r02">
                                <div class="select2conlabel zdrelative">
                                    <label for="">Taller<strong>*</strong></label>
                                    <div>
                                        <select id="taller" name="taller" required class="talleres_select2"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="zdmg-r02">
                                <label class="zdmgr-r02">Ubicado:</label>
                                <select class="form-control" id="subcontrato_seguimiento">
                                    <option value="">Todas</option>
                                    <option value="0">Taller</option>
                                    <option value="1">Subcontrato</option>
                                </select>
                            </div>
                            @endif
                            
                            <div class="zdmg-r02">
                                <label for="rangoFechas">Fecha</label>

                                 <div class="input-group">
                                    <input
                                        name="rangoFechas"
                                        id="rangoFechas"
                                        type="text"
                                        class="form-control"
                                        autocomplete="off"
                                        placeholder="Selecciona un rango"
                                        readonly
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="limpiarRangoFechas"
                                    >
                                        ×
                                    </button>
                                </div>
                                <input type="hidden" name="FechaInicio_seguimiento" id="FechaInicio_seguimiento">
                                <input type="hidden" name="FechaFin_seguimiento" id="FechaFin_seguimiento">
                            </div>
                            <div class="zdmg-r02">
                                <label for="estatus">Estatus</label>
                                <select name="estatus" class="form-control" id="estatus">
                                    <option value="">Todos</option>
                                    <option value="1" selected>Pendientes</option>
                                    <option value="2">Sin Diagnostico</option>
                                    <option value="3">Diagnostico En Proceso</option>
                                    <option value="11">Diagnostico Terminado</option>
                                    <option value="4">Diagnosticado sin Vales</option>
                                    <option value="5">Diagnosticado Con Vales Sin Entregar</option>
                                    <option value="12">Diagnosticado Con Vales Sin Confirmar</option>
                                    <option value="6">Diagnosticado Con Vales Pendientes</option>
                                    <option value="7">Diagnosticado Con Vales Terminados</option>
                                    <option value="8">Unidades Terminadas sin Verificar</option>
                                    <option value="9">Unidades Terminadas Verificadas</option>
                                    <option value="13">Entradas</option>
                                    <option value="10">Salidas</option>

                                </select>
                            </div>
                        </div>
                        <div id='dataupload'>
                            <div class="viewelements vanigrow vaniflex zdfd-column" id="viewelements">
                                <div class="elementosporpagina">
                                    <div id='pagination'></div>
                                </div>
                                <div class="mitabla vanigrow vaniflex zdfd-column">
                                    <table id="tablarecepciones" class="table table-sm  table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ord. Servicio</th>
                                                <th>Ord. Seguimiento</th>
                                                <th>Taller</th>
                                                <th>Ubicado</th>
                                                <th>Ubicacion</th>
                                                <th>Empresa</th>
                                                <th>Economico </th>
                                                <th>Placa</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Entrada</th>
                                                <th>Salida</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                 </div>
                            </div>
                            <div  class="no-results-message"  id="div-no-results-message" hidden>
                                <span id="no-results-message"></span>
                            </div>
                        </div>
                        
                        <div id='loadingdata' class="carga" hidden>
                            <h3 class="text-center m-2">Cargando Datos</h3>
                            <div class="spinnerp"></div>
                        </div>
                    </div>

                </div>
            </div>
    </div>

</main>
    @include('modales.ValesDeAlmacen')
    @include('modales.CambiarModulo')
    @include('modales.ConcentradoDeVales')
    @include('modales.RetrasoSalida')
    @include('modales.TablaTrabajosParciales')
    @include('modales.TallerOrden')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{asset('js/paginacionv1.js')}}"></script>

    @stack('scripts')
    <script>
    $(function() {
        
        let elements = [];
        let totalelements = 0;
        let Page = 1;
        let itemsPerPage = 10;
        let typingTimer;
        const typingDelay = 1000; // 1 segundo
        searchdata();
        async function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.Recepciones.Vehiculares.Seguimineto.Elements') }}',
                    data:{
                        currentPage: Page,
                        itemsPerPage: itemsPerPage,
                        search: $('#search').val(),
                        estatus: $('#estatus').val(),
                        subcontrato: $('#subcontrato_seguimiento').val(),
                        taller: $('#taller').val(),
                        empresa: $('#empresas_seguimiento').val(),
                        inicio: $('#FechaInicio_seguimiento').val(),
                        fin: $('#FechaFin_seguimiento').val(),
                    },
                    success: function(response) {
                        elements = response.elements;
                        totalelements = response.totalelements;
                        console.log(totalelements);
                        document.getElementById('loadingdata').setAttribute('hidden', true);
                        document.getElementById('dataupload').removeAttribute('hidden');
                        showElements()
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            });
        }

        window.RedirectionFolio =function(orden,ruta){
            localStorage.setItem('ordenbuscar', orden);
            window.location.href = ruta
        }
        window.executeshowElements = function() {
            eval("showElements()");
        };
        window.executeSearchdata = function(newpage = 1,changePage= true) {
            if(changePage){
                Page = newpage;
            }
            eval("searchdata()");
        };
        
        function showElements() {
            ShowPagination(totalelements,8,Page);
            const user_id = {{ auth()->id() }}
            $('#tablarecepciones tbody').empty();
            if (totalelements > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
                document.getElementById('div-no-results-message').setAttribute('hidden', true);
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
                document.getElementById('div-no-results-message').removeAttribute('hidden');
                if($('#search').val()==''){
                    $('#no-results-message').text('No Hay Resultados Disponibles');
                }else{
                    $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con  '+$('#search').val() );
                }
            }
            $.each(elements, function(index, element) {
                const usersreturn=[170,1,element.User]
                let row = $('<tr class="zdrelative"></tr>');
                let presupuestoUrl = `{{ route('2025.Recepcion.Vehicular.View') }}?contrato=${element.contrato}&modulo=${element.modulo}&anio=${element.anio}&zona=${element.zona}`;

                let acciones1 = $('<td></td>');
                let acciones = $(`<div class='zdflex gap-2' id='OpcionesSalida${index}'></div>`);

                /* ================== BOTONES SUPERIORES ================== */
                let divbtn1 = document.createElement('div');
                divbtn1.className = 'zdflex flex-wrap justify-content-center align-items-center gap-1';
                if([1,170].includes(user_id)){
                    divbtn1.append(
                        crearBoton(
                            'btn btn-warning btn-sm zdrelative',
                            'Cambiar Taller',
                            '<i class="fas fa-wrench"></i>',
                            () => CambiarTaller(element.detallesgeneralesid,element.taller_id,element.taller)
                        )
                    );
                }
                if([1,170].includes(user_id)){
                        divbtn1.append(
                        crearBoton(
                            'btn btn-success btn-sm zdrelative',
                            'Cambiar Modulo',
                            '<i class="fas fa-table"></i>',
                            () => OpenChangerModuloCortana(element.detallesgeneralesid,element.modulo_cortana_id,element.modulo_cortana)
                        )
                    );
                }
                if(element.has_subcontrato == '1'){
                    if([1,170].includes(user_id)){
                         divbtn1.append(
                            crearBoton(
                                'btn btn-danger btn-sm zdrelative',
                                'Quitar Subcontrato',
                                '<i class="fas fa-folder-minus"></i>',
                                () => ToggleSubcontrato(element.detallesgeneralesid,element.has_subcontrato)
                            )
                        );
                    }
                }
                else{
                    divbtn1.append(
                    crearBoton(
                        'btn btn-primary btn-sm zdrelative',
                        'Ingresar Subcontrato',
                        '<i class="fas fa-folder-plus"></i>',
                        () => ToggleSubcontrato(element.detallesgeneralesid,element.has_subcontrato)
                    )
                );
                }
               
                divbtn1.append(
                    crearBoton(
                        'btn btn-warning btn-sm zdrelative',
                        'Mensajes',
                        '<i class="fa fa-comment-alt"></i>',
                        () => OpenRetrasoSalida(element.id)
                    )
                );

                
                divbtn1.append(
                    crearBoton(
                        'btn btn-success btn-sm p-0 zdrelative',
                        'Trabajos Parciales',
                        '<i class="fa-solid fa-plus"></i>',
                        () => OpenTrabajosParcialesModal(element.detallesgeneralesid)
                    )
                );
                if (element.Terminado){
                    divbtn1.append(
                        crearBoton(
                            'btn btn-info btn-sm zdrelative',
                            'Concentrado De Vales',
                            '<i class="fa-solid fa-ticket"></i>',
                            () => OpenConcentradoValesAlmacenModal(element.detallesgeneralesid)
                        )
                    );
                }

                acciones.append(divbtn1);

                /* ================== BOTONES DE ESTADO ================== */
                let divbtn = document.createElement('div');
                divbtn.className = 'zdflex justify-content-center align-items-center gap-1';

                if (element.Diagnostico_Inicio == null) {

                    divbtn.append(
                        crearBoton(
                            'btn btn-danger p-0 zdrelative',
                            'Iniciar Diagnostico',
                            '<i class="fa-solid fa-plus"></i> Iniciar Diagnostico',
                            () => UpdateDateSeguimiento(element.detallesgeneralesid, 1)
                        )
                    );

                } else if (element.Diagnostico_Terminado == null) {

                    divbtn.append(
                        crearBoton(
                            'btn btn-warning p-0 zdrelative',
                            'Terminar Diagnostico',
                            '<i class="fa-solid fa-plus"></i> Terminar Diagnostico',
                            () => UpdateDateSeguimiento(element.detallesgeneralesid, 2)
                        )
                    );

                } else {
                    if (element.Entregado == null) {
                    divbtn.append(
                        crearBoton(
                            'btn btn-success p-0 zdrelative',
                            'Diagnostico Completado',
                            '<i class="fa-solid fa-check"></i> Diagnostico Completado',
                            () => mostramensajeexito(
                                'Diagnostico Completado: ' + element.Diagnostico_Terminado.fecha
                            )
                        )
                    );}

                    if (element.Terminado == null) {
                        let classbtn='btn-secondary';

                        if(element.Vales>0){
                            if(element.ValesnoConfimados>0){
                                classbtn='btn-danger';
                            }else if(element.ValesPendientes > 0 ){
                                classbtn='btn-warning';
                            }else if(element.ValesEntregados > 0 ){
                                classbtn='btn-info';
                            }else if(element.ValesSurtidos>0){
                                classbtn='btn-success';
                            }
                        }
                        divbtn.append(

                            crearBoton(
                                'btn p-2 zdrelative '+classbtn,
                                'Vales De Almacen',
                                'Vales',
                                () => OpenValeAlmacenModal(
                                    element.detallesgeneralesid,
                                    element.OrdenServicio,
                                    true
                                )
                            )
                        );

                        divbtn.append(
                            crearBoton(
                                'btn btn-warning p-0 zdrelative',
                                'Terminar Unidad',
                                'Terminar Unidad',
                                () => UpdateDateSeguimiento(element.detallesgeneralesid, 3)
                            )
                        );

                    } else if (element.Verificado == null) {

                        divbtn.append(
                            crearBoton(
                                'btn btn-success p-0 zdrelative',
                                'Unidad Terminada',
                                'Unidad Terminada',
                                () => mostramensajeexito(
                                    'Unidad Terminada: ' + element.Terminado.fecha
                                )
                            )
                        );

                        divbtn.append(
                            crearBoton(
                                'btn btn-warning p-0 zdrelative',
                                'Verificar Unidad',
                                'Verificar Unidad',
                                () => UpdateDateSeguimiento(element.detallesgeneralesid, 4)
                            )
                        );

                        divbtn.append(
                            crearBoton(
                                'btn btn-danger p-0 zdrelative',
                                'Unidad Sin Terminar',
                                'Unidad Sin Terminar',
                                () => UpdateDateSeguimiento(element.detallesgeneralesid, 3)
                            )
                        );

                    } else if (element.Entregado == null) {

                        divbtn.append(
                            crearBoton(
                                'btn btn-success p-0 zdrelative',
                                'Unidad Verificada',
                                'Unidad Verificada',
                                () => mostramensajeexito(
                                    'Unidad Verificada: ' + element.Verificado.fecha
                                )
                            )
                        );

                        divbtn.append(
                            crearBoton(
                                'btn btn-warning p-0 zdrelative',
                                'Entregar Unidad',
                                'Entregar Unidad',
                                () => UpdateDateSeguimiento(element.detallesgeneralesid, 5)
                            )
                        );

                    } else {

                        divbtn.append(
                            crearBoton(
                                'btn btn-success p-0 zdrelative',
                                'Unidad Entregada',
                                'Unidad Entregada',
                                () => mostramensajeexito(
                                    'Unidad Entregada: ' + element.Entregado.fecha
                                )
                            )
                        );
                        divbtn.append(
                            crearBoton(
                                'btn btn-danger p-0 zdrelative',
                                'Unidad Entregada',
                                'Reingresar Unidad',
                                () => Reingresar(element.detallesgeneralesid)
                            )
                        );
                        
                    }
                }

                acciones.append(divbtn);
                acciones1.append(acciones);
                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.OrdenServicio+'`,'+'`'+presupuestoUrl+'`)">' + element.OrdenServicio + '</a></div></td>');
                row.append('<td><div class="">' + element.OrdenSeguimiento+ '</div></td>');
                row.append('<td><div class="">' + element.taller + '</div></td>');
                row.append('<td><div class="">' + element.subcontrato + '</div></td>');
                row.append('<td><div class="">' + element.Ubicacion + '</div></td>');
                row.append('<td><div class="">' + element.Empresa + '</div></td>');
                row.append('<td><div class="">' + element.Economico + '</div></td>');
                row.append('<td><div class="">' + element.Placa + '</div></td>');
                row.append('<td><div class="">' + element.Marca + '</div></td>');
                row.append('<td><div class="">' + element.Modelo + '</div></td>');
                row.append('<td><div class="">' + element.Entrada + '</div></td>');
                row.append('<td><div class="">' + (element.Salida != null ? element.Salida : 'En Taller') + '</div></td>');
                row.append(acciones1);
                ;
                $('#tablarecepciones tbody').append(row);
            });
        }

        $('#search').on('input', async function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(async () => {
                Page = 1;
                await searchdata();
            }, typingDelay);
        });
        $('#estatus').on('change', async function () {
            Page = 1;
             searchdata();
        });
        $('#taller').on('change', async function () {
            Page = 1;
             searchdata();
        });
        $('#subcontrato_seguimiento').on('change', async function () {
            Page = 1;
             searchdata();
        });
        $('#empresas_seguimiento').on('change', async function () {
            Page = 1;
             searchdata();
        });
        let ultimaFechaInicio = '';
        let ultimaFechaFin = '';
        function actualizarTextoRango(inicio, fin) {
            if (!inicio && !fin) {
                $('#rangoFechas').val('');
                return;
            }

            if (inicio === fin) {
                $('#rangoFechas').val(inicio);
                return;
            }

            $('#rangoFechas').val(inicio + ' a ' + fin);
        }
        function buscarSiCambioFecha() {
            const inicioActual = $('#FechaInicio_seguimiento').val();
            const finActual = $('#FechaFin_seguimiento').val();

            if (inicioActual !== ultimaFechaInicio || finActual !== ultimaFechaFin) {
                ultimaFechaInicio = inicioActual;
                ultimaFechaFin = finActual;

                Page = 1;
                searchdata();
            }
        }
       const calendarioRango = flatpickr('#rangoFechas', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            locale: 'es',
            allowInput: false,
            clickOpens: true,

            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 0) {
                    $('#FechaInicio_seguimiento').val('');
                    $('#FechaFin_seguimiento').val('');
                    $('#rangoFechas').val('');
                    return;
                }

                if (selectedDates.length === 1) {
                    const fecha = instance.formatDate(selectedDates[0], 'Y-m-d');

                    $('#FechaInicio_seguimiento').val(fecha);
                    $('#FechaFin_seguimiento').val(fecha);
                    actualizarTextoRango(fecha, fecha);
                    return;
                }

                const inicio = instance.formatDate(selectedDates[0], 'Y-m-d');
                const fin = instance.formatDate(selectedDates[1], 'Y-m-d');

                $('#FechaInicio_seguimiento').val(inicio);
                $('#FechaFin_seguimiento').val(fin);
                actualizarTextoRango(inicio, fin);
            },

            onClose: function (selectedDates, dateStr, instance) {
                const inicio = $('#FechaInicio_seguimiento').val();
                const fin = $('#FechaFin_seguimiento').val();

                actualizarTextoRango(inicio, fin);
                buscarSiCambioFecha();
            }
        });

        $('#limpiarRangoFechas').on('click', function () {
            calendarioRango.clear();

            $('#FechaInicio_seguimiento').val('');
            $('#FechaFin_seguimiento').val('');
            $('#rangoFechas').val('');

            buscarSiCambioFecha();
        });
        
       
        
        window.Reingresar=function(id) {
            Swal.fire({
                icon: "question",
                text: "¿Estás seguro de Reingresar La Unidad?",
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
                    $.ajax({
                            type: 'post',
                            url: '{{ route('Detalles.Generales.Reingreso') }}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                DetGenId: id,
                            },
                            success: function(response) {
                                mostramensajeexito(response.message||'Reingresado')
                                executeSearchdata()
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
                            }
                        });
                }
            });
        }
        window.ReporteSeguimiento=function(id,estado){
            window.open('/zcrat/generate/reporte/seguimiento/pdf','_blank');
        }
        window.ToggleSubcontrato=function(id,estado) {
            let message=estado == '0' ? '¿Estás seguro de ponerlo en subcontrato?'  : '¿Estás seguro de quitarlo del subcontrato?' ;
            Swal.fire({
                icon: "question",
                text: message,
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
                    $.ajax({
                            type: 'post',
                            url: '{{ route('Detalles.Generales.subcontrato') }}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                DetGenId: id,
                            },
                            success: function(response) {
                                mostramensajeexito(response.message||'Reingresado')
                                executeSearchdata()
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
                            }
                        });
                }
            });
        }
        

        UpdateDateSeguimiento = async function(DetGenId,TipoFecha) {
            let url='{{ route('2025.Recepciones.Vehiculares.Update.Date.Seguimiento') }}';

            $.ajax({
                    type: 'post',
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}",
                        DetGenId: DetGenId,
                        tipoFecha: TipoFecha
                    },
                    success: function(response) {
                        mostramensajeexito(response.message||'Actualizado')
                        executeSearchdata()
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
                    }
                });
        }
    });
</script>
@endsection