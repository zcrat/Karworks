<!-- Modal -->
<div class="modal fade" id="ViewValeAlmacenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  >
    <div class="modal-dialog zdmw-95pct modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ValAlmTittle">Vale De Almacen</h5>
                <button type="button" class="btn-close ViewValAlmClose" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ValAlmDivPdf" class="zdminw-50vw">
                    <iframe src="" frameborder="0" id='VisorValepdfView' class='Visorpdf'></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ViewValAlmClose" >Cerrar</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        $(".ViewValAlmClose").on('click',function(){
            closethismodal()
        })
        function closethismodal(){
            $('#ViewValeAlmacenModal').modal('hide');
        }
        window.OpenViewValeAlmacenModal=async function(id){
            const pdfUrl = "/Zcrat/Vales/Almacen/pdf/" + id + "#FitPage";
            const visor = $('#VisorValepdfView');
            visor.attr('src', pdfUrl);
            $('#ViewValeAlmacenModal').modal('show');
        };
    });
</script>
@endpush