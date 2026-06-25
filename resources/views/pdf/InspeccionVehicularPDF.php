<?php

include("mc_table.php");
define('FPDF_FONTPATH', 'font/');
 use Carbon\Carbon;
$pdf = new PDF_MC_Table();
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage('P','A4');

$datos=$InspeccionVehicular->detallesGenerales;
$vehiculo=$datos->vehiculo;

    $tw = 210;
    $th = 297;
    $text=14;
    $x = 5;
    $y = 5;
    $w=200;
    $h=83;
    $pdf->SetFont('Arial','B',$text);
    step1($pdf,$x,$y,$InspeccionVehicular);
    $y+=$h;
    $h=93;
    $pdf->SetFont('Arial','B',$text);
    step22($pdf,$x,$y,$w,$h,$InspeccionVehicular);
    $y+=$h+2;
    $h=90;
    $pdf->SetFont('Arial','B',$text);
    step3($pdf,$x,$y,$w,$h,$InspeccionVehicular);
    $y+=$h+2;


    $w=90;
    $h=12;
    if($InspeccionVehicular->firma1??false){
        $pdf->Image('storage/inspeccionvehicular/firmastaller/'.$InspeccionVehicular->firma1,$x, $y, $w, $h); // 'F' = relleno
    }
    if($InspeccionVehicular->firma2??false){
        $pdf->Image('storage/inspeccionvehicular/firmasclientes/'.$InspeccionVehicular->firma2,$x+$w+20, $y, $w, $h); // 'F' = relleno
    }
    $x1=$x;
    $y+=$h;
    $h=4;
    $pdf->SetXY($x1,$y);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell($w,$h,"Inspeccionado Por:",'T',1,"C",FALSE);
    $x1+=$w+20;
    $pdf->SetXY($x1,$y);
    $pdf->Cell($w,$h,"Firma Cliente:",'T',1,"C",FALSE);

    $pdf->Output();
exit;

