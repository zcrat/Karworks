<div class="modal fade" id="CompararPresupuestosModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Cabecera del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="CompararPresupuestosTitle">Presupuestos</h5>
                <button type="button" class="btn-close " data-bs-dismiss="modal"></button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="modal-body Pruebavisor">
                <div class="Visorderecha2 previewfiles2 ">
                    <h4>Presupuesto Externo</h4>
                    <div class="zdmg-r02 zdflex zdscroll-y zdw-100pct zdmw-45vw" id='presupuestosexternosfiles' hidden></div>
                    <iframe id="PresupuestoExternoIframe"  class='Visorpdf2'  hidden></iframe>
                    <img id="PresupuestoExternoImg"  hidden></img>
                    <video id="PresupuestoExternoVideo" hidden controls> 
                        <source id="PresupuestoExternoVideoSource" type="video/mp4"> Tu navegador No Soporta La Etiqueta de Video. 
                    </video>
                    <h5 id="PresupuestoExternoText" hidden>El Archivo Que Se Eligio No Se Puede Previzualisar</h5>
                </div>
                <div class="Visorderecha2">
                    <h4>Presupuesto Interno Venta</h4>
                    <iframe src="" frameborder="0" id='PresupuestoPdfVenta' class='Visorpdf2'></iframe>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function(){
        window.opencompararPresupuesto =  async function (Presupuesto) {
            let pdfUrl = "/Zcrat/Presupuestos/PDF/Venta/" + Presupuesto + "#view=FitPage";
            const visor = $('#PresupuestoPdfVenta');
            visor.removeAttr('src'); // Limpiar el visor antes de asignar un nuevo src
            setTimeout(() => {
                visor.attr('src', pdfUrl);
            }, 100);
            await $.ajax({
                url: "{{route('2025.Presupuestos.Get.Files')}}",
                type: "get",
                data:{
                presupuesto:Presupuesto,
                tipo:4
                },
                success: function(response) {
                cargarpresupuestosexternos(response.archivos,Presupuesto)
                },
                error: function(xhr) {
                    console.log(xhr);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map((msgs) => {
                            if (msgs && msgs !== "Este campo es obligatorio." && msgs !== "La opción no es válida") {
                                return msgs.join("<br>");
                            }
                        }).filter(Boolean).join("<br>");
                        Swal.fire({
                            icon: "warning",
                            title: "Información",
                            html: errorMessages,
                            timer: 10000
                        });
                    } else {
                        Swal.fire({
                            title: 'Error: ' + (xhr.status ?? "Desconocido"),
                            html: `Detalles del error:<br> ${xhr.responseJSON?.message ?? "Ocurrio Un Error Inesperado <br> Contacte a Soporte"}`,
                            icon: 'error'
                        });
                    }
                }
            })
            $('#CompararPresupuestosModel').modal('show');
        }
        function cargarpresupuestosexternos(files,presupuestoid){
            $("#presupuestosexternosfiles").empty();
            if (files.length > 0) {

                if(files.length > 1){
                    for (let i = 0; i < files.length; i++) {
                        let url = files[i]['ruta_completa'];
                        let id = files[i]['id'];
                        addfiletoformdataup(url,id);
                    }
                    $("#presupuestosexternosfiles").removeAttr('hidden');
                }else{
                    $("#presupuestosexternosfiles").attr('hidden',true);
                }
                const url = files[0]['ruta_completa'];
                const id = files[0]['id'];
                const ext = url.split('.').pop().toLowerCase();
                let tipo=null;
                if (['mp4', 'webm', 'mov'].includes(ext)) {
                    tipo='video'
                } else if (ext === 'pdf') {
                    tipo='pdf';
                } else if (['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'].includes(ext)) {
                    tipo = 'imagen';
                } 
                PreviewPresupuestosExterno(url,tipo)
            }else{
                let InputEmpty = ['PresupuestoExternoIframe', 'PresupuestoExternoImg', 'PresupuestoExternoVideoSource'];
                let InputHidden = ['PresupuestoExternoIframe', 'PresupuestoExternoImg', 'PresupuestoExternoVideo', 'PresupuestoExternoText'];

                InputEmpty.forEach(input => {
                    $("#" + input).removeAttr('src');
                });
                InputHidden.forEach(input => {
                    $("#" + input).attr('hidden', true);
                });
                $("#PresupuestoExternoText").text('No Se Han Subido Presupuestos Externos').removeAttr('hidden');
            }
        }
        function addfiletoformdataup(filePath, id){
            const fileURL = filePath;
            
            // Obtener extensión en minúsculas
            const ext = filePath.split('.').pop().toLowerCase();

            // Crear contenedor base
            let tipoArchivo = `<div class="zdflex zdjc-center zdfd-column image-container" data-index="file-${id}">`;

            // Evaluar tipo de archivo según extensión
            if (['mp4', 'webm', 'mov'].includes(ext)) {
                tipoArchivo += `
                <video class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
                    onclick="PreviewPresupuestosExterno('${fileURL}','video')"
                    title='Video-${id}' controls>
                    <source src="${fileURL}" type="video/${ext}">
                    Tu navegador no soporta la etiqueta de video.
                </video>`;
            } else if (ext === 'pdf') {
                tipoArchivo += `
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
                    onclick="PreviewPresupuestosExterno('${fileURL}','pdf')"
                    title='PDF-${id}'>
                    <img src="/storage/iconos/pdf1.png" class="zdw-100pct zdh-100pct">
                </button>`;
            } else if (ext === 'xml') {
                tipoArchivo += `
                <button type="button" onclick="PreviewPresupuestosExterno('nosoportado')" title='XML-${id}'>
                    <img src="/storage/iconos/XML.png" class="zdw-100pct zdh-100pct">
                </button>`;
            } else if (['xls', 'xlsx'].includes(ext)) {
                tipoArchivo += `
                <button type="button" onclick="PreviewPresupuestosExterno('nosoportado')" title='Excel-${id}'>
                    <img src="/storage/iconos/XLSX.png" class="zdw-100pct zdh-100pct">
                </button>`;
            } else if (['doc', 'docx'].includes(ext)) {
                tipoArchivo += `
                <button type="button" onclick="PreviewPresupuestosExterno('nosoportado')" title='DOC-${id}'>
                    <img src="/storage/iconos/DOC.png" class="zdw-100pct zdh-100pct">
                </button>`;
            } else if (['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'].includes(ext)) {
                tipoArchivo += `
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
                    onclick="PreviewPresupuestosExterno('${fileURL}','imagen')"
                    title='Foto-${id}'>
                    <img src="${fileURL}" class="zdw-100pct zdh-100pct">
                </button>`;
            } else {
                tipoArchivo += `
                <button type="button" class="boton-imagen zdmg-r02 zdw-r8 zdmnw-r8 zdh-r8"
                    onclick="PreviewPresupuestosExterno('nosoportado')" title='Archivo-${id}'>
                    <img src="/storage/iconos/desconocido.png" class="zdw-100pct zdh-100pct">
                </button>`;
            }

            // Botón eliminar actualizado
            tipoArchivo += `
                <button type="button" class="btn btn-success descargar-imagen"
                onclick="downloadone(${id})" title="Descargar">Descargar</button>
            </div>`;

            // Insertar en el contenedor
            $("#presupuestosexternosfiles").append(tipoArchivo);
        }
        window.PreviewPresupuestosExterno = function (archivo,tipo=null) {
            console.log(archivo,tipo);
            let InputEmpty = ['PresupuestoExternoIframe', 'PresupuestoExternoImg', 'PresupuestoExternoVideoSource'];
            let InputHidden = ['PresupuestoExternoIframe', 'PresupuestoExternoImg', 'PresupuestoExternoVideo', 'PresupuestoExternoText'];

            InputEmpty.forEach(input => {
                $("#" + input).removeAttr('src');
            });
            InputHidden.forEach(input => {
                $("#" + input).attr('hidden', true);
            });
            const archivoSanitizado = encodeURI(archivo);
            console.log(archivoSanitizado);
           if (tipo=='imagen') {
                $('#PresupuestoExternoImg').attr('src', archivoSanitizado.replace(/#/g, '%23')).removeAttr('hidden');
            } else if (tipo=='pdf') {
                $('#PresupuestoExternoIframe').attr('src', ""+archivoSanitizado.replace(/#/g, '%23')).removeAttr('hidden');
            } else if (tipo=='video') {
                $('#PresupuestoExternoVideoSource').attr('src', archivoSanitizado.replace(/#/g, '%23'));
                $('#PresupuestoExternoVideo')[0].load();
                $('#PresupuestoExternoVideo').removeAttr('hidden');
            } else {
                $('#PresupuestoExternoText').text('El Archivo Que Se Eligio No Se Puede Previzualisar').removeAttr('hidden');
            }
            $("#presupuestosexternosfiles").removeAttr('hidden') 
        }
    });
</script>
@endpush
