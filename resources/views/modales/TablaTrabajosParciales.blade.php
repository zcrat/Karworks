<div class="modal fade" id="TrabajosParcialesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="TrabajosParcialesModalLabel">Lista De Trabajos Parciales</h5>
                <button type="button" class="btn-close CloseTrabajosParcialesModal"></button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <div class="zdflez zdjc-start zdai-center zdfd-column">
                    <button type="button" class="btn btn-primary" onclick="CreateTrabajoParcial()">Crear Trabajo Parcial</button>
                    <div id="ListaTrabajosParciales" hidden>
                        <table id="TablaTrabajosParciales" class="table table-sm  table-striped" hidden>
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Fecha</th>
                                    <th>Horas</th>
                                    <th>Usuario</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="TrabajosParcialesMessage">
                        <span>No Tiene Trabajos Registrados</span>
                    </div>
                </div>
                <div id='ListaCargaTrabajosParciales' class="carga" >
                    <h3 class="text-center m-2">Cargando Datos</h3>
                    <div class="spinnerp"></div>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary CloseTrabajosParcialesModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function(){
        let ModalFather=null;
        let ThisModal=$('#TrabajosParcialesModal');
        let ThisOrden=null;
        let elements=[];
        window.OpenTrabajosParcialesModal = function(id){
            ModalFather = $('.modal.show');
            if(ModalFather){
                ModalFather.modal('hide');
            }
            ThisOrden = id;
            searchdata()
            ThisModal.modal('show');
        }
        $(".CloseTrabajosParcialesModal").on('click',closethismodal)
        function closethismodal(){
            ThisModal.modal('hide');
            ThisPresupuesto=null
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
        function searchdata() {
            document.getElementById('ListaCargaTrabajosParciales').removeAttribute('hidden');
            document.getElementById('ListaTrabajosParciales').setAttribute('hidden', true);
            $.ajax({
                type: 'GET',
                url: '{{ route('DetallesGenerales.TrabajoParcial.Read') }}',
                data:{
                    DetGenId:ThisOrden
                },
                success: function(response) {
                    elements = response.elements;
                    showElements();
                    document.getElementById('ListaCargaTrabajosParciales').setAttribute('hidden', true);
                    document.getElementById('ListaTrabajosParciales').removeAttribute('hidden');
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
        function showElements(){
            $('#TablaTrabajosParciales tbody').empty();
            if (elements.length === 0) {
                $('#TrabajosParcialesMessage').removeAttr('hidden');
                $('#TablaTrabajosParciales').attr('hidden', true);
                return;
            } else {
                $('#TrabajosParcialesMessage').attr('hidden', true);
                $('#TablaTrabajosParciales').removeAttr('hidden');
            }
            $.each(elements, function(index, element) {
                let row = $('<tr>'); 
                row.append('<td><div class="Datatable-content">'+ element.descripcion+ '</div></td>');
                row.append('<td><div class="Datatable-content">'+ element.fecha  + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ element.horas  + '</div></td>');
                row.append('<td><div class="Datatable-content">'+ element.user  + '</div></td>');
                row.append(`<div class="zdflex"><button type="button"class="opcionesdesplegables btn  btn-primary ">Opciones</button>
                        <ul class="detallesdesplegables zdw-r6 " hidden>
                            <li><a href="#" onclick="DeleteTrabajoParcial(`+element.id+`)" ">Eliminar</a></li>
                            <li><a href="#" onclick="UpdateTrabajoParcial(`+element.id+`,'`+element.descripcion+`')">Editar</a></li>
                        </ul> </div>`
                );
                $('#TablaTrabajosParciales tbody').append(row);

            });
        }
        window.DeleteTrabajoParcial = (id) => { // Tu código aquí };
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Una vez eliminado, No lo podras revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('DetallesGenerales.TrabajoParcial.Delete')}}",
                        type: "DELETE",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:id,
                        },
                        success: function(response) {
                            const mensaje=response.message
                            Swal.fire({ html: `${mensaje}`, icon: 'success',showConfirmButton: false,timer: 2000,});
                            searchdata()
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            console.log(xhr)
                            Swal.fire({
                                title: 'Error',
                                html: `${errorMessage} ${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.error}`:``}`,
                                icon: 'error'
                                });

                        }
                    });
                }
            });
        };
        window.CreateTrabajoParcial = async () =>{
            ThisModal.modal('hide');
            const { value: values } = await Swal.fire({
                title: "Nuevo Trabajo Parcial",
                html: `
                    <div>
                        <label>Descripcion</label>
                        <textarea id="Descripcion" name="Descripcion" class='form-control'></textarea>
                        <label>Horas Trabajadas</label>
                        <input type="number" id="HorasTrabajadas" name="HorasTrabajadas" step=1 class='form-control' placeholder="Ingrese las horas trabajadas">
                    </div>
                `,
                focusConfirm: false,
                confirmButtonText: "Confirmar",
                preConfirm: () => {
                    const Descripcion = document.getElementById("Descripcion").value;
                    const HorasTrabajadas = document.getElementById("HorasTrabajadas").value;

                    if (!Descripcion || !HorasTrabajadas) {
                        Swal.showValidationMessage("La Descripcion y las Horas Trabajadas  Son Obligatorias");
                        return false;
                    }
                    if( Number(HorasTrabajadas) <= 0) {
                        Swal.showValidationMessage("Las Horas Trabajadas deben ser mayores a 0");
                        return false;
                    }

                    return [Descripcion,HorasTrabajadas];
                }
            });
            ThisModal.modal('show');
            if (Descripcion) {
                $.ajax({
                    url: '{{ route('DetallesGenerales.TrabajoParcial.Create') }}', // Cambia esto por la URL del endpoint en tu backend
                    method: 'POST',
                    data: {
                        DetGenId: ThisOrden,
                        descripcion:values[0],
                        horas:values[1],
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $message=response.message ?? "Creado Exitosamente"
                        Swal.fire({
                            icon: "success",
                            title: $message,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        searchdata();
                    },
                    error: function (error) {
                        Swal.fire({
                            icon:'error',
                            title:'Ocurrio un problema al crear El Trabajo Parcial. Intentelo en unos minutos',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                });
            }
        }
        window.UpdateTrabajoParcial = async (id,descripcionprevia) =>{
            const idtrabajo = id;
            ThisModal.modal('hide');
            const { value: Descripcion } = await Swal.fire({
                title: "Editar Trabajo Parcial",
                html: `
                    <div>
                        <label>Descripcion</label>
                        <textarea id="Descripcion" name="Descripcion" class='form-control'>${descripcionprevia}</textarea>
                    </div>
                `,
                focusConfirm: false,
                confirmButtonText: "Confirmar",
                preConfirm: () => {
                    const Descripcion = document.getElementById("Descripcion").value;

                    if (!Descripcion) {
                        Swal.showValidationMessage("La Descripcion Es Obligatoria");
                        return false;
                    }

                    return Descripcion;
                }
            });
            ThisModal.modal('show');
            if (Descripcion) {
                $.ajax({
                    url: '{{ route('DetallesGenerales.TrabajoParcial.Update') }}', // Cambia esto por la URL del endpoint en tu backend
                    method: 'POST',
                    data: {
                        id: idtrabajo,
                        descripcion:Descripcion,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $message=response.message ?? "Actualizado Exitosamente"
                        Swal.fire({
                            icon: "success",
                            title: $message,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        searchdata();
                    },
                    error: function (error) {
                        Swal.fire({
                            icon:'error',
                            title:'Ocurrio un problema al crear El Trabajo Parcial. Intentelo en unos minutos',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                });
            }
        }
        
    });
</script>
@endpush
