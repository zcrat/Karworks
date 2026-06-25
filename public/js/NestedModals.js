$(function(){
    function CloseAndOpen(hide,show){
        hide.modal('hide')
        show.modal('show')
    }
    window.OpenModalChildren=function(SecondModal){
        ModalFather = $('.modal.show');
        ModalChildren = SecondModal;
        CloseAndOpen( ModalFather,ModalChildren);
    }
    window.OpenModalGranChild=function(SecondModal){
        ModalGranchild = SecondModal;
        CloseAndOpen( ModalChildren,ModalGranchild);
    }
    // Delegar el evento clic a elementos con la clase 'nueva-clase'
    $(document).on('click', '.CloseGranchild', function() {
        $('.CloseGranchild').removeClass('CloseGranchild');
        CloseAndOpen( ModalGranchild,ModalChildren);
    });
    $(document).on('click', '.CloseChildren', function() {
        $('.CloseChildren').removeClass('CloseChildren');
        CloseAndOpen( ModalChildren,ModalFather);
    });
})