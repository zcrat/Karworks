<div class="modal fade" id="InspeccionVehicularModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="IVMtitle"></h5>
                <button type="button" class="btn-close IVMclose"></button>
            </div>
            <div class="modal-body">
                <div class="zdflex zdfw-w zdgap2">
                        <div id='IVRevLucEsp' class="border_div zdgap2 zdw-100pct"></div>
                        <div id='IVLiquidosDiv' class="border_div zdgrid zdgcl5 zdgap2 zdw-100pct"></div>
                        <div id='IVllantasDiv' class=" border_div zdgrid zdgcl2 zdgap2 zdw-100pct"></div>
                        <div id='IVManguerasDiv' class="border_div zdgap2 zdw-100pct"></div>
                        <div id='IVBandasDiv' class="border_div zdgap2 zdw-100pct"></div>
                        <div id='IVFiltrosDiv' class="border_div zdgap2 zdw-100pct"></div>
                        <div id='IVSeguridad' class="border_div zdgap2 zdw-100pct"></div>
                </div>
                
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary IVMclose">Cerrar</button>
                <button type="button" class="btn btn-primary" id="SaveIV">Guardar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        
        const divllantas=$('#IVllantasDiv');
        const divRevLucEsp=$('#IVRevLucEsp');
        const divMangueras=$('#IVManguerasDiv');
        const divliquidos=$('#IVLiquidosDiv');
        const divbandas=$('#IVBandasDiv');
        const divfiltros=$('#IVFiltrosDiv');
        const divseguridad=$('#IVSeguridad');
        const llantas={
            EsDelIzq:null,
            PreDelIzq:'',
            EsTraIzq:null,
            PreTraIzq:'',
            EsDelDer:null,
            PreDelDer:'',
            EsTraDer:null,
            PreTraDer:'',
            EsRef:null,
            PreRef:'',
            aliniacion:null
        }
        const RevLucEsp={
            Codigo:null,
            notas:''
        }
        const Mangueras={
            refrigerante:null,
            direccion:null,
            calefaccion:null
        }
        const Liquidos={
            motor:null,
            trasmision:null,
            diferencial:null,
            refrigerante:null,
            frenos:null,
            direccion:null,
            parabrisas:null,
            notas:'',
            OKmotor:false,
            OKtrasmision:false,
            OKdiferencial:false,
            OKrefrigerante:false,
            OKfrenos:false,
            OKdireccion:false,
            OKparabrisas:false,
            LLenomotor:false,
            LLenotrasmision:false,
            LLenodiferencial:false,
            LLenorefrigerante:false,
            LLenofrenos:false,
            LLenodireccion:false,
            LLenoparabrisas:false,
        }
        const Bandas={
            accesorios:null,
            direccion:null,
            aire:null
        }
        const Filtros={
            aire:null,
            combustible:null,
            aceite:null,
            notas:''
        }
        const Seguridad={
            freno:null,
            parabrisasdel:null,
            parabrisastra:null,
            notas:''
        }
        $(function(){ 
             $(".IVMclose").on('click',function(){
                closethismodal()
            })
             $("#SaveIV").on('click',function(){
                const datos={
                    llantas:llantas,
                    revicionluces:RevLucEsp,
                    mangueras:Mangueras,
                    liquidos:Liquidos,
                    bandas:Bandas,
                    filtros:Filtros,
                    seguridad:Seguridad
                }
                console.log(datos)
            })
            function closethismodal(){
                $('#InspeccionVehicularModel').modal('hide')
            }
            window.OpenInspenccionVehicular=function(){
                llenarllantas();
                llenarrevisionluces();
                llenarliquidos();
                llenarbandas();
                llenarfiltros();
                llenarmangueras();
                llenarseguridad();
                $('#InspeccionVehicularModel').modal('show');
            }
            function llenarllantas(){
                divllantas.empty();
                divllantas.append('<label class="title_inspeccion_tecnica zd_col_span2">LLantas</label>')
                divllantas.append('<h6>Patron De Desgaste</h6><h6 class="zdtext-left">Presion</h6>')
                
                divllantas.append(button(llantas.EsDelIzq,'I. Delantera','llanta','EsDelIzq')+`<div class="zdflex zdjc-end"><input type='number' class='presion' data-key="PreDelIzq" value="${llantas.PreDelIzq}"></input></div>`);
                divllantas.append(button(llantas.EsTraIzq,'I. Trasesa','llanta','EsTraIzq')+`<div class="zdflex zdjc-end"><input type='number' class='presion' data-key="PreTraIzq" value="${llantas.PreTraIzq}"></input></div>`);
                divllantas.append(button(llantas.EsDelDer,'D. Delantera','llanta','EsDelDer')+`<div class="zdflex zdjc-end"><input type='number' class='presion' data-key="PreDelDer" value="${llantas.PreDelDer}"></input></div>`);
                divllantas.append(button(llantas.EsTraDer,'D. Trasera','llanta','EsTraDer')+`<div class="zdflex zdjc-end"><input type='number' class='presion' data-key="PreTraDer" value="${llantas.PreTraDer}"></input></div>`);
                divllantas.append(button(llantas.EsRef,'Refaccion','llanta','EsRef')+`<div class="zdflex zdjc-end"><input type='number' class='presion' data-key="PreRef" value="${llantas.PreRef}"></input></div>`);
                
                divllantas.append('<h5 class="zd_col_span2">EL DESGASTE DE NEUMATICO INDICA QUE:</h5>')
                divllantas.append(button(llantas.aliniacion,'Se Necesita Alineacion Y Balanceo','llanta','aliniacion','zd_col_span2')+'<div></div>')
            }
            function llenarmangueras(){
                divMangueras.empty();
                divMangueras.append('<label class="title_inspeccion_tecnica">Mangueras</h4>')
                divMangueras.append(button(Mangueras.refrigerante,'Refrigerante','mangueras','refrigerante'));
                divMangueras.append(button(Mangueras.direccion,'Direccion','mangueras','direccion'));
                divMangueras.append(button(Mangueras.calefaccion,'Calefaccion','mangueras','calefaccion'));
            }
            function llenarbandas(){
                divbandas.empty();
                divbandas.append('<label class="title_inspeccion_tecnica">Bandas</h4>')
                divbandas.append(button(Bandas.accesorios,'Accesorios','bandas','accesorios'));
                divbandas.append(button(Bandas.direccion,'Direccion Hidraulica','bandas','direccion'));
                divbandas.append(button(Bandas.aire,'Alternados/A. Acondicionado','bandas','aire'));
                
            }
            function llenarfiltros(){
                divfiltros.empty();
                divfiltros.append('<label class="title_inspeccion_tecnica">Filtros</h4>')
                divfiltros.append(button(Filtros.aire,'Aire','Filtros','aire'));
                divfiltros.append(button(Filtros.combustible,'Combustible','Filtros','combustible'));
                divfiltros.append(button(Filtros.aceite,'Aceite','Filtros','aceite'));
                divfiltros.append(`<div><label>Notas</label></div><textarea class="form-control" id="notasfiltros">${Filtros.notas}</textarea>`)
            }
            function llenarseguridad(){
                divseguridad.empty();
                divseguridad.append('<label class="title_inspeccion_tecnica">Seguridad</h4>')
                divseguridad.append(button(Seguridad.freno,'Freno de Seguridad','Seguridad','freno','zdgcl2'));
                divseguridad.append('<h5 class="zd_col_span2">LIMPIAPARABRISAS</h5>')
                divseguridad.append( `<div class="zdgrid zdgcl2 zdgap2">`+button(Seguridad.parabrisasdel,'Izq / Der','Seguridad','parabrisasdel','')+button(Seguridad.parabrisastra,'Trasero','Seguridad','parabrisastra','')+`</div>`);
                divseguridad.append(`<div><label>Notas</label></div><textarea class="form-control" id="notasseguridad">${Seguridad.notas}</textarea>`)
            }
            function llenarrevisionluces(){
                divRevLucEsp.empty();
                divRevLucEsp.append('<label class="title_inspeccion_tecnica">Revision De Luces Espias</h4>')
                divRevLucEsp.append(button(RevLucEsp.Codigo,'Codigo(s)','RevLucEsp','Codigo'));
                divRevLucEsp.append(`<div><label>Notas</label></div><textarea class="form-control" id="notaslucesp">${RevLucEsp.notas}</textarea>`)
            }
            function llenarliquidos(){
                divliquidos.empty();
                divliquidos.append('<label class="title_inspeccion_tecnica zd_col_span5">Liquidos</label>')
                divliquidos.append('<h6 class="zd_col_span3">Condicion</h6><h6 class="">OK</h6><h6 class="">LLeno</h6>')
                divliquidos.append(button(Liquidos.motor,'Aceite de Motor','liquidos','motor','zd_col_span3')+Checkbox(Liquidos.OKmotor,'liquidostoggle','OKmotor')+Checkbox(Liquidos.LLenomotor,'liquidostoggle','LLenomotor'));
                divliquidos.append(button(Liquidos.trasmision,'Trasmision','liquidos','trasmision','zd_col_span3')+Checkbox(Liquidos.OKtrasmision,'liquidostoggle','OKtrasmision')+Checkbox(Liquidos.LLenotrasmision,'liquidostoggle','LLenotrasmision'));
                divliquidos.append(button(Liquidos.diferencial,'Diferencial','liquidos','diferencial','zd_col_span3')+Checkbox(Liquidos.OKdiferencial,'liquidostoggle','OKdiferencial')+Checkbox(Liquidos.LLenodiferencial,'liquidostoggle','LLenodiferencial'));
                divliquidos.append(button(Liquidos.refrigerante,'Refrigerante','liquidos','refrigerante','zd_col_span3')+Checkbox(Liquidos.OKrefrigerante,'liquidostoggle','OKrefrigerante')+Checkbox(Liquidos.LLenorefrigerante,'liquidostoggle','LLenorefrigerante'));
                divliquidos.append(button(Liquidos.frenos,'Frenos','liquidos','frenos','zd_col_span3')+Checkbox(Liquidos.OKfrenos,'liquidostoggle','OKfrenos')+Checkbox(Liquidos.LLenofrenos,'liquidostoggle','LLenofrenos'));
                divliquidos.append(button(Liquidos.direccion,'Direccion Hidraulica','liquidos','direccion','zd_col_span3')+Checkbox(Liquidos.OKdireccion,'liquidostoggle','OKdireccion')+Checkbox(Liquidos.LLenodireccion,'liquidostoggle','LLenodireccion'));
                divliquidos.append(button(Liquidos.parabrisas,'LimpiaParabrisas','liquidos','parabrisas','zd_col_span3')+Checkbox(Liquidos.OKparabrisas,'liquidostoggle','OKparabrisas')+Checkbox(Liquidos.LLenoparabrisas,'liquidostoggle','LLenoparabrisas'));
                divliquidos.append(`<label>Notas</label><textarea class="zd_col_span4 form-control" id="notasliquidos">${Liquidos.notas}</textarea>`)
            }
            function button(estado,label,clase,key,clasesdiv){

                return `<div class='zdflex zdw-100pct zdgap2 ${clasesdiv}'>
                    <button class='btn_circulo_red ${clase}' data-key=${key} data-estado='1'>`+(estado==1?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                    <button class='btn_triangulo ${clase}' data-key=${key} data-estado='2'>`+(estado==2?'<img src="/storage/triangulobutton.png"/><span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':`<img src="/storage/triangulobutton.png"/>`)+`</button>
                    <button class='btn_circulo_verde ${clase}' data-key=${key} data-estado='3'>`+(estado==3?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                    <label>`+label+`</label>
                    </div>
                    `
            }
            function Checkbox(estado,clase,key){
                const checked = estado===true ? 'checked' : '';

                return ` <input type="checkbox" class="validation ${clase}" data-key="${key}" data-estado="${estado}" ${checked}/>`
            }


            $(document).on('input', '.presion', function() {
                const presion = $(this).val();
                const key = $(this).attr('data-key');
                llantas[key] = presion;
            });
            $(document).on('change', '#notaslucesp', function() {
                const notas = $(this).val();
                RevLucEsp.notas = notas;
            });
            $(document).on('change', '#notasliquidos', function() {
                const notas = $(this).val();
                Liquidos.notas = notas;
            });
            $(document).on('change', '#notasfiltros', function() {
                const notas = $(this).val();
                Filtros.notas = notas;
            });
            $(document).on('change', '#notasseguridad', function() {
                const notas = $(this).val();
                Seguridad.notas = notas;
            });
            $(document).on('click', '.Seguridad', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                Seguridad[key] = estado;
                llenarseguridad();
            });
            $(document).on('click', '.Filtros', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                Filtros[key] = estado;
                llenarfiltros();
            });
            $(document).on('click', '.bandas', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                Bandas[key] = estado;
                llenarbandas();
            });
            $(document).on('click', '.RevLucEsp', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                RevLucEsp[key] = estado;
                llenarrevisionluces();
            });
            $(document).on('click', '.mangueras', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                Mangueras[key] = estado;
                llenarmangueras();
            });
            $(document).on('click', '.llanta', function() {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                llantas[key] = estado;
                llenarllantas();
            });
            $(document).on('click', '.liquidos', function(e) {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado');
                Liquidos[key] = estado;
                llenarliquidos();

            });
            $(document).on('click', '.liquidostoggle', function(e) {
                const key = $(this).attr('data-key');
                const estado = $(this).attr('data-estado') === 'true';
                console.log(estado)
                Liquidos[key] = !estado;
                llenarliquidos();

            });

        })
    </script>
@endpush