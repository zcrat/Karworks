<div class="modal fade" id="UserEditModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="titleUserEditModal">Nuevo User</h5>
                <button type="button" class="btn-close closeEditUser"></button>
            </div>
                <div class="modal-body">
                    <div>
                        <h2>Datos</h2>
                        <div class="form-group">
                            <label for="nombre_UserEdit">Nombre:<i class="ml-2 color-required fas fa-asterisk"></i></label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-user"></i></div>
                                <input id="nombre_UserEdit" class="form-control" type="text" name="nombre_UserEdit"
                                    placeholder="Ej. Alberto Esquivias Flores" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="usuario_UserEdit">Usuario:<i class="ml-2 color-required fas fa-asterisk"></i></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input id="usuario_UserEdit" class="form-control" type="text" name="usuario_UserEdit"
                                    placeholder="Ej. Pruebas" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_UserEdit">Contraseña:<i class="ml-2 color-required fas fa-asterisk"></i></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    *
                                </div>
                                <input id="password_UserEdit" class="form-control" type="password" name="password_UserEdit" required>
                            </div>
                        </div>
                        <button type="submit" id="saveUserEdit" class="btn btn-primary">Guardar</button>
                     </div>
                    <div id="rolesandpermisosdiv">
                        <h2>Roles y Permisos</h2>
                        <div>
                            <h4>Roles</h4>
                            <div id="roles" class="row row-cols-3 ">
                                
                            </div>
                        </div>
                        <div>
                            <h4>Permisos</h4>
                            <div id="Permisos" class="row row-cols-3 ">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeEditUser">Cerrar</button>
                </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
         $(function(){
            let ThisModal=$('#UserEditModal');
            let ModalFather=null;
            let IdUser=null;
            let Roles=[];
            let Permisos=[];
            let PermisosDirectos=[];
            let PermisosHeredados=[];
            let RolesUsuario=[];
            $(".closeEditUser").on('click',function(){
                closethismodal()
            })
            function closethismodal(){
                ThisModal.modal('hide');
                if(DisparadorOtroModal){
                    DisparadorOtroModal=null;
                }
            }
            window.OpenUserAdmin=async function(id=null){
                IdUser=id;
                $('#rolesandpermisosdiv').attr('hidden',true);
                $('#roles').empty();
                $('#Permisos').empty();
                if(IdUser){
                    $('#titleUserEditModal').text('Actualizar Usuario ');
                    GetPermisos(true);
                }else{
                    UpdateDatos();
                    $('#titleUserEditModal').text('Nuevo Usuario ');
                    ThisModal.modal('show');
                }
            };
            window.GetPermisos=function(restetinputs=false){
                $.ajax({
                    url: '{{ route('User.Get.Permisos') }}', // Cambia esto por la URL del endpoint en tu backend
                    method: 'get',
                    data: {
                        id:IdUser,
                    },
                    success: function (response) {
                        Roles = response.roles ?? [];
                        Permisos = response.permisos ?? [] ;
                        PermisosDirectos = response.permisosdirectos ?? [];       
                        PermisosHeredados = response.permisosheredados ?? [];       
                        RolesUsuario = response.rolesusuario ?? [];
                        UpdateRoles();
                        UpdatePermisos();
                        if(restetinputs){
                            UpdateDatos(response.datos??null)
                        }
                        $('#rolesandpermisosdiv').removeAttr('hidden',true);
                        ThisModal.modal('show');

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
            $(document).on('change', '.roltoggle', function() {
                const rol = $(this).val();
                 $.ajax({
                    url: "{{route('User.Toggle.Rol')}}",
                    type: "post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        rol:rol,
                        user:IdUser
                    },
                    success: function(response) {
                        Swal.fire({
                        title: 'Exito',
                        html: `Actualizado Correctamente`,
                        icon: 'success'
                        });
                        GetPermisos();
                    },
                    error: function(xhr, status, error) {
                        UpdateRoles();
                        Swal.fire({
                            title: 'Error',
                            html: `${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.message}`:``}`,
                            icon: 'error'
                            });
                    }
                });

            });

            $(document).on('change', '.permisotoggle', function() {
                const permiso = $(this).val();
                $.ajax({
                    url: "{{route('User.Toggle.Permiso')}}",
                    type: "post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        permiso:permiso,
                        user:IdUser
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Exito',
                            html: `Actualizado Correctamente`,
                            icon: 'success'
                            });
                        GetPermisos();
                    },
                    error: function(xhr, status, error) {
                        UpdatePermisos();
                        Swal.fire({
                            title: 'Error',
                            html: `${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.message}`:``}`,
                            icon: 'error'
                            });
                    }
                });

            });

            function UpdateRoles() {
                $('#roles').empty();
                $.each(Roles, function(index, rol) {
                    const active = RolesUsuario.includes(rol);
                    let row = $('<div>', {
                        class: 'zdflex  align-items-center gap-1'
                    });

                    row.append(`<input type="checkbox" class="zdw-r1 zdh-r1 zdmg-r04 roltoggle" ${active ? 'checked' : ''} value="${rol}">`);
                    row.append(`<h5>${rol}</h5>`);

                    $('#roles').append(row);
                });
            }

            function UpdateDatos(data={"name":'',"email":''}) {
                $('#nombre_UserEdit').val(data['name']);
                $('#usuario_UserEdit').val(data['email']);
                $('#password_UserEdit').val('');
            }

            function UpdatePermisos() {
                $('#Permisos').empty();
                $.each(Permisos, function(index, permiso) {
                    const active = PermisosDirectos.includes(permiso) || PermisosHeredados.includes(permiso);
                    const toggle = PermisosDirectos.includes(permiso);

                    let row = $('<div>', {
                        class: 'zdflex flex-wrap justify-content-center align-items-center gap-1'
                    });

                    // Siempre asignamos el value con el nombre del permiso
                    // y diferenciamos directos con un atributo extra
                    row.append(`<input type="checkbox" class="zdw-r1 zdh-r1 zdmg-r04 permisotoggle" ${active ? 'checked' : ''} value="${permiso}" data-directo="${toggle}">`);
                    row.append(`<h5>${permiso}</h5>`);

                    $('#Permisos').append(row);
                });
            }
            $('#saveUserEdit').on('click',function(){
                $.ajax({
                    url: "{{route('User.CreateOrUpdate')}}",
                    type: "post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id:IdUser,
                        name: $('#nombre_UserEdit').val(),
                        email:$('#usuario_UserEdit').val(),
                        password:$('#password_UserEdit').val(),
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Exito',
                            html: `Guardado Correctamente`,
                            icon: 'success'
                            });
                        OpenUserAdmin(response.id);
                    },
                    error: function(xhr, status, error) {
                        UpdatePermisos();
                        Swal.fire({
                            title: 'Error',
                            html: `${xhr.responseJSON ? `<br>Detalles del error:<br>${xhr.responseJSON.message}`:``}`,
                            icon: 'error'
                            });
                    }
                });
            })

         })
    </script>
@endpush
