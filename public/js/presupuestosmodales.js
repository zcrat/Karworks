$(function(){
const modalrecepcion=$('#recepcionservicioyconceptos');
const modalempresas=$('#Empresa_modal');
const modalclientes=$('#usuarioStore');
const modalvehiculo=$('#newcarmodal');
const modalcolor=$('#newcolorcarmodal');
const modalmarca=$('#newmarcacarmodal');
const modalmodelo=$('#newmodelocarmodal');
const modalusertaller=$('#Newusertallermodal');
const DetallesGeneralesModal=$('#DetallesGeneralesModal');

    function modalprincipal(hide,show){
        hide.modal('hide')
        show.modal('show')
    }
    //se abren desde una modal
    $('#newempresas').on('click',function(){modalprincipal(modalrecepcion,modalempresas)});
    $('.closenewempresa').on('click', function(){modalprincipal(modalempresas,modalrecepcion)});

    $('#newcustomer').on('click',function(){modalprincipal(modalrecepcion,modalclientes) });
    $('.closenewcustomer').on('click', function(){ modalprincipal(modalclientes,modalrecepcion) });

    $('#newcar').on('click',function(){
        $('#newcarmodal input').not('input[name="_token"]').val('').trigger('change');
        $('#newcarmodal select').val('').trigger('change'); 
        $("#newcarmodal").find(".error-message").remove();
        $("#newcarmodal").find(".error-message").remove();
        console.log('click')
        modalprincipal(modalrecepcion,modalvehiculo)});
    $('#editcar').on('click',function(){
        let id = $(this).data('id')
        $.ajax({
            type: 'GET',
            url: '/Zcrat/Vehiculo/Get/Element',
            data:{
                id: id,
            },
            success: function(response) {
                element=response.element;
                $('#newcarmodal input').not('input[name="_token"]').val('').trigger('change');
                $("#newcarmodal").find(".error-message").remove();
                $('#anionewcar').val(element.anio); 
                $('#numeconomiconewcar').val(element.no_economico); 
                $('#vimnewcar').val(element.vim); 
                $('#placasnewcar').val(element.placas); 
                $("#tiponewvehiculo").append('<option value="' + element.tipo.id + '">' + element.tipo.nombre + '</option>').attr('disabled',false);
                $("#marcanewvehiculo").append('<option value="' + element.marca.id + '">' + element.marca.nombre + '</option>').attr('disabled',false);
                $("#modelonewvehiculo").append('<option value="' + element.modelo.id + '">' + element.modelo.nombre +'</option>').attr('disabled',false);
                $("#colornewvehiculo").append('<option value="' + element.color.id + '">' + element.color.nombre + '</option>').attr('disabled',false);
                $("#idvehiculo").val(element.id);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        }); 

       
        modalprincipal(modalrecepcion,modalvehiculo)});
    $('.closenewcar').on('click', function(){modalprincipal(modalvehiculo,modalrecepcion) });

    $('.newusertaller').on('click',function(){
        let close=$(this).data('origen');
        $('#formusertaller input').not('input[name="_token"]').val('').trigger('change');
        $('#titleusertaller').text('Nuevo '+close);
        $('#tipousertaller').val(close);
        console.log('0ashjdkhdfk')
        modalprincipal(modalrecepcion,modalusertaller)});

    $('.closenewusertaller').on('click', function(){modalprincipal(modalusertaller,modalrecepcion) });

    $('#newcolorcar').on('click',function(){modalprincipal(modalvehiculo,modalcolor)});
    $('.closenewcolorcar').on('click', function(){modalprincipal(modalcolor,modalvehiculo)});

    $('#newmodelocar').on('click',function(){
        modalprincipal(modalvehiculo,modalmodelo)});
    $('.closemodelonewcar').on('click', function(){modalprincipal(modalmodelo,modalvehiculo)});
    
    //se abre de dos posibles modales
    $('.newmarcacar').on('click',function(){cerrarpadre(modalmarca);});
    $('.closemarcanewcar').on('click', function(){abrirpadre(modalmarca);});

    $('.OpenDetallesGenerales').on('click',function(){cerrarpadre(DetallesGeneralesModal);});
    $('.CloseDetallesGenerales').on('click', function(){abrirpadre(DetallesGeneralesModal);});

    function cerrarpadre(segundomodal){
     modalVisible = $('.modal.show');
     modalprincipal( modalVisible,segundomodal)
    }
    window.OpenModalChildren=function(segundomodal){
        ModalFather = $('.modal.show');
        ModalChildren = segundomodal;
        modalprincipal( ModalFather,ModalChildren);
    }
    window.CloseModalChildren=function(){
        modalprincipal( ModalChildren,ModalFather);
    }
    window.OpenModalGranChild=function(segundomodal){
        ModalGranchild = segundomodal;
        modalprincipal( ModalChildren,ModalGranchild);
    }
    window.CloseModalGranChild=function(){
        modalprincipal( ModalGranchild,ModalChildren);
    }
    function abrirpadre(segundomodal){
        modalprincipal(segundomodal,modalVisible)
    }


})