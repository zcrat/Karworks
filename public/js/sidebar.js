
$(function() {
    let s1 = $('.msidebar');
    let s2 = $('.msidebar-links');
    let s3 = $('.body-container');
    $('#sider , .contraer').on('click', function() {
        s1.toggleClass('active-sidebar');
        s2.toggleClass('active-msidebar-links');
        s3.toggleClass('active-body-container');
    });
    $('.vista').on('click', function(){
        let barraId = $(this).attr('data-id') || 'none';
        localStorage.setItem('barra', barraId);
        console.log("wqee".barraId)
        cambiarbarra();
        });
        function cambiarbarra(){
          $('.subbarra').attr('hidden',true);
          if(localStorage.getItem('barra')!= 'none'){
          $('#'+ localStorage.getItem('barra')).removeAttr('hidden');
          }
        }
});
