$(function(){
    function CloseAndOpen(hide,show){
        hide.modal('hide')
        show.modal('show')
    }
    window.OpenModal2Children=function(SecondModal){
        Modal2Father = $('.modal.show');
        Modal2Children = SecondModal;
        CloseAndOpen( Modal2Father,Modal2Children);
    }
    window.OpenModal2GranChild=function(SecondModal){
        Modal2Granchild = SecondModal;
        CloseAndOpen( Modal2Children,Modal2Granchild);
    }
    window.OpenModal2GreatGranChild=function(SecondModal){
        Modal2GreatGranchild = SecondModal;
        CloseAndOpen( Modal2Granchild,Modal2GreatGranchild);
    }
    window.OpenModal2GreatGreatGranChild=function(SecondModal){
        Modal2GreatGreatGranchild = SecondModal;
        CloseAndOpen( Modal2GreatGranchild,Modal2GreatGreatGranchild);
    }
    $(document).on('click', '.Close2Children', function() {
        $('.Close2Children').removeClass('Close2Children');
        CloseAndOpen( Modal2Children,Modal2Father);
    });
    $(document).on('click', '.Close2Granchild', function() {
        $('.Close2Granchild').removeClass('Close2Granchild');
        CloseAndOpen( Modal2Granchild,Modal2Children);
    });
    $(document).on('click', '.Close2GreatGranchild', function() {
        $('.Close2GreatGranchild').removeClass('Close2GreatGranchild');
        CloseAndOpen( Modal2GreatGranchild,Modal2Granchild);
    });
    $(document).on('click', '.Close2GreatGreatGranchild', function() {
        $('.Close2GreatGreatGranchild').removeClass('Close2GreatGreatGranchild');
        CloseAndOpen( Modal2GreatGreatGranchild,Modal2GreatGranchild);
    });
})