
<div class="modal fade" id="InspeccionVehicularModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog zdmw-95pct modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="IVMtitle"></h5>
                <button type="button" class="btn-close IVMclose"></button>
            </div>
            <div class="modal-body">
                <div class="zdflex zdfw-w zdgap2" id="FormNewInpectionVehicular">
                    <div class="zdflex zdgap2 zdw-100pct">
                        <div class="zdflex zdfw-w zdgap2 zdw-50pct">
                            <div id='IVRevLucEsp' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                            <div id='IVLiquidosDiv' class="border_div zdgrid zdgcl5 zdgap2 zdw-100pct"></div>
                        </div>
                        <div class="zdflex zdfw-w zdgap2 zdw-50pct">
                            <div id='IVManguerasDiv' class="border_div zdgap2 zdw-100pct zdgrid" ></div>
                            <div id='IVBandasDiv' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                            <div id='IVFiltrosDiv' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                        </div>
                        </div>
                    <div class="zdflex zdgap2 zdw-100pct">
                        <div id='IVllantasDiv' class=" border_div zdgrid zdgcl4 zdgap2 zdw-65pct"></div>
                        <div id='IVSeguridadDiv' class="border_div zdgap2 zdw-35pct zdflex zdfd-column"></div>
                    </div>
                    <div class="zdflex zdgap2 zdw-100pct">
                        <div class="zdflex zdfw-w zdgap2 zdw-100pct">
                            <div id='IVAfinacionDiv' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                            <div id='IVTransmisionDiv' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                        </div>
                        <div class="zdflex zdfw-w zdgap2 zdw-100pct">
                            <div id='IVElectricoDiv' class="border_div zdgap2 zdw-100pct zdgrid"></div>
                            <div id='IVLucesDiv' class="border_div zdgrid zdgcl4 zdgap2 zdw-100pct"></div>
                            <div id='IVSuspencionDiv' class="border_div zdgap2 zdw-100pct zdflex zdfd-column"></div>
                        </div>
                    </div>
                    <div class="zdflex zdgap2 zdw-100pct">
                        <div class="zdw-65pct zdgcl4 zdgrid border_div">
                            <label class="title_inspeccion_tecnica zd_col_span4">FRENOS</label>
                            <div id='IVPastillasDiv' class="border_div zdgap2 zdw-100pct zd_col_span2 zdflex zdfd-column"></div>
                            <div id='IVRotoresDiv' class="border_div zdgap2 zdw-100pct zd_col_span2 zdflex zdfd-column"></div>
                            <div id='IVPinzasDiv' class="border_div zdgap2 zdw-100pct zd_col_span4 zdgcl4 zdgrid"></div>
                        </div>
                        <div id='IVEscapeDiv' class="border_div zdgap2 zdw-35pct zdflex zdfd-column "></div>
                    </div>
                    <div class="zdw-100pct">
                        <h2  class="title_inspeccion_tecnica zdw-100pct"> Firmas</h2>
                        <div class="zdflex zdgap2 zdw-100pct">
                        <div class="canvasconlabel zdw-50pct">
                            <label for="canvasfirma" class="subtitle_inspeccion_tecnica"> Responsable</label>
                            <canvas  id="canvasfirma1" name="canvasfirma1" class="canvasfirma form-control"></canvas>
                            <div class="zdflex zdgap2 zdw-100pct">
                                <button type="button" id="deshacerfirma1" class="btn btn-secondary">Deshacer</button>
                                <button type="button" id="borrarfirma1" class="btn btn-danger">Borrar</button>
                            </div>
                        </div>
                        <div class="canvasconlabel zdw-50pct">
                            <label for="canvasfirma2" class="subtitle_inspeccion_tecnica"> Cliente </label>
                            <canvas  id="canvasfirma2" name="canvasfirma2" class="canvasfirma form-control"></canvas>
                            <div class="zdflex zdgap2 zdw-100pct">
                                <button type="button" id="deshacerfirma2" class="btn btn-secondary">Deshacer</button>
                                <button type="button" id="borrarfirma2" class="btn btn-danger">Borrar</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="zdmg-r02">
                    <iframe id="PreviewInspection" src="#"  class="archivopdf" hidden></iframe>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary IVMclose">Cerrar</button>
                <button type="button" class="btn btn-primary" id="SaveIV">Guardar</button>
                <button id="ToggleVIewInspention" class="btn"></button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/canvasinspeccion.js') }}"></script>
    <script>
        const inputs = [
            {
                div: 'IVRevLucEsp',
                keyarray: 'RevLucEsp',
                elements: [
                {
                    value: `<label class="title_inspeccion_tecnica">Revision De Luces Espias</label>`,
                    tipo: 'fijo'
                },
                {
                    key: 'codigo',
                    label: 'Codigo(s)',
                    tipo: 'button'
                },
                {
                    key: 'notas',
                    tipo: 'textarea'
                }
                ]
            },{
                div: 'IVLiquidosDiv',
                keyarray: 'Liquidos',
                elements: [
                    {
                        tipo: 'fijo',
                        value: `<label class="title_inspeccion_tecnica zd_col_span5">Liquidos</label>
                        <h6 class="zd_col_span3">Condicion</h6>
                        <h6 class="">OK</h6><h6 class="">LLeno</h6>`,
                    },
                    {
                        key: 'motor',
                        label: 'Aceite de Motor',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKmotor',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenomotor',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'trasmision',
                        label: 'Trasmision',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKtrasmision',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenotrasmision',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'diferencial',
                        label: 'Diferencial',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKdiferencial',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenodiferencial',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'refrigerante',
                        label: 'Refrigerante',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKrefrigerante',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenorefrigerante',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'frenos',
                        label: 'Frenos',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKfrenos',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenofrenos',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'direccion',
                        label: 'Direccion Hidraulica',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKdireccion',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenodireccion',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'parabrisas',
                        label: 'LimpiaParabrisas',
                        tipo: 'button',
                        classdiv:'zd_col_span3'
                    },
                    {
                        key: 'OKparabrisas',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'LLenoparabrisas',
                        tipo: 'checkbox',
                    },
                    {
                        key: 'notas',
                        tipo: 'textarea',
                         classdiv:'zd_col_span5'
                    },
                    
                ]
            },
            {
                div: 'IVllantasDiv',
                keyarray: 'llantas',
                elements: [
                {
                    value: `<label class="title_inspeccion_tecnica zd_col_span4">LLantas</label>
                        <div class="zd_col_span4 zdgrid zdgcl4 zdgap4">
                            <div class="zd_col_span2 zdgrid zdgcl4">
                                <h6 class="zd_col_span2">Patron De Desgaste</h6><h6 class="zdtext-left zd_col_span2">Presion</h6>
                            </div>
                            <div class="zd_col_span2 zdgrid zdgcl4">
                                <h6 class="zd_col_span2">Patron De Desgaste</h6><h6 class="zdtext-left zd_col_span2">Presion</h6>
                            </div>
                        </div>
                        `,
                    tipo: 'fijo'
                },
                {
                    key: 'EsDelIzq',
                    label: 'I. Delantera',
                    tipo: 'button',
                    
                },
                {
                    key: 'PreDelIzq',
                    tipo: 'input'
                },
                {
                    key: 'EsTraIzq',
                    label: 'I. Trasesa',
                    tipo: 'button',
                },
                {
                    key: 'PreTraIzq',
                    tipo: 'input'
                },
                {
                    key: 'EsDelDer',
                    label: 'D. Delantera',
                    tipo: 'button',
                },
                {
                    key: 'PreDelDer',
                    tipo: 'input'
                },
                {
                    key: 'EsTraDer',
                    label: 'D. Trasesa',
                    tipo: 'button',
                },
                {
                    key: 'PreTraDer',
                    tipo: 'input'
                },
                {
                    key: 'EsRef',
                    label: 'Refaccion',
                    tipo: 'button',
                },
                {
                    key: 'PreRef',
                    tipo: 'input'
                },
                {
                    value: `<h5 class="zd_col_span4 subtitle_inspeccion_tecnica">EL DESGASTE DE NEUMATICO INDICA QUE:</h5>`,
                    tipo: 'fijo'
                },
                {
                    key: 'aliniacion',
                    label: 'Se Necesita Alineacion Y Balanceo',
                    tipo: 'button',
                    classdiv:'zd_col_span4'
                },
                ]
            },
            {
                div: 'IVManguerasDiv',
                keyarray: 'Mangueras',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">Mangueras</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'refrigerante',
                        label: 'Refrigerante',
                        tipo: 'button'
                    },
                    {
                        key: 'direccion',
                        label: 'Direccion',
                        tipo: 'button'
                    },
                    {
                        key: 'calefaccion',
                        label: 'Calefaccion',
                        tipo: 'button'
                    },
                ]
            },
            {
                div: 'IVBandasDiv',
                keyarray: 'Bandas',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">Bandas</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'accesorios',
                        label: 'Accesorios',
                        tipo: 'button'
                    },
                    {
                        key: 'direccion',
                        label: 'Direccion Hidraulica',
                        tipo: 'button'
                    },{
                        key: 'aire',
                        label: 'Alternados/A. Acondicionado',
                        tipo: 'button'
                    },
                ]
            },
            {
                div: 'IVFiltrosDiv',
                keyarray: 'Filtros',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">Filtros</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'aire',
                        label: 'Aire',
                        tipo: 'button'
                    },
                    {
                        key: 'combustible',
                        label: 'Combustible',
                        tipo: 'button'
                    },
                    {
                        key: 'aceite',
                        label: 'Aceite',
                        tipo: 'button'
                    },
                    {
                        key: 'notas',
                        tipo: 'textarea'
                    },
                ]
            },
            {
                div: 'IVSeguridadDiv',
                keyarray: 'Seguridad',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">Seguridad</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'freno',
                        label: 'Freno de Seguridad',
                        tipo: 'button',
                        classdiv:'zdgcl2'
                    },
                    {
                        value: `<h5 class="zd_col_span2 subtitle_inspeccion_tecnica">LIMPIAPARABRISAS</h5><div class="zdgrid zdgcl2 zdgap2">`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'parabrisasdel',
                        label: 'Izq / Der',
                        tipo: 'button'
                    },
                    {
                        key: 'parabrisastra',
                        label: 'Trasero',
                        tipo: 'button',
                    },
                    {
                        key: 'notas',
                        tipo: 'textarea',
                        classdiv:'zd_col_span2'
                    },
                ]
            },
            {
                div: 'IVAfinacionDiv',
                keyarray: 'afinacion',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">Afinacion Motor</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'tapa',
                        label: 'Tapa Del Distribuidor/Bujias/Cables',
                        tipo: 'button',
                    },
                    {
                        key: 'fuel',
                        label: 'FuelInjection',
                        tipo: 'button',
                    }
                ],
            },
            {
                div: 'IVTransmisionDiv',
                keyarray: 'trasmision',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">TREN DE TRANSMISION</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key: 'filtro',
                        label: 'Tapa Del Distribuidor/Bujias/Cables',
                        tipo: 'button',
                    },
                    {
                        key: 'fuel',
                        label: 'FuelInjection',
                        tipo: 'button',
                    },
                    {
                        key:'filtro',
                        label:'Filtro de Trasmision',
                        tipo:'button'
                    },
                    {
                        key:'union',
                        label:'Union de la Trasmision/Clutch',
                        tipo:'button'
                    },
                    {
                        key:'traccion',
                        label:'Eje De Traccion y Juntas Homocineticas',
                        tipo:'button'
                    },
                    {
                        key:'juntas',
                        label:'Eje De Transmision y Juntas Universales',
                        tipo:'button'
                    },
                    {
                        key:'rodamiento',
                        label:'Rodamientos De Rueda',
                        tipo:'button'
                    },
                    {
                        key:'trasmision',
                        label:'Trasmision',
                        tipo:'button'
                    },
                    {
                        key:'clutch',
                        label:'Clutch',
                        tipo:'button'
                    },
                    {
                        key:'notas',
                        tipo:'textarea'
                    },
                ],
            },{
                div: 'IVElectricoDiv',
                keyarray: 'electrico',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica">ELECTRICO</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'bateria',
                        label:'Sistema de Carga/Baterias',
                        tipo:'button'
                    },
                    {
                        key:'cables',
                        label:'Cables/Conexiones/Fusibles',
                        tipo:'button'
                    },
                ]

            },
            {
                div: 'IVLucesDiv',
                keyarray: 'luces',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica zd_col_span4">Luces</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'freno',
                        label:'Freno/Reversa',
                        tipo:'button',
                        classdiv:'zd_col_span2'
                    },
                    {
                        key:'intermitentes',
                        label:'Intermitente',
                        tipo:'button',
                        classdiv:'zd_col_span2'
                    },
                    {
                        value: `<label class="subtitle_inspeccion_tecnica zd_col_span2">Faros</label><label class="subtitle_inspeccion_tecnica zd_col_span2">Cuartos</label>`,
                        tipo: 'fijo'
                    },
                    
                    {
                        key:'faroizq',
                        label:'Izq',
                        tipo:'button'
                    },
                    
                    {
                        key:'faroder',
                        label:'Der',
                        tipo:'button'
                    },
                    {
                        key:'cuartosizq',
                        label:'Izq',
                        tipo:'button'
                    },
                    {
                        key:'cuartosder',
                        label:'Der',
                        tipo:'button'
                    },
                    {
                        value: `<label class="subtitle_inspeccion_tecnica zd_col_span4">Intermitentes</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'dif',
                        label:'Izq. Fron',
                        tipo:'button'
                    },
                    {
                        key:'dit',
                        label:'Izq. Tras',
                        tipo:'button'
                    },
                    {
                        key:'ddf',
                        label:'Der. Fron',
                        tipo:'button'
                    },
                    {
                        key:'ddt',
                        label:'Der. Tras',
                        tipo:'button'
                    },
                    
                ]

            },{
                div: 'IVSuspencionDiv',
                keyarray: 'suspension',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica zd_col_span4">Suspension/Direccion</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'amortiguadores',
                        label:'Amortigudores/Suspension',
                        tipo:'button'
                    },
                    {
                        key:'direccion',
                        label:'Juntas de Direccion/Rotulas',
                        tipo:'button'
                    },
                    {
                        key:'notas',
                        tipo:'textarea'
                    },
                ]

            },{
                div: 'IVPastillasDiv',
                keyarray: 'pastillas',
                elements: [
                    {
                        value: `<label class="subtitle_inspeccion_tecnica zd_col_span4">PASTILLAS</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'idel',
                        label:'Delantera Izquierda',
                        tipo:'button'
                    },
                    {
                        key:'ddel',
                        label:'Delantera Derecha',
                        tipo:'button'
                    },
                    {
                        key:'itras',
                        label:'Trasera Izquierda',
                        tipo:'button'
                    },
                    {
                        key:'dtras',
                        label:'Trasera Derecho',
                        tipo:'button'
                    },
                ]

            },{
                div: 'IVRotoresDiv',
                keyarray: 'rotores',
                elements: [
                    {
                        value: `<label class="subtitle_inspeccion_tecnica zd_col_span4">ROTORES</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'idel',
                        label:'Delantera Izquierda',
                        tipo:'button'
                    },
                    {
                        key:'ddel',
                        label:'Delantera Derecha',
                        tipo:'button'
                    },
                    {
                        key:'itras',
                        label:'Trasera Izquierda',
                        tipo:'button'
                    },
                    {
                        key:'dtras',
                        label:'Trasera Derecho',
                        tipo:'button'
                    },
                ]

            },{
                div: 'IVPinzasDiv',
                keyarray: 'pinzas',
                elements: [
                    
                    {
                        value: `<label class="subtitle_inspeccion_tecnica zd_col_span4">Pinzas/Cilindros de Rueda</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'idel',
                        label:'Delantera Izquierda',
                        tipo:'button',
                        classdiv:'zd_col_span2' 
                    },
                    {
                        key:'ddel',
                        label:'Delantera Derecha',
                        tipo:'button',
                        classdiv:'zd_col_span2' 
                    },
                    {
                        key:'itras',
                        label:'Trasera Izquierda',
                        tipo:'button',
                        classdiv:'zd_col_span2' 
                    },
                    {
                        key:'dtras',
                        label:'Trasera Derecho',
                        tipo:'button',
                        classdiv:'zd_col_span2' 
                    },
                ]

            },{
                div: 'IVEscapeDiv',
                keyarray: 'escape',
                elements: [
                    {
                        value: `<label class="title_inspeccion_tecnica zd_col_span4">Escape</label>`,
                        tipo: 'fijo'
                    },
                    {
                        key:'mofle',
                        label:'Mofle/Convertidor Catlitico:',
                        tipo:'button'
                    },
                    {
                        key:'sensores',
                        label:'Sensores/Soporte/Tubos',
                        tipo:'button'
                    },
                    {
                        key:'notas',
                        tipo:'textarea'
                    }
                ]

            },
            
        ];
        const formoriginal={
            llantas:{
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
            },
            RevLucEsp:{
                codigo:null,
                notas:''
            }, 
            Mangueras:{
                refrigerante:null,
                direccion:null,
                calefaccion:null
            }, 
            Liquidos:{
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
            }, 
            Bandas:{
                accesorios:null,
                direccion:null,
                aire:null
            }, 
            Filtros:{
                aire:null,
                combustible:null,
                aceite:null,
                notas:''
            }, 
            Seguridad:{
                freno:null,
                parabrisasdel:null,
                parabrisastra:null,
                notas:''
            },
            afinacion:{
                tapa:null,
                fuel:null
            },
            trasmision:{
                filtro:null,
                union:null,
                traccion:null,
                juntas:null,
                rodamiento:null,
                trasmision:null,
                clutch:null,
                notas:'',
            },
            electrico:{
                bateria:null,
                cables:null,
            },
            luces:{
                faroizq:null,
                faroder:null,
                cuartosizq:null,
                cuartosder:null,
                freno:null,
                dif:null,
                dit:null,
                ddf:null,
                ddt:null,
                intermitentes:null,
            },
            suspension:{
                amortiguadores:null,
                direccion:null,
                notas:''
            },
            pastillas:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            rotores:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            pinzas:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            escape:{
                mofle:null,
                sensores:null,
                notas:'',
            },
            imagenes:{
                firma1:null,
                firma2:null
            }
        }
        const form={
            llantas:{
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
            },
            RevLucEsp:{
                codigo:null,
                notas:''
            }, 
            Mangueras:{
                refrigerante:null,
                direccion:null,
                calefaccion:null
            }, 
            Liquidos:{
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
            }, 
            Bandas:{
                accesorios:null,
                direccion:null,
                aire:null
            }, 
            Filtros:{
                aire:null,
                combustible:null,
                aceite:null,
                notas:''
            }, 
            Seguridad:{
                freno:null,
                parabrisasdel:null,
                parabrisastra:null,
                notas:''
            },
            afinacion:{
                tapa:null,
                fuel:null
            },
            trasmision:{
                filtro:null,
                union:null,
                traccion:null,
                juntas:null,
                rodamiento:null,
                trasmision:null,
                clutch:null,
                notas:'',
            },
            electrico:{
                bateria:null,
                cables:null,
            },
            luces:{
                faroizq:null,
                faroder:null,
                cuartosizq:null,
                cuartosder:null,
                freno:null,
                dif:null,
                dit:null,
                ddf:null,
                ddt:null,
                intermitentes:null,
            },
            suspension:{
                amortiguadores:null,
                direccion:null,
                notas:''
            },
            pastillas:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            rotores:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            pinzas:{
                idel:null,
                ddel:null,
                itras:null,
                dtras:null,
            },
            escape:{
                mofle:null,
                sensores:null,
                notas:'',
            },
            imagenes:{
                firma1:null,
                firma2:null
            }
        }
        let IdGeneral=null;
        let exist=false;
        let viewpdf=false;
        $(function(){ 
            function ToggleViews(){
                console.log('validando')
                // if(exist){
                //     $('#ToggleVIewInspention').removeAttr('hidden');
                // }else{
                //     $('#ToggleVIewInspention').attr('hidden',true);
                //     viewpdf=false
                // }
                if(viewpdf){
                    $('#ToggleVIewInspention').text('Editar').addClass('btn-info').removeClass('btn-danger');
                    $('#PreviewInspection').attr('src','');
                    $('#FormNewInpectionVehicular').attr('hidden',true);
                    $('#SaveIV').attr('hidden',true);
                    ViewPDF()
                }else{
                    $('#ToggleVIewInspention').text('Ver PDF').addClass('btn-danger').removeClass('btn-info');
                    $('#PreviewInspection').attr('hidden',true).attr('src','');
                    $('#FormNewInpectionVehicular').removeAttr('hidden');
                    $('#SaveIV').removeAttr('hidden');
                    ViewForm()
                    ajustarCanvas2()
                    if(form.imagenes.firma1 != null){
                        executedibujarImagen1("/storage/inspeccionvehicular/firmastaller/"+form.imagenes.firma1);
                    }
                    if(form.imagenes.firma2 != null){
                        executedibujarImagen2("/storage/inspeccionvehicular/firmasclientes/"+form.imagenes.firma2);
                    }
                        
                }
                 $('#InspeccionVehicularModel').modal('show');
            }
             $(".IVMclose").on('click',function(){
                closethismodal()
            })
            $("#SaveIV").on('click',function(){
                const datos = new FormData();
                let canvas = document.getElementById("canvasfirma1");
                let canvas2 = document.getElementById("canvasfirma2");
                let canvasImage = canvas.toDataURL("image/png");
                let canvasImage2 = canvas2.toDataURL("image/png");
                datos.append('id', IdGeneral);
                datos.append('firma1', canvasImage);
                datos.append('firma2', canvasImage2);
                
                Object.entries(form).forEach(([seccion, campos]) => {
                if (typeof campos === 'object' && campos !== null) {
                    // Recorremos cada campo dentro de la sección
                    Object.entries(campos).forEach(([campo, valor]) => {
                        if(typeof valor == "boolean"){
                            valor=valor?"1":"0";
                        }
                        datos.append(`${seccion}[${campo}]`, valor+'' ?? '');
                    });
                } else {
                    // En caso de que haya campos simples (no aplica aquí, pero sirve por si luego agregas más)
                    datos.append(seccion, campos ?? '');
                }
                });


                $.ajax({
                    type: 'POST',
                    url: '{{ route('2025.InspeccionVehicular.CreateOrUpdate') }}',
                    data: datos,
                    processData: false,
                    contentType: false,
                    headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregar token CSRF en el encabezado
                                },
                    success: function(response) {
                        const mensaje=response.message
                        Swal.fire({ html: `${mensaje}`, icon: 'success',showConfirmButton: false,timer: 1000,});
                        exist=true;
                        viewpdf=true;
                        ToggleViews();
                    },
                    error: function(xhr, status, error) {
                        if(xhr.status===422){
                            Swal.fire({ title: 'Error', html: `Los Datos No Son Correctos`, icon: 'error'});
                        }else{
                            let errorMessage = 'Intentelo de nuevo, si el error persiste contacte a Soporte.';
                            Swal.fire({ title: 'Error', html: `${errorMessage}<br>Detalles del error: ${error}<br>${status} : ${xhr.status}`, icon: 'error'});
                        }

                        console.error(xhr);
                    }
                })
            });
            $("#ToggleVIewInspention").on('click',function(){
                viewpdf=!viewpdf;
                ToggleViews();
            })
            function closethismodal(){
                $('#InspeccionVehicularModel').modal('hide')
            }
            window.OpenInspenccionVehicular=function(id){

                IdGeneral=id;
               
                $.ajax({
                    type: 'GET',
                    url: '{{ route('2025.InspeccionVehicular.Read') }}',
                    data: {
                        id: IdGeneral,
                    },
                    success: function(response) {
                        exist=response.message == 'Existe'
                        viewpdf=exist;
                        let data = formoriginal;
                        if(exist){
                            data = response.data;
                        }
                        $.extend(form.llantas, data.llantas);
                        $.extend(form.RevLucEsp, data.RevLucEsp);
                        $.extend(form.Mangueras, data.Mangueras);
                        $.extend(form.Liquidos, data.Liquidos);
                        $.extend(form.Bandas, data.Bandas);
                        $.extend(form.Filtros, data.Filtros);
                        $.extend(form.Seguridad, data.Seguridad);
                        $.extend(form.afinacion, data.afinacion);
                        $.extend(form.trasmision, data.trasmision);
                        $.extend(form.electrico, data.electrico);
                        $.extend(form.luces, data.luces);
                        $.extend(form.suspension, data.suspension);
                        $.extend(form.pastillas, data.pastillas);
                        $.extend(form.rotores, data.rotores);
                        $.extend(form.pinzas, data.pinzas);
                        $.extend(form.escape, data.escape);
                        $.extend(form.imagenes, data.imagenes);
                        ToggleViews();
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
            });
            }
            function button(value,label,keyarray,key,divid,clasesdiv){
                return `<div class='zdflex zdw-100pct zdgap2 ${clasesdiv}'>
                    <div id=${divid} class='zdflex zdgap2'>
                    <button class='btn_circulo_red buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='1'>`+(value==1?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                    <button class='btn_triangulo buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='2'>`+(value==2?'<img src="/storage/triangulobutton.png"/><span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':`<img src="/storage/triangulobutton.png"/>`)+`</button>
                    <button class='btn_circulo_verde buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='3'>`+(value==3?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                    </div>
                    <label>`+label+`</label>
                    </div>
                    `
            }
            function reprintbuttons(divid,key,keyarray,value){
                $('#'+divid).empty();
                $('#'+divid).append(`
                    <button class='btn_circulo_red buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='1'>`+(value==1?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                    <button class='btn_triangulo buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='2'>`+(value==2?'<img src="/storage/triangulobutton.png"/><span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':`<img src="/storage/triangulobutton.png"/>`)+`</button>
                    <button class='btn_circulo_verde buttonIV' data-key=${key} data-key-array=${keyarray} data-div=${divid} data-value='3'>`+(value==3?'<span><img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Octicons-check.svg"  alt="Checked Icon" /></span>':'<span></span>')+`</button>
                `);

            }
            function checkbox(value,keyarray,key){
                const checked = value===true ? 'checked' : '';

                return ` <input type="checkbox" class="validation checkboxIV " data-key=${key}  data-key-array=${keyarray} data-value="${value}" ${checked}/>`
            }
            function input(value,keyarray,key){
                return `<div class="zdflex zdjc-end"><input type='number' class='inputIV' data-key=${key} data-key-array=${keyarray} value="${value}"></input></div>`
            }
            function textarea(value,keyarray,key,classdiv=''){
                return `<div class="zdflex zdw-100pct  zdfd-column ${classdiv}"><label>NOTAS</label><textarea class="form-control notasIV" data-key=${key} data-key-array=${keyarray} >${value}</textarea></div>`
            }
            function ViewForm(){
                 let contdivbuttons=0
                inputs.forEach(data => {
                            const div=data['div'];
                            const keyarray=data['keyarray'];
                            const elements=data['elements'];
                            let news=``;

                            elements.forEach(element => {
                                const tipo=element['tipo']
                                
                                if(['button','textarea','input','checkbox'].includes(tipo)){
                                    const key=element['key'];
                                    if(tipo == 'button'){
                                        contdivbuttons++;
                                        news+=button(form[keyarray][key],element['label'],keyarray,key,div+'_'+keyarray+'_'+contdivbuttons,element['classdiv']??'')
                                    }
                                    else if(tipo == 'checkbox'){
                                        news+=checkbox(form[keyarray][key],keyarray,key)
                                    }
                                    else if(tipo == 'input'){
                                        news+=input(form[keyarray][key],keyarray,key)
                                    }
                                    else{
                                        news+=textarea(form[keyarray][key],keyarray,key,element['classdiv']??'')
                                    }
                                }
                                if(tipo=='fijo'){
                                    news+=element['value']
                                }
                            })
                            $('#'+div).empty();
                            $('#'+div).append(news);
                        });
                       
            }
            function renderHtmlEnIframe(idIframe, html) {
                const iframe = document.getElementById(idIframe);
                if (!iframe) return console.warn('Iframe no encontrado:', idIframe);

                const doc = iframe.contentDocument || iframe.contentWindow?.document;
                if (!doc) return console.warn('No se pudo acceder al documento del iframe.');

                doc.open();
                doc.write(html || '<!DOCTYPE html><html><head></head><body></body></html>');
                doc.close();
                }

            function ViewPDF() {
                $('#PreviewInspection').attr('src', 'about:blank');
                setTimeout(() => {
                    const pdfUrl = "/Zcrat/InspeccionVehicular/PDF/" + IdGeneral + "?v=" + Date.now() + "#FitPage";
                    $('#PreviewInspection').attr('src', pdfUrl).removeAttr('hidden');;
                }, 50);
                // $.ajax({
                //     
                //     method: 'GET',
                //     data: { id:IdGeneral },
                //     dataType: 'json',
                //     success: function(response) {
                //     $('#PreviewInspection').attr('src','/Zcrat/InspeccionVehicular/PDF2?id=5305');
                //     // renderHtmlEnIframe('PreviewInspection', response.html);
                //     },
                //     error: function(xhr) {
                //     console.error(xhr);
                //     Swal.fire({
                //         icon: "error",
                //         title: "Error en la solicitud",
                //         text: "Ocurrió un error inesperado. Contacta a soporte.",
                //         timer: 5000
                //     });
                //     renderHtmlEnIframe('PreviewInspection'); // vacía el iframe
                //     }
                // });
            }
            $(document).on('input', '.notasIV', function() {
                const value = $(this).val();
                const keyarray = $(this).attr('data-key-array');
                const key = $(this).attr('data-key');
                form[keyarray][key] = value;
            });
            $(document).on('click', '.checkboxIV', function() {
                const key = $(this).attr('data-key');
                const keyarray = $(this).attr('data-key-array');
                const value = $(this).attr('data-value') === 'true';
                form[keyarray][key] = !value;
            });
            $(document).on('input', '.inputIV', function() {
                const value = $(this).val();
                const key = $(this).attr('data-key');
                const keyarray = $(this).attr('data-key-array');
                form[keyarray][key] = value;
            });
            $(document).on('click', '.buttonIV', function(e) {
                const key = $(this).attr('data-key');
                const div = $(this).attr('data-div');
                const keyarray = $(this).attr('data-key-array');
                const value = $(this).attr('data-value');
                form[keyarray][key] = value;
                reprintbuttons(div,key,keyarray,value)
            });
        })
    </script>
@endpush