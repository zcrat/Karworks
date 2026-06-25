@extends ('layouts.admin2')
    @section('contenido')
        <main class="main vaniflex vanigrow">
            <div class="container-fluid vaniflex vanigrow">
                <div class="card vanigrow">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Recepcion Vehicular
                        <button type="button" class="boton1" data-bs-toggle="modal" onclick="limpiarmodalrecepciones()"
                            data-bs-target="#RecepcionVehicular">
                            <i class="fa-solid fa-circle-plus"></i>&nbsp;Nueva
                        </button>
                        <div id="submenudemo">
                       
                        </div>
                    </div>
                    <div class="card-body mycard ">
                        <div class="vaniwidth" id="dataupload">
                            <div class="d-flex">
                                <div class="iconoin zdmgr-r05">
                                    <input class="misearch zdw-r29" type="text" id="search" name="s"
                                        placeholder="Busqueda Por #Orden,, Folio, Marca, Modelo, etc">
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                                </div>

                                <div class="vaniflex zditemscenter">
                                    <label class="zdmgr-r02">Empresas:</label>
                                    <select class="empresas-Select2" id="empresas">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="viewelements" id="viewelements">
                                <div class="elementosporpagina">
                                    <select class="rounded" id="epp">
                                        <option value="10">10</option>
                                        @for ($i = 15; $i <= $elementostotales / 3; $i += 5)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div id='pagination'></div>
                                </div>
                                <div class="mitabla">
                                    <table id="tablarecepciones" class="table table-sm  table-striped">
                                        <colgroup>
                                            <col class="button_options"> <!-- Columna con ancho fijo del 20% -->

                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Ord. Servicio</th>
                                                <th>Ord. Seguimiento</th>
                                                <th>Ubicacion</th>
                                                <th>Empresa</th>
                                                <th>Vehiculo</th>
                                                <th>F. Recepcion</th>
                                                <th>F. Compromiso</th>
                                                <th>Accciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="no-results-message" hidden>
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
            @include('modales.RecepcionVehicularModal')
            @include('modales.PreupuestoModal')
            {{-- @include('modales.RecepcionVehicularPDF') --}}
            @include('modales.viewarchivopdf')
            @include('modales.VehiculoModel')
            @include('modales.ModalOneAttribute')
            @include('modales.clientes')
            @include('modales.InspeccionVehicular')


        </main>
    @endsection
    @section('scripts')
        <script src="{{ asset('js/paginacion.js') }}"></script>
        <script src="{{ asset('js/canvas.js') }}"></script>
        <script>
            $(function() {
                let elements = [];
                let originalelements = [];
                const modulo = @json($modulo);
                const anio = @json($anio);
                const contrato = @json($contrato);
                const zona = @json($zona);
                let fol=localStorage.getItem('ordenbuscar')
        if(fol){
            console.log(fol)
            $('#search').val(fol).trigger('input');
            localStorage.removeItem('ordenbuscar');
        };
                window.executeSearchdata = function() {
                    document.getElementById('loadingdata').removeAttribute('hidden');
                    document.getElementById('dataupload').setAttribute('hidden', true);
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('2025.Recepcion.Vehicular.Get.Elements') }}',
                        data: {
                            modulo: modulo,
                            anio: anio,
                            zona: zona,
                            contrato: contrato
                        },
                        success: function(response) {
                            originalelements = elements = response.elements;
                            document.getElementById('loadingdata').setAttribute('hidden', true);
                            document.getElementById('dataupload').removeAttribute('hidden');
                            filtering();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                        }
                    });
                }
                window.executedelete = function(id) {
                    let ruta = "{{ route('2025.Recepcion.Vehicular.delete') }}";
                    Swal.fire({
                        icon: "question",
                        text: "¿Estás Seguro de Eliminar La Recepción Vehicular?",
                        showCancelButton: true,
                        confirmButtonText: "Confirmar",
                        cancelButtonText: "Cancelar",
                        reverseButtons: true,
                        customClass: {
                            confirmButton: "btn-primary",
                            cancelButton: "btn-light",
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: ruta,
                                method: "DELETE",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    let message=response.success;
                                    Swal.fire('Éxito', `${message}`, 'success');
                                    executeSearchdata();
                                    },
                                error: function(xhr, status, error) {
                                if(xhr.status===422){
                                    Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                                }else{
                                    let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                    Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                                }
                                }
                            });
                        }
                    });
                };
                window.executeservicio = function(id) {
                    $('#recepcionservicio').modal("show");
                };
                window.executereporte = function(id) {
                    $.ajax({
                        url: "{{ route('2025.Recepcion.Vehicular.PDF') }}",
                        method: 'GET',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                                const newWindow = window.open('', '_blank');
                                newWindow.document.write(response.html);
                                newWindow.document.close();
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            Swal.fire({
                                icon: "error",
                                title: "Error en la solicitud",
                                text: "Ocurrió un error inesperado. Contacta a soporte.",
                                timer: 5000
                            });
                        }
                    });
                }
                window.executeshowElements = function () {
                    ShowPagination(elements.length, 8);
                    let startIndex = (Page - 1) * itemsPerPage;
                    let endIndex = startIndex + itemsPerPage;
                    let paginatedElements = elements.slice(startIndex, endIndex);
                    $('#tablarecepciones tbody').empty();
                    if (paginatedElements.length > 0) {
                        document.getElementById('viewelements').removeAttribute('hidden');
                    } else {
                        document.getElementById('viewelements').setAttribute('hidden', true);
                    }
                    $.each(paginatedElements, function(index, element) {
                        const dg = element.detalles_generales ? element.detalles_generales : {};
                        const row = $('<tr>');

                        // columnas principales
                        row.append('<td><div class="Datatable-content">' + (dg.OrdenServicio ? dg.OrdenServicio : 'Sin #ORDEN') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (dg.OrdenSeguimiento ? dg.OrdenSeguimiento : 'Sin # Seguimiento') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (dg.Ubicacion ? dg.Ubicacion : 'folio') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (dg.empresa && dg.empresa.nombre ? dg.empresa.nombre : 'Sin Empresa') + '</div></td>');

                        let vehiculo = dg.vehiculo;
                        let vehiculoInfo = vehiculo
                            ? (vehiculo.placas ? vehiculo.placas : 'Sin Placas')
                            + ' -- ' + (vehiculo.marca && vehiculo.marca.nombre ? vehiculo.marca.nombre : 'Sin Marca')
                            + ' -- ' + (vehiculo.modelo && vehiculo.modelo.nombre ? vehiculo.modelo.nombre : 'Sin Marca')
                            + ' -- ' + (vehiculo.no_economico ? vehiculo.no_economico : 'Sin No Economico')
                            : 'El vehículo no tiene datos';
                        row.append('<td><div class="Datatable-content">' + vehiculoInfo + '</div></td>');

                        row.append('<td><div class="Datatable-content">' + (dg.Fecha_entrada ? dg.Fecha_entrada : 'No Se Registro') + '</div></td>');
                        row.append('<td><div class="Datatable-content">' + (dg.Fecha_Esperada ? dg.Fecha_Esperada : 'No Se Registro') + '</div></td>');

                        // Asegura userId inyectado desde Blade antes de este script:
                       let  userId = {{ auth()->id() ?? 'null' }}

                        // botones base
                        let baseButtons = ''
                            + '<button class="btn btn-success reporte" onclick="executereporte(' + (dg.id ? dg.id : 1) + ')"><i class="fa fa-eye"></i></button>'
                            + '<button class="btn btn-warning" onclick="executeeditarrecepcion(' + element.id + ')"><i class="fa fa-pencil-square-o"></i></button>'
                            + '<button class="btn btn-danger" onclick="executedelete(' + element.id + ')"><i class="fa-solid fa-trash"></i></button>'
                            + '<button class="btn btn-info" onclick="NewPresupuesto(\'' + element.id + '\')"><i class="fa-solid fa-file"></i></button>';

                        // extras
                        let extraButtons = '';
                        if (userId === 1 || userId === 170) {
                            extraButtons += ''
                                + '<button class="btn btn-info" onclick="OpenInspenccionVehicular(' + (dg.id ? dg.id : 1) + ')">IV</button>'
                                + '<button class="btn ' + (element.Update_fotos == 0 ? 'btn-danger' : 'btn-success') + '"'
                                + ' onclick="executecambiarimagenes(\'' + element.id + '\',\'' + element.Update_fotos + '\')">R</button>';
                        }
                        // const usersreturn=[170,1,dg.User_id];
                        // if (dg.Fecha_salida  && usersreturn.includes(userId) ) {
                        //     extraButtons += ''
                        //         + '<button class="btn btn-danger btn-sm p-0 zdrelative justify-content-center align-items-center"'
                        //         + ' onclick="Reingresar(' + (dg.id ? dg.id : 1) + ')"><i class="fa-solid fa-retweet"></i></button>';
                        // }

                        // columna de acciones
                        row.append('<td><div class="Datatable-content">' + baseButtons + extraButtons + '</div></td>');

                        $('#tablarecepciones tbody').append(row);
                    });
                }
                function filtering() {
                    let search = $('#search').val().toLowerCase();
                    const empresas = document.getElementById("empresas");
                    let option1 = empresas.value;
                    let nombreempresa = empresas.options[empresas.selectedIndex].text;
                    Page = 1
                    elements = originalelements.filter(function(element) {
                        return (option1 === '' || element.detalles_generales.Empresa_id == option1) && (search === '' ||
                        element.detalles_generales.OrdenServicio.toLowerCase().includes(search) ||
                        element.detalles_generales.OrdenSeguimiento.toLowerCase().includes(search) ||
                        element.detalles_generales.Ubicacion.toLowerCase().includes(search) ||
                        element.detalles_generales.vehiculo.marca.nombre.toLowerCase().includes(search) ||
                        element.detalles_generales.vehiculo.modelo.nombre.toLowerCase().includes(search) ||
                        element.detalles_generales.vehiculo.no_economico.toLowerCase().includes(search) ||
                        element.detalles_generales.vehiculo.placas.toLowerCase().includes(search));

                    });
                    if (elements.length === 0) {
                        document.querySelector('.no-results-message').removeAttribute('hidden');
                        if (search !== '' && option1 === '') {
                            $('#no-results-message').text(
                                'No Se Encontraron Recepcion Vehiculares Con #Orden, Ord. Seguimmiento, Folio, Marca, Modelo o Placas que Coincidan Con ' +
                                search);
                            } else if (search === '' && option1 !== '') {
                            $('#no-results-message').text('No Se Encontraron Recepcion Vehiculares de la Empresa ' +
                                nombreempresa);
                            } else {
                                $('#no-results-message').text('No Existen  Recepciones Vehiculares');
                        }
                    } else {
                        document.querySelector('.no-results-message').setAttribute('hidden', true);
                        $('#no-results-message').text('');
                    }
                    executeshowElements();
                    
                }
                $('#search').on('input', filtering);
                $("#empresas").change(filtering);
                executeSearchdata();
            });

            window.executecambiarimagenes = function(id,actualizar) {
                let ruta = "{{ route('2025.Recepcion.Vehicular.fotosupdate') }}";
                if(actualizar == 0){
                    text="¿Estás Seguro de Aceptar Cambios En las Imagenes De Evidencia?"
                }else{
                    text="¿Estás Seguro de Ya No Aceptar Cambios En las Imagenes De Evidencia?"
                }
                    Swal.fire({
                        icon: "question",
                        text: text,
                        showCancelButton: true,
                        confirmButtonText: "Confirmar",
                        cancelButtonText: "Cancelar",
                        reverseButtons: true,
                        customClass: {
                            confirmButton: "btn-primary",
                            cancelButton: "btn-light",
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: ruta,
                                method: "PUT",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    let message=response.success;
                                    Swal.fire('Éxito', `${message}`, 'success');
                                    executeSearchdata();
                                    },
                                error: function(xhr, status, error) {
                                if(xhr.status===422){
                                    Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                                }else{
                                    let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                    Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                                }
                                }
                            });
                        }
                    });
            };
            $("#serviciorecepcionform").submit(function(e) {
                e.preventDefault();
                let modal = $("#recepcionservicio");
                let guardar = $("#guardarDatos")
                let ruta = "{{ route('2025.Recepcion.Vehicular.presupuesto.create') }}";
                let data = new FormData(); // Crear un objeto FormData
                data.append('detallesgenerales_id', $("#detallesgenerales_id").val());
                data.append('rsServicio', $("#rsServicio").val());
                data.append('rsObservaciones', $("#rsObservaciones").val());
                data.append('rsFolio', $("#rsFolio").val());
                
                modal.modal("hide");
                guardar.attr("disabled", true);
                Swal.fire({
                        icon: "question",
                        text: "¿Estás seguro de guardar el presupuesto?",
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
                                $.ajax({
                                url: ruta, // URL de la ruta
                                type: 'POST', // Método HTTP
                                data: data, // Enviar el FormData
                                processData: false, // Evita que jQuery procese automáticamente el FormData
                                contentType: false, // No establece automáticamente el tipo de contenido
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregar token CSRF en el encabezado
                                },
                                success: function(response) {
                                    guardar.attr("disabled", false);
                                    Swal.fire({
                                        icon: "success",
                                        title: response.success,
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                },
                                error: function(error) {
                                    guardar.attr("disabled", false);
                                    if (error.status === 422) {
                                    $("#serviciorecepcionform").find(".error-message").remove();
                                    let errors = error.responseJSON.errors;
                                    let errorMessages = Object.values(errors).map((msgs) =>{if(msgs != "Este campo es obligatorio." && msgs != "La opción no es válida"){return msgs.join("<br>")}}).filter(Boolean).join("<br>");
                                    for (let field in errors) {
                                        let input = $("#serviciorecepcionform").find(`[name="${field}"]`);
                                        let errorMessage =
                                            `<small class="text-danger error-message">${errors[field].join("<br>")}</small>`;
                                        input.after(errorMessage);
                                    }
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Errores",
                                        html: errorMessages,
                                    }).then(() => {
                                        modal.modal("show");

                                    });
                                }else {
                                    console.log(error)
                                    let errorMessage = 'Intentelo de nuevo';
                                    Swal.fire({ 
                                        title: 'Error : '+error.status, 
                                        html: `${errorMessage}<br>Si El Error Continua Contacte A Soporte <br>Detalles del error: <br>${error.responseJSON.message}`, 
                                        icon: 'error',
                                    }).then(() => {modal.modal("show");});
                                }

                            }
                            });
                        } else {
                            modal.modal("show");
                            guardar.attr("disabled", false);

                        }
                    });

            });
            window.NewPresupuesto = function(id){
                $.ajax({
                    url: '{{ route('2025.Recepcion.Vehicular.Get.Element') }}',
                    method: "GET",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        console.log(response)
                        element= response.recepcion
                        $('.zdhidden').attr('hidden',true);
                        $('#tipovehiculo2').removeAttr('required');
                        $('#reditcar').attr('data-id',element.detalles_generales.vehiculo.id);
                        $('#rsmodelo').val(element.detalles_generales.vehiculo.modelo.nombre);
                        $('#rsvin').val(element.detalles_generales.vehiculo.vim);
                        $('#rsplacas').val(element.detalles_generales.vehiculo.placas);
                        $('#rsAño').val(element.detalles_generales.vehiculo.anio);
                        $('#rsMarca').val(element.detalles_generales.vehiculo.marca.nombre);
                        $('#rsUbicación').val(element.detalles_generales.vehiculo).attr('disabled',true);
                        $('#rsFecha_Alta').val(element.detalles_generales.Fecha_entrada).attr('disabled',true);;
                        $('#rsFolio').val(element.detalles_generales.OrdenServicio).removeAttr('disabled');
                        $('#rsnorden').val(element.detalles_generales.OrdenServicio).attr('disabled',true);
                        $('#rsordenseg').val(element.detalles_generales.OrdenSeguimiento).attr('disabled',true);
                        $('#rsubicacion').val(element.detalles_generales.Ubicacion).attr('disabled',true);
                        $('#rsKm_De_Ingreso').val(element.detalles_generales.Kilometraje_entrada).attr('disabled',true);;
                        $('#detallesgenerales_id').val(element.detalles_generales.id);
                        $("#empresasrecepcion2").empty().append('<option value="' + element.detalles_generales.empresa.id + '">' + element.detalles_generales.empresa.nombre + '</option>').attr('disabled',true);
                        $("#clientesrecepcion2").empty().append('<option value="' + element.detalles_generales.Customer_id + '">' + element.detalles_generales.customer.nombre + '</option>').attr('disabled',true);
                        $("#vehiculopresupuesto").empty().append('<option value="' + element.detalles_generales.vehiculo.id + '">' + element.detalles_generales.vehiculo.no_economico + '-' + element.detalles_generales.vehiculo.placas + '</option>').attr('disabled',true);;
                        $("#admintrasportedemo2").empty().append('<option value="' + element.detalles_generales.administrador_trasporte.id + '">' + element.detalles_generales.administrador_trasporte.nombre + '</option>').attr('disabled',true);;
                        $("#jefedelprocesodemo2").empty().append('<option value="' + element.detalles_generales.jefede_proceso.id + '">' + element.detalles_generales.jefede_proceso.nombre + '</option>').attr('disabled',true).attr('disabled',true);;
                        $("#Trabajadordemo2").empty().append('<option value="' + element.detalles_generales.trabajador.id + '">' + element.detalles_generales.trabajador.nombre + '</option>').attr('disabled',true);;
                        $("#rsObservaciones").val(element.Notas); 
                        $('#recepcionservicio').modal("show");
                    },
                    error: function(xhr, status, error) {
                    if(xhr.status===422){
                        Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                    }else{
                        let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                        Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                    }
                    }
                });
            }
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
                                    url: '{{ route('Detalles.Generales.Delete.Salida') }}',
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
            function mensajefallo(tittle,html,time=2000,showbtn=false){
                const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: showbtn,
                        timer: time,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                        });
                        Toast.fire({
                        icon: "error",
                        title: tittle,
                        html:html
                    });
            }
        </script>
        @stack('scripts')
    @endsection
