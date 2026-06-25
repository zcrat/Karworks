@extends ('layouts.admin2')
@section ('contenido')

<main class="main vaniflex vanigrow">
    <div class="container-fluid vaniflex vanigrow">
            <div class="card vanigrow">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>TODOS LOS PRESUPUESTOS
                </div>
                <div class="card-body mycard ">
                    <div class="vaniwidth vaniflex zdfd-column" id="dataupload" >
                        <div class="d-flex">
                        
                            <div class="iconoin zdmgr-r05">
                                <input class="misearch zdw-r29"
                                    type="text" id="search" name="s"
                                    placeholder="Busqueda Por Ord. Servicio Folio, Marca, Modelo, Vin, Economico, etc" >
                                    <i class="fa fa-search" aria-hidden="true"></i>&nbsp;
                            </div>
                            
                        </div>
                        <div class="viewelements vanigrow vaniflex zdfd-column" id="viewelements">
                            <div class="elementosporpagina">
                                <select   class="rounded" id="epp">
                                <option value="10" >10</option>
                                    @for ($i = 15; $i <= $elementostotales/3; $i += 5)
                                        <option value="{{ $i }}" >{{ $i }}</option>
                                    @endfor
                                </select>
                                <div id='pagination'></div>
                            </div>
                            <div class="mitabla vanigrow vaniflex zdfd-column">
                                <table id="tablarecepciones" class="table table-sm  table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ord. Servicio</th>
                                            <th>Ord. Seguimiento</th>
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
                        <div  class="no-results-message" hidden>
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

    @include('modales.viewarchivopdf')
    @include('modales.UbicacionDetallesGenerales')
    @include('modales.SalidaVehiculo')
    @include('modales.RetrasoSalida')
    @include('modales.TablaTrabajosParciales')
    @include('modales.ValesDeAlmacen')
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/paginacion.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
@stack('scripts')
<script>
    $(function() {
        
        let elements = [];
        let originalelements = [];
        searchdata();
        function searchdata() {
            document.getElementById('loadingdata').removeAttribute('hidden');
            document.getElementById('dataupload').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('2025.Recepciones.Vehiculares.Get.All.Elements') }}',
                data:{
                },
                success: function(response) {
                    originalelements = elements = response.elements;
                    document.getElementById('loadingdata').setAttribute('hidden', true);
                    document.getElementById('dataupload').removeAttribute('hidden');
                    filtering()
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }

        window.RedirectionFolio =function(orden,ruta){
        localStorage.setItem('ordenbuscar', orden);
        window.location.href = ruta
    }
        window.executeshowElements = function() {
            eval("showElements()");
        };
        window.executeSearchdata = function() {
            eval("searchdata()");
        };
        function showElements() {
            ShowPagination(elements.length,8);
            let startIndex = (Page - 1) * itemsPerPage;
            let endIndex = startIndex + itemsPerPage;
            let paginatedElements = elements.slice(startIndex, endIndex);
            const user_id = {{ auth()->id() }}
            $('#tablarecepciones tbody').empty();
            if (paginatedElements.length > 0) {
                document.getElementById('viewelements').removeAttribute('hidden');
            } else {
                document.getElementById('viewelements').setAttribute('hidden', true);
            }
            $.each(paginatedElements, function(index, element) {
                const usersreturn=[170,1,element.User]
                let row = $('<tr class="zdrelative"></tr>');
                let acciones1 =$(`<td></td>`);
                let acciones =$(`<div class='zdflex gap-2' id='OpcionesSalida`+index+`'></div>`);
                let divbtn = document.createElement('div');
                divbtn.className = 'zdflex flex-wrap justify-content-center align-items-center gap-1';
                let btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-warning btn-sm zdrelative';
                btn.title = 'Mensajes';
                btn.innerHTML = '<i class="fa fa-comment-alt"></i>';
                btn.onclick = function() { OpenRetrasoSalida(element.id); };
                divbtn.append(btn);

                btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-success btn-sm p-0 zdrelative justify-content-center align-items-center';
                btn.title = 'Vales De Almacen';
                btn.innerHTML = '<i class="fa-solid fa-ticket"></i>';
                btn.onclick = function() { OpenValeAlmacenModal(element.detallesgeneralesid,element.OrdenServicio);};
                divbtn.append(btn);
                acciones.append(divbtn);

                divbtn2 = document.createElement('div');
                divbtn2.className = 'zdflex flex-wrap justify-content-center align-items-start gap-1';

                btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-success btn-sm p-0 zdrelative justify-content-center align-items-center';
                btn.title = 'Trabajos Parciales';
                btn.innerHTML = '<i class="fa-solid fa-plus"></i>';
                btn.onclick = function() { OpenTrabajosParcialesModal(element.detallesgeneralesid);};
                divbtn2.append(btn);

                if(element.Salida){
                    if(usersreturn.includes(user_id) ){
                        btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-danger btn-sm p-0 zdrelative justify-content-center align-items-center ';
                        btn.title = 'Reingresar';
                        btn.innerHTML = '<i class="fa-solid fa-retweet"></i><';
                        btn.onclick = function() { Reingresar(element.detallesgeneralesid)};
                        divbtn2.append(btn);
                    }
                }
                acciones.append(divbtn2);
                let presupuestoUrl =`{{ route('2025.Recepcion.Vehicular.View') }}?contrato=${element.contrato}&modulo=${element.modulo}&anio=${element.anio}&zona=${element.zona}`;
                const Botones=['Diagnostico','PedidoHecho','PedidoEntregado','Salida']
                let botonactivado=false
                Botones.forEach((Boton)=>{
                    const BTN = document.createElement('button');
                    let textoBoton = Boton.replace(/_/g, '').replace(/([a-z])([A-Z])/g, '$1 $2');
                        BTN.textContent = textoBoton;
                        BTN.setAttribute('idordenservicio', element.detallesgeneralesid);
                        BTN.setAttribute('index', index);
                        let clases='btn '+Boton;
                    if(element[Boton]){
                       clases+=' btn-success';
                        BTN.setAttribute('disabled', true);
                        
                    }else if(!botonactivado){
                       clases+=' btn-warning';
                        botonactivado=true
                    }else{
                       clases+=' btn-danger';
                        BTN.setAttribute('disabled', true);
                    }
                    BTN.className = clases;
                    acciones.append(BTN);
                })
                acciones1.append(acciones);

                row.append('<td><div class=""><a class="milink" onclick="RedirectionFolio( `' +element.OrdenServicio+'`,'+'`'+presupuestoUrl+'`)">' + element.OrdenServicio + '</a></div></td>');
                row.append('<td><div class="">' + element.OrdenSeguimiento+ '</div></td>');
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

        $('#search').on('input', filtering);
        function filtering() { 
            let search = $('#search').val().toLowerCase();
            Page = 1
                elements = originalelements.filter(function(element) {

                    return ((search === '' || 
                    element.OrdenServicio.toLowerCase().includes(search) || 
                    element.OrdenSeguimiento.toLowerCase().includes(search) || 
                    element.Empresa.toLowerCase().includes(search) || 
                    element.Economico.toLowerCase().includes(search) ||
                    element.Placa.toLowerCase().includes(search) ||
                    element.Marca.toLowerCase().includes(search) ||
                    element.Modelo.toLowerCase().includes(search)))
                

                });
            if (elements.length === 0) {
                document.querySelector('.no-results-message').removeAttribute('hidden');
                $('#no-results-message').text('No Se Encontraron Resultados Con Folio, Economico, Placas, Vin, Modelos o Marca Que Coincidan Con  '+search );
                
            } else {
                document.querySelector('.no-results-message').setAttribute('hidden',true);
                $('#no-results-message').text('');
            }
            showElements();
            
        }
        $(document).on('click', '.Diagnostico', function() {

                Swal.fire({
                    icon: "question",
                    text: "¿Estás seguro de guardar la hora de inicio del diagnostico?",
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
                        index =$(this).attr('index')
                        $.ajax({
                            type: 'post',
                            url: '{{ route('Detalles.Generales.Register.Diagnostico') }}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                DetGenId: $(this).attr('idordenservicio'),
                            },
                            success: function(response) {
                                mostramensajeexito(response.message||'Registrado')
                                BotonesActualizacion(response.element,index)
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
        })
        $(document).on('click', '.PedidoHecho', function() {
            Swal.fire({
                        icon: "question",
                        text: "¿Estás seguro de guardar la hora de pedido de refacciones?",
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
                            index =$(this).attr('index')
                            $.ajax({
                                    type: 'post',
                                    url: '{{ route('Detalles.Generales.Register.PedidoHecho') }}',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        DetGenId: $(this).attr('idordenservicio'),
                                    },
                                    success: function(response) {
                                        mostramensajeexito(response.message||'Registrado')
                                        BotonesActualizacion(response.element,index)
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
        })
        $(document).on('click', '.PedidoEntregado', function() {
             Swal.fire({
                        icon: "question",
                        text: "¿Estás seguro de guardar la hora de entrega de refacciones?",
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
                            index =$(this).attr('index')   
            $.ajax({
                    type: 'post',
                    url: '{{ route('Detalles.Generales.Register.PedidoEntregado') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        DetGenId: $(this).attr('idordenservicio'),
                    },
                    success: function(response) {
                        mostramensajeexito(response.message||'Registrado')
                        BotonesActualizacion(response.element,index)
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
            })
            $(document).on('click', '.Salida', function() {
                OpenDetGenEntSalModal($(this).attr('idordenservicio'));
        })
        function BotonesActualizacion(element,index){
                const Botones=['Diagnostico','PedidoHecho','PedidoEntregado','Fecha_salida']
                const div=$("#OpcionesSalida"+index)
                console.log('Div encontrado:', div.length);
                console.log('vaciando div'+index)
                div.empty()
                let btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-warning btn-sm zdrelative';
                btn.title = 'Mensajes';
                btn.innerHTML = '<i class="fa fa-comment-alt"></i>';
                btn.onclick = function() { OpenRetrasoSalida(element.id); };
                div.append(btn);
                let botonactivado=false
                Botones.forEach((Boton)=>{
                    const BTN = document.createElement('button');
                    let textoBoton = Boton.replace(/_/g, '').replace(/([a-z])([A-Z])/g, '$1 $2');
                        BTN.textContent = textoBoton;
                        BTN.id = Boton;
                        BTN.setAttribute('idordenservicio', element.id);
                        BTN.setAttribute('index', index);
                    if(element[Boton]){
                        BTN.className = `btn btn-success`;
                        BTN.setAttribute('disabled', true);
                        
                    }else if(!botonactivado){
                        BTN.className = `btn btn-warning`;
                        botonactivado=true
                    }else{
                        BTN.className = `btn btn-danger`;
                        BTN.setAttribute('disabled', true);
                    }
                    div.append(BTN);
                })
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
    });
</script>
@endsection