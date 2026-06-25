<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Reporte Inspección Técnica de Vehículo</title>
    <script>
        function imprim1(print){
            var printContents = document.getElementById('print').innerHTML;
            w = window.open();
            w.document.write(printContents);
            w.document.close(); // necessary for IE >= 10
            w.focus(); // necessary for IE >= 10
            w.print();
            w.close();
            return true;
        }
    </script>
    <style>
      .text-akumas{
        color: #000075;
      }
      .bg-akumas{
        background-color: #000075;
      }
      .border-akumas{
        border: 1px solid #000075 !important;
      }
      .badge-akumas{
        background-color: #000075;
        color: white;
      }
    </style>
</head>

<body>
    @php
    function renderbuttons($estado) {
        switch ($estado) {
            case 1:
                return '<i class="fas fa-check-circle" style="color:red"></i>'
                     . '<i class="fas fa-exclamation-triangle" style="color:#FFEA00"></i>'
                     . '<i class="fas fa-circle" style="color:green"></i>';
            case 2:
                return '<i class="fas fa-circle" style="color:red"></i>'
                     . '<img src="' . asset('img/triangle-check.png') . '" style="position:relative; top:-2px" width="18px">'
                     . '<i class="fas fa-circle" style="color:green"></i>';
            case 3:
                return '<i class="fas fa-circle" style="color:red"></i>'
                     . '<i class="fas fa-exclamation-triangle" style="color:#FFEA00"></i>'
                     . '<i class="fas fa-check-circle" style="color:green"></i>';
            default:
                return '';
        }
    }
    function RenderLiquidos($estado, $ok, $lleno, $label) {
        $html = '<div class="row">';
        $html .= '<div class="col-md-9 pr-0">';
        $html .= renderbuttons($estado);
        $html .= '<small class="text-akumas">' . e($label) . '</small>';
        $html .= '</div>';

        $html .= '<div class="col-md-1 px-0">';
        $html .= '<input type="checkbox" ' . ($ok ? 'checked' : '') . ' disabled />';
        $html .= '</div>';

        $html .= '<div class="col-md-2">';
        $html .= '<input type="checkbox" ' . ($lleno ? 'checked' : '') . ' disabled />';
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }
    function RenderLlantas($estado, $presion, $label) {

        $html = '<div class="row">';
        $html .= '<div class="col-md-8">';
        $html .= renderbuttons($estado);
        $html .= '<small class="text-akumas">' . e($label) . '</small>';
        $html .= '</div>';


        $html .= '<div class="col-md-2">';
        $html .= '<input class="form-control form-control-sm text-center" type="text" value="'.$presion.'" disabled />';
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }
    function RenderSimple($estado, $label) {
        $html = renderbuttons($estado);
        $html .= '<small class="text-akumas">' . e($label) . '</small>';
        return $html;
    }
    function renderFaros($izq, $der,$label) {
        $total = floor($izq + $der);

        $html = '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= RenderSimple($total, $label);
        $html .= '</div>';

        $html .= '<div class="col-md-3">';
        $html .= '<small class="text-akumas">';
        $html .= 'Izq. ';
        if ($izq > 1) {
            $html .= '<i class="fas fa-check"></i>';
        }
        $html .= '</small>';
        $html .= '</div>';

        $html .= '<div class="col-md-3">';
        $html .= '<small class="text-akumas">';
        $html .= 'Der. ';
        if ($der > 1) {
            $html .= '<i class="fas fa-check"></i>';
        }
        $html .= '</small>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
    function renderDireccionales($dif,$dit,$ddf,$ddt) {
        $html = '<div class="row">';

        // Columna de íconos de estado
        $html .= '<div class="col-md-5 pr-0">';
        $estado = floor($dif + $dit+$ddf+$ddt);
        $html .= RenderSimple($estado, 'Direccionales');
        $html .= '</div>';

        // Indicadores individuales
        $html .= '<div class="col-md-1 px-0"><small class="text-akumas">IF.' . ($dif>1 ? ' <i class="fas fa-check"></i>' : '') . '</small></div>';
        $html .= '<div class="col-md-1 px-0"><small class="text-akumas">IT.' . ($dit>1 ? ' <i class="fas fa-check"></i>' : '') . '</small></div>';
        $html .= '<div class="col-md-1 px-0"><small class="text-akumas">DF.' . ($ddf>1 ? ' <i class="fas fa-check"></i>' : '') . '</small></div>';
        $html .= '<div class="col-md-2 px-0"><small class="text-akumas">DT.' . ($ddt>1 ? ' <i class="fas fa-check"></i>' : '') . '</small></div>';

        $html .= '</div>';
        return $html;
    }

@endphp

    <div class="container-fluid" id="print">
        <div class="container">
            <div class="row">
                <div class="col-md-7 text-center" style="background: #B3B3B3">
                    <h2 class="">Reporte de Inspección Técnica de Vehículo Multi-Punto </h2>
                </div>
                <div class="col-md-5 text-center">
                    <img  src="{{asset('img/'.$InspeccionVehicular->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor)}}" width="100%"  alt="Logo Akumas">
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 text-left">
                    <strong>Fecha: </strong><small>{{$InspeccionVehicular->detallesGenerales->Fecha_entrada}}</small>
                </div>
                <div class="col-md-5 text-center">
                    <strong>{{$InspeccionVehicular->detallesGenerales->modulo->FacturaEmisor->nombre_emisor}}, S.A. DE .C.V. </strong>
                    <div><small class="text-akumas">CORREGIDORA #1033, COL. CENTRO, C.P. 58000, MORELIA, MICH.</small></div>
                    <div><small class="text-akumas">TELS (443) 690 3540 / Nex. 279 7139 Y 29</small></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Nombre: </strong><small>{{$InspeccionVehicular->detallesGenerales->Empresa->nombre}}</small>
                </div>
                <div class="col-md-6">
                    <strong>Tel. </strong><small>{{$InspeccionVehicular->detallesGenerales->Telefono}}</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <strong>Vehíc./Marca/Modelo/Año: </strong><small>{{$InspeccionVehicular->detallesGenerales->Vehiculo->marca->nombre.'/'.$InspeccionVehicular->detallesGenerales->Vehiculo->modelo->nombre.'/'.$InspeccionVehicular->detallesGenerales->Vehiculo->anio}}</small>
                </div>
                <div class="col-md-3">
                    <strong>Placas: </strong> <small>{{$InspeccionVehicular->detallesGenerales->Vehiculo->placas}}</small>
                </div>
                <div class="col-md-3">
                    <strong>Kilometraje: </strong> <small>{{$InspeccionVehicular->detallesGenerales->Kilometraje_entrada}}</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>#VIN: </strong><small>{{$InspeccionVehicular->detallesGenerales->Vehiculo->vim}}</small>
                </div>
                <div class="col-md-3">
                    <strong>#economico: </strong> <small>{{$InspeccionVehicular->detallesGenerales->Vehiculo->no_economico}}</small>
                </div>
                <div class="col-md-3">
                    <strong>O.R. #: </strong> <small>{{$InspeccionVehicular->detallesGenerales->OrdenSeguimiento}}</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="card border border-danger">
                        <div class="p-0 pl-3 card-header">
                            Daños Visibles en Pre-Inspección del Vehículo:
                        </div>
                        <div class="card-body p-0">
                            <img src="{{asset('/storage/carros/'.$InspeccionVehicular->detallesGenerales->recepcionesVehiculares[0]->Carro.'')}}" width="100%" height="" alt="Daños">
                        </div>
                        <div class="p-0 card-footer bg-white text-center">
                            <strong>Antefirma del Cliente:
                                @if ($InspeccionVehicular->detallesGenerales->recepcionesVehiculares)
                                    <img src="{{asset('/storage/firmastaller/'.$InspeccionVehicular->detallesGenerales->recepcionesVehiculares[0]->Firma)}}" width="20%" alt="">
                                @else
                                _________________
                                @endif
                               </strong>
                            <strong> Fecha: </strong><small>{{$InspeccionVehicular->created_at}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border border-danger">
                        <div class="p-0 pl-3 card-header ">
                           Reporte de Fallas:
                        </div>
                        <div class="card-body">
                            @if ($InspeccionVehicular->detallesGenerales->Indicaciones_cliente)
                                <small>{{$InspeccionVehicular->detallesGenerales->Indicaciones_cliente}}</small>
                            @else
                                <hr>
                                <hr>
                                <hr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="card text-center ">
                        <div class="p-0 pl-3 card-header">
                            <i class="fas fa-circle" style="color:red"></i><strong> Requiere Atención Inmediata</strong>
                            <i class="fas fa-exclamation-triangle" style="color: #FFEA00"></i><strong> Puede Requerir Atención Futura</strong>
                            <i class="fas fa-circle" style="color:green" ></i><strong> Inspeccionada y esta bien</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container ">
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="card border border-danger ">
                        <div class="p-0 pl-3 card-header bg-danger">
                            <i class="fas fa-square" style="color:white"></i>
                            <strong class="text-white"> 26 PUNTOS - INSPECCIÓN DE VEHÍCULO </strong>
                        </div>
                        <div class="card-body py-1">
                            <div class="row">
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">REVISIÓN DE LUCES ESPÍAS</strong>
                                        </div>
                                        <div class="card-body py-0 px-0">
                                            {!! RenderSimple($InspeccionVehicular->revisionLucesEspias->codigo,'Código(s):') !!}
                                            <hr>
                                            <hr>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1 px-0">
                                        <div class="card">
                                            <div class="p-0 pl-3 card-header bg-akumas">
                                                <strong class="text-white">LÍQUIDOS</strong>
                                            </div>
                                            <div class="card-body py-0 px-0">
                                              <div class="row">
                                                <div class="col-md-9">
                                                  <small class="text-akumas"><strong>CONDICIÓN:</strong></small>
                                                </div>
                                                <div class="col-md-1 px-0">
                                                  <small class="text-akumas"><strong>OK</strong></small>
                                                </div>
                                                <div class="col-md-2 px-0">
                                                  <small class="text-akumas"><strong>LLENO</strong> </small>
                                                </div>
                                              </div>
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->aceite_motor,$InspeccionVehicular->liquidos->aceite_motor_ok,$InspeccionVehicular->liquidos->aceite_motor_lleno,'Aceite de Motor:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->transmision,$InspeccionVehicular->liquidos->transmision_ok,$InspeccionVehicular->liquidos->transmision_lleno,'Transmisión:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->diferencial_frente_trasero,$InspeccionVehicular->liquidos->diferencial_frente_trasero_ok,$InspeccionVehicular->liquidos->diferencial_frente_trasero_lleno,'Diferencial: FRENTE/TRASERO:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->liquido_refrigerante,$InspeccionVehicular->liquidos->refrigerante_ok,$InspeccionVehicular->liquidos->refrigerante_lleno,'Refrigerante:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->frenos,$InspeccionVehicular->liquidos->frenos_ok,$InspeccionVehicular->liquidos->frenos_lleno,'Frenos:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->direccion_hidraulica,$InspeccionVehicular->liquidos->direccion_hidraulica_ok,$InspeccionVehicular->liquidos->direccion_hidraulica_lleno,'Dirección Hidráulica:') !!}
                                                {!! RenderLiquidos($InspeccionVehicular->liquidos->limpiaparabrisas,$InspeccionVehicular->liquidos->limpiaparabrisas_ok,$InspeccionVehicular->liquidos->limpiaparabrisas_lleno,'Limpiaparabrisas:') !!}
                                                <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                                @if ($InspeccionVehicular->liquidos->liquido_notas)
                                                    <small>{{$InspeccionVehicular->liquidos->liquido_notas}}</small>
                                                @else
                                                    <hr>
                                                    <hr>
                                                    <hr>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">MANGUERAS</strong>
                                        </div>
                                        <div class="card-body pt-0 px-0">
                                            {!! RenderSimple($InspeccionVehicular->mangueras->refrigerante,'Refrigerante:') !!}
                                            <br>
                                            {!! RenderSimple($InspeccionVehicular->mangueras->direccion_aire_acondicionado,'Dirección/Aire Acondic.:') !!}
                                            <br>
                                            {!! RenderSimple($InspeccionVehicular->mangueras->calefaccion,'Calefacción:') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1 px-0">
                                        <div class="card">
                                            <div class="p-0 pl-3 card-header bg-akumas">
                                                <strong class="text-white">BANDAS</strong>
                                            </div>
                                            <div class="card-body pt-0 px-0">
                                                {!! RenderSimple($InspeccionVehicular->bandas->accesorios,'Accesorios:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->bandas->bandas_direccion_hidraulica,'Dirección Hidráulica:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->bandas->alternador_aire_acondicionado,'>Alternador/A.Acondic:') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1 px-0">
                                        <div class="card">
                                            <div class="p-0 pl-3 card-header bg-akumas">
                                                <strong class="text-white">FILTROS</strong>
                                            </div>
                                            <div class="card-body py-0 px-0">
                                                {!! RenderSimple($InspeccionVehicular->filtros->aire,'>Aire:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->filtros->combustible,'>Combustible:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->filtros->aceite,'>Aceite:') !!}
                                                <br>
                                                <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                                @if ($InspeccionVehicular->filtros->notas)
                                                    <small>{{$InspeccionVehicular->filtros->notas}}</small>
                                                @else
                                                    <hr>
                                                    <hr>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">LLANTAS</strong>
                                        </div>
                                        <div class="card-body pt-0 px-0">
                                            <div class="row">
                                                <div class="col-md-8"><strong><small class="text-akumas">PATRÓN DE DESGASTE/DAÑO:</small></strong></div>
                                                <div class="col-md-3 p-0 pl-2"><strong><small class="text-akumas">PRESIÓN</small></strong></div>
                                            </div>
                                            {!! RenderLlantas($InspeccionVehicular->llantas->izquierda_delantera,$InspeccionVehicular->llantas->izquierda_delantera_presion,'I. Dlantera:') !!}
                                            {!! RenderLlantas($InspeccionVehicular->llantas->izquierda_trasera,$InspeccionVehicular->llantas->izquierda_trasera_presion,'I. Trasera::') !!}
                                            {!! RenderLlantas($InspeccionVehicular->llantas->derecha_delantera,$InspeccionVehicular->llantas->derecha_delantera_presion,'D. Dlantera:') !!}
                                            {!! RenderLlantas($InspeccionVehicular->llantas->derecha_trasera,$InspeccionVehicular->llantas->derecha_trasera_presion,'D. Trasera:') !!}
                                            {!! RenderLlantas($InspeccionVehicular->llantas->refaccion,$InspeccionVehicular->llantas->refaccion_presion,'Refaccion:') !!}
                                              <small class="text-akumas">EL DESGATE DE NEUMÁTICOS INDICA QUE:</small>
                                              <br>
                                              {!! RenderSimple($InspeccionVehicular->llantas->alineacion_balanceo,'Se necesita Alineación y Balanceo:') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1 px-0">
                                        <div class="card">
                                            <div class="p-0 pl-3 card-header bg-akumas">
                                                <strong class="text-white">SEGURIDAD</strong>
                                            </div>
                                            <div class="card-body py-0 px-0">
                                                {!! RenderSimple($InspeccionVehicular->seguridad->frenos_emergencia,'Freno de Emergencia:') !!}
                                                <br>
                                                <small class="text-akumas"><strong>LIMPIAPARABRISAS</strong></small>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {!! RenderSimple($InspeccionVehicular->seguridad->limpiaparabrisas_izquierdo_derecho,'Izq./Der:') !!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! RenderSimple($InspeccionVehicular->seguridad->limpiaparabrisas_trasero,'Trasero') !!}
                                                    </div>
                                                </div>
                                                <br>
                                                <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                                @if ($InspeccionVehicular->seguridad->notas)
                                                    <small>{{$InspeccionVehicular->seguridad->notas}}</small>
                                                @else
                                                    <hr>
                                                    <hr>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-akumas">
                        <div class="p-0 pl-3 card-header bg-akumas">
                            <i class="fas fa-square" style="color:white"></i>
                            <strong class="text-white"> 57 PUNTOS - INSPECCIÓN DE VEHÍCULO <sub>(Incluye todos los anteriores)</sub> </strong>
                        </div>
                        <div class="card-body py-1 ">
                            <div class="row">
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">AFINACIÓN DE MOTOR</strong>
                                        </div>
                                        <div class="card-body pt-0 px-0">
                                            {!! RenderSimple($InspeccionVehicular->afinacionMotor->tapa_distribuidor_bujias_cables,'Tapa del Distribuidor/Bujías/Cables:') !!}
                                            <br>
                                            {!! RenderSimple($InspeccionVehicular->afinacionMotor->fuel_injection,'Fuel Injection:') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0">
                                        <div class="card">
                                            <div class="p-0 pl-3 card-header bg-akumas">
                                                <strong class="text-white">TREN DE TRANSMISÓN</strong>
                                            </div>
                                            <div class="card-body py-0 px-0">
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->filtro_transmison,'Filtro de Transmisión:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->union_transmision_clutch,'Unión de la Transmisión/Clutch:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->eje_traccion_juntas_homocineticas,'Eje de Tracción y Juntas Homocinéticas:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->eje_transmision_juntas_universales,'Eje de Transmisión y Juntas Universales:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->rodamientos_rueda,'Rodamientos de Rueda:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->tren_transmision,'Transmisón:') !!}
                                                <br>
                                                {!! RenderSimple($InspeccionVehicular->trenTransmision->clutch,'Clutch:') !!}
                                                <br>
                                                <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                                @if ($InspeccionVehicular->trenTransmision->tren_notas)
                                                    <small>{{$InspeccionVehicular->trenTransmision->tren_notas}}</small>
                                                @else
                                                    <hr>
                                                    <hr>
                                                    <hr>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">ELÉCTRICO</strong>
                                        </div>
                                        <div class="card-body pt-0 px-0">
                                            {!! RenderSimple($InspeccionVehicular->electrico->sistema_carga_bateria,'Sistema de Carga/Bateria:') !!}
                                            <br>
                                            {!! RenderSimple($InspeccionVehicular->electrico->cables_conexiones_fusibles,'Cables/Conexiones/Fusibles:') !!}
                                            <br>
                                                <small class="text-akumas">LUCES:</small>
                                            <br>

                                            {!! RenderFaros($InspeccionVehicular->electrico->faro_izquierda,$InspeccionVehicular->electrico->faro_derecha,'Faros') !!}
                                            {!! RenderFaros($InspeccionVehicular->electrico->cuarto_izquierda,$InspeccionVehicular->electrico->cuarto_derecha,'Cuartos') !!}
                                            {!! RenderSimple($InspeccionVehicular->electrico->reversa_frenos,'Frenos/Reversa:') !!}
                                            <br>
                                            {!! renderDireccionales(
                                                $InspeccionVehicular->electrico->direccionales,
                                                $InspeccionVehicular->electrico->direccionales_izquierda_delantera,
                                                $InspeccionVehicular->electrico->direccionales_derecha_delantera,
                                                $InspeccionVehicular->electrico->direccionales_izquierda_trasera
                                            ) !!}
                                            {!! RenderSimple($InspeccionVehicular->electrico->intermitentes,'Intermitentes:') !!}
                                        </div>
                                    </div>
                                    <div class="card">
                                      <div class="p-0 pl-3 card-header bg-akumas">
                                          <strong class="text-white">SUSPENSIÓN/DIRECCIÓN</strong>
                                      </div>
                                      <div class="card-body py-0 px-0">
                                        {!! RenderSimple($InspeccionVehicular->suspencionDireccion->amortiguadores_suspencion,'Amortiguadores/Suspensión:') !!}
                                         <br>
                                        {!! RenderSimple($InspeccionVehicular->suspencionDireccion->juntas_direccion_rotulas,'Juntas de Dirección/Rótulas:') !!}
                                        <br>
                                        <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                        @if ($InspeccionVehicular->suspencionDireccion->notas)
                                            <small>{{$InspeccionVehicular->suspencionDireccion->notas}}</small>
                                        @else
                                            <hr>
                                            <hr>
                                            <hr>
                                        @endif
                                      </div>
                                  </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">FRENOS</strong>
                                        </div>
                                        <div class="card-body py-0 px-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-akumas">PASTILLAS:</small>
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pastillas_izquierda_delantera,'I. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pastillas_derecha_delantera,'D. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pastillas_izquierda_trasera,'I. Tras.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pastillas_derecha_trasera,'D. Tras.:') !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-akumas">ROTORES:</small>
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->rotores_izquierda_delantera,'I. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->rotores_derecha_delantera,'D. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->rotores_izquierda_trasera,'I. Tras.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->rotores_derecha_trasera,'D. Tras.:') !!}
                                                </div>
                                            </div>
                                            <small class="text-akumas">PINZAS/CILINDROS DE RUEDA:</small>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pinzas_cilindros_rueda_izquierda_delantera,'I. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pinzas_cilindros_rueda_derecha_delantera,'D. Del.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pinzas_cilindros_rueda_izquierda_trasera,'I. Tras.:') !!}
                                                    <br>
                                                    {!! RenderSimple($InspeccionVehicular->frenos->pinzas_cilindros_rueda_derecha_trasera,'D. Tras.:') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="p-0 pl-3 card-header bg-akumas">
                                            <strong class="text-white">ESCAPE</strong>
                                        </div>
                                        <div class="card-body py-0 px-0">
                                            {!! RenderSimple($InspeccionVehicular->escape->mofle_convertidor_catlitico,'Mofle/Convertidor Catlítico:') !!}
                                            <br>
                                            {!! RenderSimple($InspeccionVehicular->escape->sensores_soporte_tubos,'Sensores/Soportes/tubos:') !!}
                                            <br>
                                            <br>
                                            <h5><span class="badge badge-akumas">NOTAS:</span></h5>
                                            @if ($InspeccionVehicular->escape->escape_notas)
                                                <small>{{$InspeccionVehicular->escape->escape_notas}}</small>
                                            @else
                                                <hr>
                                                <hr>
                                                <hr>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
              <div class="col-md-4">
                  <small>Inspeccionado por:</small>
                  @if ($InspeccionVehicular->User)
                      {{$InspeccionVehicular->user->name}}
                  @else
                  _________________
                  @endif
              </div>
              <div class="col-md-4">
                  <small>Firma del cliente:</small>
                  @if ($InspeccionVehicular->Firma)
                  <img src="{{asset('img/firmas/'.$InspeccionVehicular->Firma)}}"
                  width="20%" alt="">
                  @else
                  _________________
                @endif
              </div>
              <div class="col-md-4">
                <small>Fecha:</small>
                {{$InspeccionVehicular->created_at}}
              </div>
            </div>
          </div>
    </div>
</body>
</html>
