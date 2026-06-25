<div class="modal fade" id="PagosPresupuestoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="pagostittle">Pagos Presupuestos</h5>
                <button type="button" class="btn-close closepagos"></button>
            </div>
            <div id="listapagos" hidden>
                <table id="tablapagospresupuesto" class="table table-sm  table-striped">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Nombre</th>
                            <th>Importe</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id='listacargapagos' class="carga" >
                <h3 class="text-center m-2">Cargando Datos</h3>
                <div class="spinnerp"></div>
            </div>
            <div  class="no-results-messagepagos" hidden>
                <span id="no-results-messagepagos"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closepagos">Cerrar</button>
                <button type="button" class="btn btn-primary" id="nuevopago">Nuevo</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(function(){
            let elements=[];
            let presupuesto=0;
            $(".closepagos").on('click',regresarmodalsyc);

            function regresarmodalsyc(){
                $("#PagosPresupuestoModal").modal('hide')
            }
            $(document).on('click', '.presupuestopagado', async function () {
                let thisinput = $(this);
                console.log('se esta abriendo');
                presupuesto = thisinput.attr('data-id');
                searchdata() 
                
            });
            function searchdata() {
                document.getElementById('listacargapagos').removeAttribute('hidden');
                document.getElementById('listapagos').setAttribute('hidden', true);
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.presupuesto.get.pagos') }}',
                    data:{
                        presupuesto
                    },
                    success: function(response) {
                        elements = response.elements;
                        document.getElementById('listacargapagos').setAttribute('hidden', true);
                        document.getElementById('listapagos').removeAttribute('hidden');
                        showElements();
                        $("#PagosPresupuestoModal").modal('show')
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            }
            function showElements() {
                $('#tablapagospresupuesto tbody').empty();
                if (elements.length === 0) {
                    document.querySelector('.no-results-messagepagos').removeAttribute('hidden');
                    $('#no-results-messagepagos').text('No Se Encontraron Pagos');
                    document.getElementById('listapagos').setAttribute('hidden', true);
                } else {
                    document.querySelector('.no-results-messagepagos').setAttribute('hidden', true);
                    document.getElementById('listapagos').removeAttribute('hidden');
                    $('#no-results-messagepagos').text('');
                    $.each(elements, function(index, element) {
                        let row = $('<tr>'); 
                        row.append('<td><div class="Datatable-content">'+ (element.descripcion ? element.descripcion : "0" ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">'+ (element.nombre ? element.nombre : "0" ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">'+ (element.importe ? element.importe : "Sin importe" ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">'+ (element.fecha ? element.fecha : "Sin fecha" ) + '</div></td>');
                        row.append('<td><div class="Datatable-content">'+
                            '<button type="button" class="btn btn-danger" onclick="executeeliminarpago('+element.id+')"><i class="fa-solid fa-trash"></i></button>'+
                            '<button type="button" class="btn btn-success" onclick="executesubirarchivo('+element.presupuesto_id+',10)"><i class="fa fa-picture-o"></i></button>'+
                            '</div></div></td>')
                        $('#tablapagospresupuesto tbody').append(row);
                    });
                }
            }
            $(document).on('click', '.presupuestopagar', async function () {
                let thisinput = $(this);
                presupuesto = thisinput.attr('data-id');

                async function mostrarFormulario() {
                    const { value: formValues } = await Swal.fire({
                        title: "Pagar Presupuesto",
                        html: `
                            <div>
                                <label>Fecha</label>
                                <input id="Fecha" name="Fecha" type="datetime-local" class='form-control'>
                            </div>
                            <div>
                                <label>Importe Pagado<strong>*</strong></label>
                                <input id="Importe" name="Importe" type="number" class='form-control'>
                            </div>
                            <div>
                                <label>Descripcion<strong>*</strong></label>
                                <input id="descripcion" name="descripcion" type="text" class='form-control'>
                            </div>
                            <div>
                                <label>Nombre<strong>*</strong></label>
                                <input id="nombre" name="nombre" type="text" class='form-control'>
                            </div>
                        `,
                        focusConfirm: false,
                        confirmButtonText: "Confirmar",
                        preConfirm: () => {
                            const fecha = document.getElementById("Fecha").value;
                            const importe = document.getElementById("Importe").value;
                            const descripcion = document.getElementById("descripcion").value;
                            const nombre = document.getElementById("nombre").value;

                            if (!importe) {
                                Swal.showValidationMessage("El Importe Es Obligatorio");
                                return false;
                            }

                            return [fecha, importe,descripcion,nombre];
                        }
                    });

                    if (formValues) {
                        $.ajax({
                            url: "{{route('2025.Presupuestos.Update.Pago')}}",
                            type: "get",
                            data: {
                                id: presupuesto,
                                Importe: formValues[1],
                                Fecha: formValues[0],
                                descripcion: formValues[2],
                                nombre: formValues[3],
                            },
                            success: function (response) {
                                Swal.fire({ title: 'Éxito', html: `${response.message ?? 'Pagado Exitosamente'}`, icon: 'success' });
                                thisinput.removeClass('btn-danger presupuestopagar').addClass('btn-success presupuestopagado');
                            },
                            error: function (xhr, status, errors) {
                                if (xhr.status === 422) {
                                    let message = 'Errores de validación:<br>';
                                    let errorMessages = Object.values(xhr.responseJSON.errors || {})
                                        .map((msgs) => msgs.join("<br>"))
                                        .filter(Boolean)
                                        .join("<br>");

                                    Swal.fire({
                                        title: 'Error de Validación',
                                        html: `${message}<br>Detalles del error:<br> ${errorMessages}`,
                                        icon: 'error'
                                    }).then(() => {
                                        mostrarFormulario(); // Volver a mostrar el formulario
                                    });
                                } else {
                                    Swal.fire({ title: 'Error', html: `Contacte A Soporte`, icon: 'error' });
                                }
                            }
                        });
                    }
                }

                mostrarFormulario();
            });
            $('#nuevopago').on('click',async function () {
                
                $("#PagosPresupuestoModal").modal('hide')
                async function mostrarFormulario() {
                    const { value: formValues } = await Swal.fire({
                        title: "Pagar Presupuesto",
                        html: `
                            <div>
                                <label>Fecha</label>
                                <input id="Fecha" name="Fecha" type="datetime-local" class='form-control'>
                            </div>
                            <div>
                                <label>Importe Pagado<strong>*</strong></label>
                                <input id="Importe" name="Importe" type="number" class='form-control'>
                            </div>
                            <div>
                                <label>Descripcion<strong>*</strong></label>
                                <input id="descripcion" name="descripcion" type="text" class='form-control'>
                            </div>
                            <div>
                                <label>Nombre<strong>*</strong></label>
                                <input id="nombre" name="nombre" type="text" class='form-control'>
                            </div>
                        `,
                        focusConfirm: false,
                        confirmButtonText: "Confirmar",
                        preConfirm: () => {
                            const fecha = document.getElementById("Fecha").value;
                            const importe = document.getElementById("Importe").value;
                            const descripcion = document.getElementById("descripcion").value;
                            const nombre = document.getElementById("nombre").value;

                            if (!importe) {
                                Swal.showValidationMessage("El Importe Es Obligatorio");
                                return false;
                            }

                            return [fecha, importe,descripcion,nombre];
                        }
                    });

                    if (formValues) {
                        $.ajax({
                            url: "{{route('2025.Presupuestos.Update.Pago')}}",
                            type: "get",
                            data: {
                                id: presupuesto,
                                Importe: formValues[1],
                                Fecha: formValues[0],
                                descripcion: formValues[2],
                                nombre: formValues[3],
                            },
                            success: function (response) {
                                Swal.fire({ title: 'Éxito', html: `${response.message ?? 'Pagado Exitosamente'}`, icon: 'success' });
                                thisinput.removeClass('btn-danger presupuestopagar').addClass('btn-success presupuestopagado').attr('data-fecha',response.fecha).attr('data-importe',response.importe);
                                
                            },
                            error: function (xhr, status, errors) {
                                if (xhr.status === 422) {
                                    let message = 'Errores de validación:<br>';
                                    let errorMessages = Object.values(xhr.responseJSON.errors || {})
                                        .map((msgs) => msgs.join("<br>"))
                                        .filter(Boolean)
                                        .join("<br>");

                                    Swal.fire({
                                        title: 'Error de Validación',
                                        html: `${message}<br>Detalles del error:<br> ${errorMessages}`,
                                        icon: 'error'
                                    }).then(() => {
                                        mostrarFormulario(); // Volver a mostrar el formulario
                                    });
                                } else {
                                    Swal.fire({ title: 'Error', html: `Contacte A Soporte`, icon: 'error' });
                                }
                            }
                        });
                    }
                }

                mostrarFormulario();
            });
            window.executeeliminarpago = (id)=>{
                Swal.fire({
                title: '¿Estás seguro?',
                text: "Una vez eliminado, no podrás recuperar este pago.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{route('2025.presupustos.delete.pago')}}",
                            type: "DELETE",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                id:id,
                            },
                            success: function(response) {
                                Swal.fire('Éxito', 'El Concepto Fue Eliminado Correctamente', 'success');
                                regresarmodalsyc()
                                try{
                                    executeSearchdata();
                                }catch(e){
                                    console.log('no existe la funcion')
                                }
                            },
                            error: function(xhr, status, error) {
                            if(xhr.status===499){
                                Swal.fire({ title: 'Error', html: `Detalles del error:<br>${xhr.responseJSON.error}`, icon: 'error'});
                            }else{
                                let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                                Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                            }
                            }
                        });
                    } 
                });
            } 
        })
    </script>
@endpush