function step1($pdf,$x,$y,$InspeccionVehicular){
    $w=105;
    $h=15;
    setbg($pdf,1);
    setcl($pdf,1);
    $pdf->SetXY($x,$y);
    $pdf->RoundedRect($x, $y, $w, $h, 2, 'F'); // 'F' = relleno
    $pdf->MultiCell($w, $h/2, "Reporte de Inspeccion Tecnica de Vehiculo\n Multi-Punto", 0, "C", false);
    $wimg=90;
    $x1=210-5-$wimg;
    
    $pdf->Image('img/'.$InspeccionVehicular->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor,$x1,$y,$wimg,$h);
    $y+=$h;
    $pdf->SetXY($x1,$y);
    $pdf->SetFont('Arial','B',8);
    $pdf->MultiCell($wimg, 3, $InspeccionVehicular->detallesGenerales->modulo->FacturaEmisor->nombre_emisor.", S.A. DE .C.V.", 0, "C", false);
    $y+=3;
    $pdf->SetFont('Arial','',6);
    $pdf->SetXY($x1,$y);
    $pdf->MultiCell($wimg, 2.5, "PUERTO DE ACAPULCO #328, COL. RINCON DEL ANGEL. C.P. 58337 \n MORELIA, MICH, TEL (433) 2532182", 0, "C", false);
    $y+=5;
    $fecha=Carbon::parse($InspeccionVehicular->created_at)->translatedFormat('j \\d\\e F \\d\\e Y');
    $pdf->SetFont('Arial','',9);
    setcl($pdf,1);
    $x1=$x;
    $y1=$y;
    $h=4;
    $w=12;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Fecha:",0,1,"L",FALSE);
    $x1+=$w;
    $w=40;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$fecha,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=10;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"O.S.#:",'',1,"L",FALSE);
    $x1+=$w;
    $w=110-$x1;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$InspeccionVehicular->detallesGenerales->OrdenServicio,'B',1,"L",FALSE);
    
    $y1+=$h+1;
    $x1=$x;
    $w=15;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Nombre:",0,1,"L",FALSE);
    $x1+=$w;
    $w=105-$w;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$fecha,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=8;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"TEL:",'',1,"L",FALSE);
    $x1+=$w;
    $w=30;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$InspeccionVehicular->detallesGenerales->Empresa->tel_negocio,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=17;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Kilometraje:",'',1,"L",FALSE);
    $x1+=$w+2;
    $w=210-$x1-5;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$InspeccionVehicular->detallesGenerales->Kilometraje_entrada,'B',1,"L",FALSE);
    $y1+=$h+1;
    $x1=$x;
    $w=15;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Vehiculo:",0,1,"L",FALSE);
    $x1+=$w;
    $w=60;
    $pdf->SetXY($x1,$y1);
    $vehiculo=$InspeccionVehicular->detallesGenerales->Vehiculo->marca->nombre."/".$InspeccionVehicular->detallesGenerales->Vehiculo->modelo->nombre.'/'.$InspeccionVehicular->detallesGenerales->Vehiculo->anio;
    $pdf->Cell($w,$h,$vehiculo,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=12;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Placas:",0,1,"L",FALSE);
    $x1+=$w;
    $w=20;
    $pdf->SetXY($x1,$y1);
    $placas=$InspeccionVehicular->detallesGenerales->Vehiculo->placas;
    $pdf->Cell($w,$h,$placas,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=10;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"#VIN:",0,1,"L",FALSE);
    $x1+=$w;
    $w=40;
    $pdf->SetXY($x1,$y1);
    $placas=$InspeccionVehicular->detallesGenerales->Vehiculo->vim;
    $pdf->Cell($w,$h,$placas,'B',1,"L",FALSE);
    $x1+=$w+2;
    $w=17;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,"Economico:",'',1,"L",FALSE);
    $x1+=$w+2;
    $w=210-$x1-5;
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,$InspeccionVehicular->detallesGenerales->Vehiculo->no_economico,'B',1,"L",FALSE);
    $y1+=$h+2;
    $x1=$x;
    $w=110;
    $h=32;
    setbg($pdf,2);
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(193, 89, 64);
    $pdf->RoundedRect($x1, $y1, $w, $h, 2, 'D'); // 'F' = relleno
    $pdf->Image('storage/carros/'.$InspeccionVehicular->detallesGenerales->recepcionesVehiculares[0]->Carro,$x1, $y1, $w, $h); // 'F' = relleno
    $pdf->SetXY($x1,$y1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w,5,utf8_decode('Daños Visibles:'),'',1,"L",FALSE);
    
    $x1+=$w+2;
    $w=210-$x1-5;
    $pdf->RoundedRect($x1, $y1, $w, $h, 2, 'D'); // 'F' = relleno
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,5,utf8_decode('Indicaciones Cliente:'),'',1,"L",FALSE);
    $pdf->SetXY($x1,$y1+1);
    $pdf->SetFont('Arial','',8);
    $text=utf8_decode($InspeccionVehicular->detallesGenerales->Indicaciones_cliente);
    $pdf->MultiCell($w, 3.5, "                                               ".$text, 0, "L", false);
     $pdf->SetDrawColor(0,0,0);
    $y1+=$h+1;
    $x1=$x;
    $w=200;
    $h=10;
    setbg($pdf,1);
    $pdf->RoundedRect($x1, $y1, $w, $h, 2, 'F'); // 'F' = relleno
    $pdf->SetFont('Arial','B',9);
    
    $w=40;
    $e=20;
    $x1=$x+$e; 
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,utf8_decode('Requiere Atencion Inmediata'),'',1,"C",FALSE);
    $x1+=$w+$e; 
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,utf8_decode('Puede Requerir Atencion Futura'),'',1,"C",FALSE);
    $x1+=$w+$e; 
    $pdf->SetXY($x1,$y1);
    $pdf->Cell($w,$h,utf8_decode('Inspeccionada y Esta Bien Ahora'),'',1,"C",FALSE);

    $x1=$x;
    $x1+=15;
    $y2=$y1+5;
    
    $pdf->SetFillColor(255, 0, 0); // rojo
    $pdf->Rect($x1-2, $y2-2, 4,4, 'F');
    
    $x1+=56;
    $pdf->SetFillColor(255, 255, 0); // amarillo
    $pdf->Polygon([$x1, $y2+2, $x1+2, $y2-2, $x1+4, $y2+2], 'F'); 
    
    $x1+=60;
    $pdf->SetFillColor(0,255, 0); // verde
    $pdf->Circle($x1, $y2, 2, 'F');
}
function step22($pdf,$x,$y,$w,$h,$InspeccionVehicular){
        $elements=$InspeccionVehicular->revisionLucesEspias;
        $elements1=$InspeccionVehicular->liquidos;
        $elements2=$InspeccionVehicular->mangueras;
        $elements3=$InspeccionVehicular->bandas;
        $elements4=$InspeccionVehicular->filtros;
        $elements5=$InspeccionVehicular->llantas;
        $elements6=$InspeccionVehicular->seguridad;
        $divarray=[
            [
                65,
                [
                    ['REVISION DE LUCES ESPIAS','title'],
                    ['Codigo(s):', $elements->codigo??'',3],
                    [$elements->notas??'','notas',14],
                    ['LIQUIDOS','title',5],
                    ['CONDICION', 'divlabel2',0,0,51],
                    ['OK', 'divlabel2',0,51,6],
                    ['LLENO', 'divlabel2',6,59,6],
                    ['Aceite de Motor:','printliquidos', $elements1->aceite_motor_ok??'', $elements1->aceite_motor??'', $elements1->aceite_motor_lleno??''],
                    ['Transmisión:','printliquidos', $elements1->transmision_ok??'', $elements1->transmision??'', $elements1->transmision_lleno??''],
                    ['Diferencial: FRENTE/TRASERO:','printliquidos', $elements1->diferencial_frente_trasero_ok??'', $elements1->diferencial_frente_trasero??'', $elements1->diferencial_frente_trasero_lleno??''],
                    ['Refrigerante:','printliquidos', $elements1->refrigerante_ok??'', $elements1->liquido_refrigerante??'', $elements1->refrigerante_lleno??''],
                    ['Frenos:','printliquidos', $elements1->frenos_ok??'', $elements1->frenos??'', $elements1->frenos_lleno??''],
                    ['Dirección Hidráulica:','printliquidos', $elements1->direccion_hidraulica_ok??'', $elements1->direccion_hidraulica??'', $elements1->direccion_hidraulica_lleno??''],
                    ['Limpiaparabrisas:','printliquidos', $elements1->limpiaparabrisas_ok??'', $elements1->limpiaparabrisas??'', $elements1->limpiaparabrisas_lleno??'',3],
                    [$elements1->liquido_notas??'','notas'],
                ]
            ],
            [
                50,[
                    ['MANGUERAS','title'],
                    ['Refrigerante:', $elements2->refrigerante??''],
                    ['Direccion/Aire Acondic:', $elements2->direccion_aire_acondicionado??''],
                    ['Calefaccion:', $elements2->calefaccion??''],
                    ['BANDAS','title'],
                    ['Accesorios:', $elements3->accesorios??''],
                    ['Dirección Hidráulica:', $elements3->bandas_direccion_hidraulica??''],
                    ['Alternador/A.Acondic:', $elements3->alternador_aire_acondicionado??''],
                    ['FILTROS','title'],
                    ['Aire:', $elements4->aire??''],
                    ['Combustible:', $elements4->combustible??''],
                    ['Aceite:', $elements4->aceite??''],
                    [$elements4->filtro_notas??'','notas'],
                ]
            ],
            [
                61,[
                    ['LLANTAS','title',5],
                    ['PATRON DE DESGASTE/DAÑO', 'divlabel2',0,0,46,'L'],
                    ['PRESION', 'divlabel2',6,46,15],
                    ['I. Delantera:','printllantasrow', $elements5->izquierda_delantera??'', $elements5->izquierda_delantera_presion??''],
                    ['I. Trasera:','printllantasrow', $elements5->izquierda_trasera??'', $elements5->izquierda_trasera_presion??''],
                    ['D. Delantera:','printllantasrow', $elements5->derecha_delantera??'', $elements5->derecha_delantera_presion??''],
                    ['D. Trasera:','printllantasrow', $elements5->derecha_trasera??'', $elements5->derecha_trasera_presion??''],
                    ['Refaccion:','printllantasrow', $elements5->refaccion??'', $elements5->refaccion_presion??'',3],
                    ['EL DESGASTE DE NEUMATICOS INDICA QUE:', 'divlabel2',6,0,61,'L'],
                    ['Se Necesita Alineacion y Balanceo:', $elements5->alineacion_balanceo??''],
                    ['MANGUERAS','title'],
                    ['Freno de Emergencia:', $elements6->frenos_emergencia??'',4],
                    ['LIMPIAPARABRISAS', 'divlabel2',7,0,61,'C'],
                    ['Izq./Der.:',$elements6->limpiaparabrisas_izquierdo_derecho??'',0,2],
                    ['Trasero.:',$elements6->limpiaparabrisas_trasero??'',5,2,1],
                    [$elements6->seguridad_notas??'','notas'],
                ]
            ]
        ];
        setbg($pdf,2);
        $pdf->RoundedRect($x, $y, $w, $h, 2, 'F'); // 'F' = relleno
        setbg($pdf,0);
        $x+=2;
        $pdf->Rect($x, $y+7, $w-4, $h-9,'F'); // 'F' = relleno
        
        $x1=$x+5;
        $y1=$y+1;
        $w1=7;
        $h1=5;
        $pdf->RoundedRect($x1, $y1, $w1, $h1, 2, 'F'); // 'F' = relleno
        setcl($pdf,2);

        $x1+=$w1+2;
        $pdf->SetXY($x1,$y1);
        $pdf->Cell($w-$x1,$h1,"26 PUNTOS - INSPECCION DE VEHICULO",0,1,"L",FALSE);
        $x1=$x+5;
        $y1+=$h1+3;
        $wdiv=65;
        $y2=$y1;
        foreach ($divarray as $divs){
             $wdiv=$divs[0];
            foreach ($divs[1] as $div){
                if($div[1]=='title'){
                    printsubtitle($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??8;
                }else if($div[1] =='increment'){
                    $y1+=$div[0];
                }else if($div[1] =='subtitle'){
                    printsubsubtitle($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??4;
                }else if($div[1] =='notas'){
                    printnotas($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??0;
                }else if($div[1] =='divlabel'){
                    $pdf->SetFont('Arial','B',6);
                    setcl($pdf,1);
                    $suma=($div[4]??0) * ($wdiv/$divicion);
                    $pdf->SetXY($x1+$suma,$y1);
                    $divicion=$div[3]??1;
                    $pdf->Cell($wdiv/$divicion,4,utf8_decode($div[0]),0,1,"C",FALSE);
                    $y1+=$div[2]??0;
                }else if($div[1] =='divlabel2'){
                    $pdf->SetFont('Arial','B',6);
                    setcl($pdf,1);
                    $pdf->SetXY($x1+$div[3],$y1);
                    $pdf->Cell($div[4],4,utf8_decode($div[0]),0,1,$div[5]??"C",FALSE);
                    $y1+=$div[2]??0;
                }else if($div[1] =='printliquidos'){
                    printliquidosrow($pdf,$x1,$y1,$div[3],$div[2],$div[4],$div[0],$wdiv);
                    $y1+=$div[5]??5;
                }else if($div[1] =='printllantasrow'){
                    printllantasrow($pdf,$x1,$y1,$div[2],$div[3],$div[0],$wdiv);
                    $y1+=$div[4]??5;
                }else{
                    $divicion=$div[3]??1;
                    $suma=($div[4]??0) * ($wdiv/$divicion);
                    printbuttons($pdf,$x1+$suma,$y1,$div[1],$div[0],$wdiv/$divicion);
                    if(!empty($div[2])){
                        $y1+=$div[2];
                    }else{
                        $y1+=$div[2]??5;
                    }
                }
            }
            $x1+=$wdiv+5;
            $y1=$y2;
        }
}
function step3($pdf,$x,$y,$w,$h,$InspeccionVehicular){
        setbg($pdf,3);
        $pdf->RoundedRect($x, $y, $w, $h, 2, 'F');
        
        setbg($pdf,0);
        $pdf->Rect($x+2, $y+7, $w-4, $h-9,'F');

        $x1=$x+5;
        $y1=$y+1;
        $w1=7;
        $h1=5;

        $pdf->RoundedRect($x1, $y1, $w1, $h1, 2, 'F');
        setcl($pdf,2);

        $x1+=$w1+2;
        $w1=100;
        $pdf->SetXY($x1,$y1);
        $pdf->Cell($w1,$h1,"57 PUNTOS - INSPECCION DE VEHICULO",0,1,"L",FALSE);
        $pdf->SetFont('Arial','B',9);
        $x1+=$w1+1;
        $pdf->SetXY($x1,$y1);
        $pdf->Cell($w-$x1,$h1,"(Incluye todos los anteriores)",0,1,"L",FALSE);

        $x1=$x+5;
        $y1+=$h1+3;
        $wdiv=65;
        $y2=$y1;
        $elements=$InspeccionVehicular->afinacionMotor;
        $elements1=$InspeccionVehicular->trenTransmision;
        $elements2=$InspeccionVehicular->electrico;
        $elements3=$InspeccionVehicular->suspencionDireccion;
        $elements4=$InspeccionVehicular->frenos;
        $elements5=$InspeccionVehicular->escape;
        $divarray=[
            [65,[
                ['SUSPENCION/DIRECCION','title'],
                ['Amortiguadores/Suspensión:', $elements3->amortiguadores_suspencion??''],
                ['Juntas de Dirección/Rótulas:', $elements3->juntas_direccion_rotulas??'',3],
                [ $elements3->suspencion_notas??'','notas',14],
                ['TREN DE TRANSMISÓN','title'],
                ['Filtro de Transmisión:',$elements1->filtro_transmison??''],
                ['Unión de la Transmisión/Clutch:',$elements1->union_transmision_clutch??''],
                ['Eje de Tracción y Juntas Homocinéticas:',$elements1->eje_traccion_juntas_homocineticas??''],
                ['Eje de Transmisión y Juntas Universales:',$elements1->eje_transmision_juntas_universales??''],
                ['Rodamientos de Rueda:',$elements1->rodamientos_rueda??''],
                ['Transmisón:',$elements1->tren_transmision??'',0,2],
                ['Clutch:',$elements1->clutch??'',3,2,1],
                [$elements1->tren_notas??'','notas'],
                ]
            ],
            [60,[
                ['ELECTRICO','title'],
                ['Sistema de Carga/Bateria:', $elements2->sistema_carga_bateria??''],
                ['Cables/Conexiones/Fusibles:', $elements2->cables_conexiones_fusibles??'',4],
                ['LUCES', 'subtitle',6],
                ['Freno/Reversa:', $elements2->reversa_frenos??''],
                ['Intermitentes:', $elements2->intermitentes??'',3],
                ['FAROS', 'divlabel',0,2],
                ['CUARTOS', 'divlabel',6,2,1],
                ['IZQ.:',$elements2->faro_izquierda??'',0,2],
                ['IZQ.:',$elements2->cuarto_izquierda??'',5,2,1],
                ['DER.:',$elements2->faro_derecha??'',0,2],
                ['DER.:',$elements2->cuarto_derecha??'',3,2,1],
                ['DIRECCIONALES', 'divlabel',6],
                ['I. DEL:',$elements2->direccionales_izquierda_delantera??'',0,2],
                ['D. DEL.:',$elements2->direccionales_derecha_delantera??'',5,2,1],
                ['I. TRA.:',$elements2->direccionales_izquierda_trasera??'',0,2],
                ['D. TRA:',$elements2->direccionales_derecha_trasera??'',4.5,2,1],
                ['AFINACION DE MOTOR','title'],
                ['Tapa del Distribuidor/Bujías/Cables:', $elements->tapa_distribuidor_bujias_cables??''],
                ['Fuel Injection:', $elements->fuel_injection??''],
            ]],[
                51,[
                    ['FRENOS','title',6],
                    ['PASTILLAS', 'divlabel',0,2],
                    ['ROTORES', 'divlabel',6,2,1],
                    ['I. DEL:',$elements4->pastillas_izquierda_delantera??'',0,2],
                    ['I. DEL:',$elements4->rotores_izquierda_delantera??'',5,2,1],
                    ['D. DEL.:',$elements4->pastillas_derecha_delantera??'',0,2],
                    ['D. DEL.:',$elements4->rotores_derecha_delantera??'',5,2,1],
                    ['I. TRA.:',$elements4->pastillas_izquierda_trasera??'',0,2],
                    ['I. TRA.:',$elements4->rotores_izquierda_trasera??'',5,2,1],
                    ['D. TRA:',$elements4->pastillas_derecha_trasera??'',0,2],
                    ['D. TRA:',$elements4->rotores_derecha_trasera??'',4,2,1],
                    ['ROTORES', 'divlabel',5,1],
                    ['I. DEL:',$elements4->pinzas_cilindros_rueda_izquierda_delantera??'',0,2],
                    ['D. DEL:',$elements4->pinzas_cilindros_rueda_derecha_delantera??'',5,2,1],
                    ['I. TRA:',$elements4->pinzas_cilindros_rueda_izquierda_trasera??'',0,2],
                    ['D. TRA:',$elements4->pinzas_cilindros_rueda_derecha_trasera??'',6,2,1],
                    ['ESCAPE','title'],
                    ['Mofle/Convertidor Catlítico:',$elements5->mofle_convertidor_catlitico??''],
                    ['Sensores/Soportes/tubos:',$elements5->sensores_soporte_tubos??'',4],
                    [$elements5->escape_notas??'','notas'],
                    
                ]
            ]
        ];
        
        
        foreach ($divarray as $divs){
             $wdiv=$divs[0];
            foreach ($divs[1] as $div){
                if($div[1]=='title'){
                    printsubtitle($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??8;
                }else if($div[1] =='increment'){
                    $y1+=$div[0];
                }else if($div[1] =='subtitle'){
                    printsubsubtitle($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??4;
                }else if($div[1] =='notas'){
                    printnotas($pdf,$x1,$y1,$wdiv,$div[0]);
                    $y1+=$div[2]??0;
                }else if($div[1] =='divlabel'){
                    $pdf->SetFont('Arial','B',6);
                    setcl($pdf,1);
                    $suma=($div[4]??0) * ($wdiv/$divicion);
                    $pdf->SetXY($x1+$suma,$y1);
                    $divicion=$div[3]??1;
                    $pdf->Cell($wdiv/$divicion,4,utf8_decode($div[0]),0,1,"C",FALSE);
                    $y1+=$div[2]??0;
                }else{
                    $divicion=$div[3]??1;
                    $suma=($div[4]??0) * ($wdiv/$divicion);
                    printbuttons($pdf,$x1+$suma,$y1,$div[1],$div[0],$wdiv/$divicion);
                    if(!empty($div[2])){
                        $y1+=$div[2];
                    }else{
                        $y1+=$div[2]??5;
                    }
                }
            }
            $x1+=$wdiv+5;
            $y1=$y2;
        }

    }
    function printbuttons($pdf,$x,$y,$estado,$label,$w,$line=true){
        $x+=2;
        $pdf->SetFillColor(255, 0, 0); // rojo
        $pdf->Rect($x-2, $y-2, 4,4, 'F');
        // $pdf->Circle($x, $y, 2, 'F');
        if($estado==1){
            printok($pdf,$x,$y);
        }
        $x+=2;
        $pdf->SetFillColor(255, 255, 0); // amarillo
        $pdf->Polygon([$x, $y+2, $x+2, $y-2, $x+4, $y+2], 'F'); 
        if($estado==2){
            printok($pdf,$x+2,$y+0.5);
        }
        $x+=6;
        $pdf->SetFillColor(0,255, 0); // verde
        $pdf->Circle($x, $y, 2, 'F');
        if($estado==3){
            printok($pdf,$x,$y);
        }
        $x+=2;
        $pdf->SetXY($x,$y-3);
        setcl($pdf,1);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell($w-10,6,utf8_decode($label),0,1,"L",FALSE);
        if($line){
            $pdf->SetLineWidth(0.2);
            $pdf->Line($x-12, $y+2.2, $x+$w-12, $y+2.2);
        }

    
    }
    function printok($pdf,$x,$y){
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetLineWidth(0.5);
        $pdf->Line($x-1, $y, $x, $y+1);
        $pdf->Line($x, $y+1, $x+1, $y-1.5);
    }
    function printsubtitle($pdf,$x,$y,$w,$label){
        setbg($pdf,3);
        $pdf->RoundedRect($x, $y, $w, 5, 2, 'F'); // 'F' = relleno
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Arial','B',10);
        setcl($pdf,2);
        $pdf->Cell($w,5,utf8_decode($label),0,1,"L",FALSE);
    }
    function printsubsubtitle($pdf,$x,$y,$w,$label){
        setbg($pdf,4);
        $pdf->RoundedRect($x, $y, $w, 3, 0.8, 'F'); // 'F' = relleno
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Arial','B',7);
        setcl($pdf,2);
        $pdf->Cell($w,3,utf8_decode($label),0,1,"C",FALSE);
    }
    function printliquidosrow($pdf,$x,$y,$estado,$ok,$lleno,$label,$w){
        $x1=$x;
        printbuttons($pdf,$x1,$y,$estado,$label,$w-18,false);
        $x1+=($w-14);
        $pdf->SetLineWidth(0.2);
        $pdf->RoundedRect($x1, $y-2, 6, 4, 1.2, 'D');
        if($ok){
            printok($pdf,$x1+3,$y);
        }
        $x1+=8;
        $pdf->SetLineWidth(0.2);
        $pdf->RoundedRect($x1, $y-2, 6, 4, 1.2, 'D');
        if($lleno){
            printok($pdf,$x1+3,$y);
        }
        $pdf->SetLineWidth(0.2);
        $pdf->Line($x, $y+2, $x+$w, $y+2);
    }
    function printllantasrow($pdf,$x,$y,$estado,$presion,$label,$w){
        $x1=$x;
        printbuttons($pdf,$x1,$y,$estado,$label,$w-20,false);
        $x1+=($w-15);
        $pdf->SetLineWidth(0.2);
        $pdf->RoundedRect($x1, $y-2, 15, 4, 1.2, 'D');
        $pdf->SetFont('Arial','B',7);
        $pdf->SetXY($x1,$y-2);
        $pdf->Cell(15,4,$presion,0,1,"R",FALSE);
        $pdf->Line($x, $y+2, $x+$w, $y+2);
    }
    function printnotas($pdf,$x,$y,$w,$text){
        setbg($pdf,3);
        $pdf->RoundedRect($x, $y, 12, 4, 0.5, 'F'); // 'F' = relleno
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Arial','B',7);
        setcl($pdf,2);
        $pdf->Cell(12,4,"NOTAS:",0,1,"C",FALSE);
        $pdf->SetLineWidth(0.2);
        $pdf->Line($x+13, $y+4, $x+$w, $y+4);
        for($i=2;$i<=3;$i++){
            $pdf->Line($x, $y+(4*$i), $x+$w, $y+(4*$i));
        }
        $pdf->SetXY($x,$y);
        setcl($pdf,3);
        $pdf->SetFont('Arial','',7);
        $pdf->MultiCell($w, 4, "                  ".$text, 0, "L", false);


    }
    function setbg($pdf,$color){
    switch ($color){
        case 1:
            $pdf->SetFillColor(190, 195, 191);
            break;
        case 2:
            $pdf->SetFillColor(193, 89, 64);
            break;
        case 3:
            $pdf->SetFillColor(99, 91, 130);
            break;
        case 4:
            $pdf->SetFillColor(123, 166, 231);
            break;
        default:
            $pdf->SetFillColor(255, 255, 255);
            break;
    }
    }
    function setcl($pdf,$color){
        switch ($color){
            case 1:
                $pdf->SetTextColor(60,40,60);
                break;
            case 2:
                $pdf->SetTextColor(255, 255, 255);
                break;
            default:
                $pdf->SetTextColor(0, 0, 0);
                break;
        }
    }
?>