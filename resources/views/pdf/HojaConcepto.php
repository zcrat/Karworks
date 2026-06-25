<?php

include("mc_table.php");
define('FPDF_FONTPATH', 'font/');
 use Carbon\Carbon;
$pdf = new PDF_Mc_Table();
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);

    $index=1;
    do{
    $pdf->AddPage('L');
 
    $datos=$presupuesto->detallesGenerales;
    $vehiculo=$datos->vehiculo;

    // $pdf->Image('img/logos-empresas/'.$presupuesto->detallesGenerales->Empresa->logo,140,5,50,20);
    //$pdf->Image('img/logos-empresas/'.$presupuesto->logo,140,10,-200);
    $pdf->Image('img/'.$datos->modulo->FacturaEmisor->logotipo_emisor,217,5,70,15);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',16);
    $pdf->SetXY(10,5);
    $pdf->Cell(70,15,"HOJA DE CONCEPTOS",0,1,"L",false);
    $pdf->Rect(85,5,40,15);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetXY(85,5);
    $pdf->Cell(40,5,"RECIBIDO",0,1,"C",false);
    $pdf->SetXY(85,10);
    $pdf->Cell(40,5,"Fecha:",0,1,"L",false);
    $pdf->SetXY(95,10);
    $pdf->Cell(25,5,Carbon::parse($datos->fecha_entrada)->toDateString(),0,1,"L",false);
    $pdf->SetXY(85,15);
    $pdf->Cell(40,5,"Hora:",0,1,"L",false);
    $pdf->SetXY(95,15);
    $pdf->Cell(25,5,Carbon::parse($datos->fecha_entrada)->toTimeString(),0,1,"L",false);
    $pdf->Rect(172,5,40,15);
    $pdf->SetXY(172,5);
    $pdf->Cell(40,5,"SALIDA",0,1,"C",false);
    $pdf->SetXY(172,10);
    $pdf->Cell(40,5,"Fecha:",0,1,"L",false);
    $pdf->SetXY(182,10);
    $fechaSalidadate = $datos->Fecha_salida ? Carbon::parse($datos->Fecha_salida)->toDateString() :( $datos->DateEntregado ? Carbon::parse($datos->DateEntregado->fecha)->toDateString() : "");
    $fechaSalidahora = $datos->Fecha_salida ? Carbon::parse($datos->Fecha_salida)->toTimeString() : ($datos->DateEntregado ? Carbon::parse($datos->DateEntregado->fecha)->toTimeString() : "");
    $pdf->Cell(25, 5, $fechaSalidadate, 0, 1, "L", false);
    $pdf->SetXY(172,15);
    $pdf->Cell(40,5,"Hora:",0,1,"L",false);
    $pdf->SetXY(182,15);
    $pdf->Cell(25,5,$fechaSalidahora,0,1,"L",false);
    $tamaniox=277;
    $tamanioy=8;
    $fonttitlle=6;
    $fonttext=8;
    $espaciadotext=5;
    $diferencia=($fonttext-$fonttitlle)/2;
    $y=22;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy*2); 
    $pdf->Line($x, $y+$tamanioy, $tamaniox+$x, $y+$tamanioy); 
    $t=90;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->cell($t,$fonttitlle/2,utf8_decode("Empresa:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+10, $y);
    $pdf->MultiCell($t-10,$fonttext/2,$datos->Empresa->nombre,0,"L",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Zona:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->zona->nombre,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Ord. Servicio:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->OrdenServicio,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Ord. Seguimiento:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->OrdenSeguimiento,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Folio:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$presupuesto->Folio,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Tecnico:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$presupuesto->Tecnico,0,1,"L");
    
    $y+=$tamanioy;
    $x=10;
    $t=15;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Anio:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->anio,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=70;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Multicell($t,$fonttitlle-2,utf8_decode("Modelo:\nMarca:"),0,"L",false);
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+10, $y);
    $pdf->MultiCell($t-10,$fonttext-4,$vehiculo->marca->nombre."\n".$vehiculo->modelo->nombre,0,"L",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Color:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->color->nombre,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Placas:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->placas,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("Kilometraje:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->kilometraje_entrada,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("#Economico:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->no_economico,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
     $pdf->cell($t,$fonttitlle/2,utf8_decode("VIM:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->vim,0,1,"L");


    $y+=$tamanioy+2;
    $tamanioy=6;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy);
    $t=6;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("NO"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t; 
    $t=18;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y+2);
    $pdf->MultiCell($t,($tamanioy/3),utf8_decode("FECHA\nAPLICACION."),0,"C",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t; 
    $t=9;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("REP"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t; 
    $t=9;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("REMP"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t; 
    $t=26;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("CLAVE"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=120;
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("DESCRIPCION"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    
    $x+=$t;
    $t=71;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy/2,utf8_decode("Costos ($)"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $pdf->Line($x, $y+($tamanioy/2), $t+$x, $y+($tamanioy/2));

    $y+=($tamanioy/2);
    $t=13;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy/2,utf8_decode("PARTES"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy/2);
    $x+=$t;
    $t=15;
    $pdf->SetFont('Arial','B',$fonttext-3);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($t,$tamanioy/4,utf8_decode("MANO \n DE OBRA"),0,"C",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy/2);
    $x+=$t;
    $t=15;
    $pdf->SetFont('Arial','B',$fonttext-4);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($t,$tamanioy/4,utf8_decode("SUB-CONTRATADOS"),0,"C",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy/2);
    $x+=$t;
    $t=13;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy/2,utf8_decode("OTROS"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy/2);
    $x+=$t;
    $t=15;
    $pdf->SetFont('Arial','B',$fonttext-3.5);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($t,$tamanioy/4,utf8_decode("SUBTOTAL\nCOSTOS"),0,"C",false);
    $x+=$t;
    $t=18;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y-($tamanioy/2));
    $pdf->MultiCell($t,$tamanioy/3,utf8_decode("PRECIO\nDE VENTA\n($)"),0,"C",FALSE);
    $x=10;
    $y+=$tamanioy/2;
    $incremento=5.7;
    if(count($conceptos)>20){
        $incremento=($incremento*20)/count($conceptos);
    }
    $pdf->SetFont('Arial','B',$incremento);
    $SUBTOTAL=0;
    $SUBTOTALVenta=0;
    $SUBTOTALES=[1=>0,2=>0,3=>0,4=>0];
    $EJESESTATUS=[1=>34,2=>43];
    $TAMANIOSSTATUS=[1=>9,2=>9];
    $EJESTIPOS=[1=>198,2=>211,3=>226,4=>241];
    $TAMANIOSTIPOS=[1=>13,2=>15,3=>15,4=>13];
    for($i=$y;$i<=175;$i+=$incremento){
        $x=10;
        $anchoCelda = 120; // Ancho definido en MultiCell
        $texto = empty($conceptos[$index-1])? ' ': $conceptos[$index-1]->descripcion ;
        $pdf->SetFont('Arial', 'B', $incremento+2);
        $anchoTexto = $pdf->GetStringWidth($texto); // Obtiene el ancho del texto
        $lineasNecesarias = ceil($anchoTexto / $anchoCelda);
        $incrementooriginal=$incremento;
        $incremento= $incremento*$lineasNecesarias;
        $pdf->Rect($x, $i, $tamaniox, $incremento);
        $pdf->SetXY($x, $i);
        $pdf->Cell(6,$incremento,utf8_decode($index),0,1,"C");
        $ts=[6,18,9,9,26,$anchoCelda,13,15,15,13,15];
        foreach($ts as $indice =>$t){
            $x+=$t;
            $pdf->Line($x, $i, $x, $i+$incremento);
        }
        if(!empty($conceptos[$index-1] )){
            $pdf->SetFont('Arial','B',$incrementooriginal+2);
            $con=$conceptos[$index-1];
            $costo=($con->partes ?? 0)+
                ($con->manoobra ?? 0)+
                ($con->subcontratados ?? 0)+
                ($con->otros ?? 0);
            $cantidad=($con->reparar ?? 0) + 
                ($con->remplazo ?? 0);
            $porcentajeconiva= $con->iva==1 ? 1.16 : 1 ;
            $pdf->SetXY(16, $i);
            $pdf->Cell(18,$incremento,$con->fecha,0,1,"C");
            $pdf->SetXY($EJESESTATUS[1], $i);
            $pdf->Cell($TAMANIOSSTATUS[1],$incremento,$con->reparar??0,0,1,"C");
            $pdf->SetXY($EJESESTATUS[2], $i);
            $pdf->Cell($TAMANIOSSTATUS[2],$incremento,$con->remplazo??0,0,1,"C");
            $pdf->SetXY(52, $i);
            $pdf->Cell(26,$incremento,$con->clave,0,1,"C");
            $pdf->SetXY(77, $i);
            $pdf->MultiCell(120,$incrementooriginal,$con->descripcion??'',0,"C",FALSE);
            $pdf->SetXY($EJESTIPOS[1], $i);
            $pdf->Cell($TAMANIOSTIPOS[1],$incremento,amoneda($cantidad * $con->partes/$porcentajeconiva) ,1,1,"C");
            $pdf->SetXY($EJESTIPOS[2], $i);
            $pdf->Cell($TAMANIOSTIPOS[2],$incremento,amoneda($cantidad * $con->manoobra/$porcentajeconiva) ,1,1,"C");
            $pdf->SetXY($EJESTIPOS[3], $i);
            $pdf->Cell($TAMANIOSTIPOS[3],$incremento,amoneda($cantidad * $con->subcontratados/$porcentajeconiva) ,1,1,"C");
            $pdf->SetXY($EJESTIPOS[4], $i);
            $pdf->Cell($TAMANIOSTIPOS[4],$incremento,amoneda($cantidad * $con->otros/$porcentajeconiva) ,1,1,"C");
            $pdf->SetXY(254, $i);
            $pdf->Cell(15,$incremento,amoneda($cantidad * $costo/$porcentajeconiva),1,1,"C");
            $pdf->SetXY(269, $i);
            $pdf->Cell(18,$incremento,amoneda($cantidad * $con->venta),0,1,"C");

            $SUBTOTAL+=($cantidad * $costo/$porcentajeconiva);
            $SUBTOTALVenta+=$con->venta * $cantidad;
            $SUBTOTALES[1]+=($cantidad * $con->partes/$porcentajeconiva);
            $SUBTOTALES[2]+=($cantidad * $con->manoobra/$porcentajeconiva);
            $SUBTOTALES[3]+=($cantidad * $con->subcontratados/$porcentajeconiva);
            $SUBTOTALES[4]+=($cantidad * $con->otros/$porcentajeconiva);

        }
        $index++;
        $i+=$incremento-$incrementooriginal;
        $incremento=$incrementooriginal;
        $y=$i;
    }
    $y+=($incremento);
    $x=10;
    $incremento=8;
    $pdf->Rect($x, $y, $tamaniox, $incremento);
    $ts=[188,13,15,15,13,15];
    foreach($ts as $t){
        $x+=$t;
        $pdf->Line($x, $y, $x, $y+$incremento);
    }
    $x=10;
    $pdf->SetXY($x, $y);
    $t=188;
    $pdf->SetFont('Arial','B',$incremento*1.5);
    $pdf->Cell($t,$incremento,utf8_decode('AUTORIZACION'),0,1,"C");
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=13;
    $pdf->SetFont('Arial','B',$incremento); 
    $pdf->Cell($t,$incremento,utf8_decode('PARTES'),0,1,"C");
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=15;
    $pdf->SetFont('Arial','B',$incremento-2); 
    $pdf->MultiCell($t,$incremento/2,utf8_decode("MANO\nDE OBRA"),0,"C",FALSE);
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=15;
    $pdf->SetFont('Arial','B',$incremento-2);
    $pdf->MultiCell($t,$incremento/2,utf8_decode("SUB-CONTRATADOS"),0,"C",FALSE);
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=13;
    $pdf->SetFont('Arial','B',$incremento);
    $pdf->Cell($t,$incremento,utf8_decode('OTROS'),0,1,"C");
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=15;
    $pdf->SetFont('Arial','B',$incremento-2);
    $pdf->MultiCell($t,$incremento/2,utf8_decode("SUBTOTAL\nCOSTOS"),0,"C",FALSE);
    $x+=$t;
    $pdf->SetXY($x, $y);
    $t=18;
    $pdf->SetFont('Arial','B',$incremento);
    $pdf->MultiCell($t,$incremento/2,utf8_decode("PRECIO\nDE VENTA"),0,"C",FALSE);
    $y+=($incremento); 
    $y1=$y;
    $incremento=5.6;
    $multiplicador=[1,0.16,1.16];

    for($i=1;$i<=3;$i++){
        $x=168;
        $t=30;
        $pdf->Rect($x, $y, ($tamaniox-$x)+10, $incremento);
        $pdf->Line($x+30, $y, $x+30, $y+$incremento);

        $pdf->SetXY($x+30, $y);
        $pdf->Cell(13,$incremento,amoneda($SUBTOTALES[1]*$multiplicador[$i-1]),'R',1,"C");
        $pdf->SetXY($x+43, $y);
        $pdf->Cell(15,$incremento,amoneda($SUBTOTALES[2]*$multiplicador[$i-1]),'R',1,"C");
        $pdf->SetXY($x+58, $y);
        $pdf->Cell(15,$incremento,amoneda($SUBTOTALES[3]*$multiplicador[$i-1]),'R',1,"C");
        $pdf->SetXY($x+73, $y);
        $pdf->Cell(13,$incremento,amoneda($SUBTOTALES[4]*$multiplicador[$i-1]),'R',1,"C");
        $pdf->SetXY($x+86, $y);
        $pdf->Cell(15,$incremento,amoneda($SUBTOTAL*$multiplicador[$i-1]),'R',1,"C");
        $pdf->SetXY($x+101, $y);
        $pdf->Cell(18,$incremento,amoneda($SUBTOTALVenta*$multiplicador[$i-1]),'R',1,"C");
        $y+=($incremento); 
    }
    $pdf->SetXY(168, $y1);
    
    $pdf->MultiCell(30,$incremento,utf8_decode("SUBTOTAL\nIVA\nTOTAL"),0,"C",FALSE);
}while(count($conceptos) >=$index);

// $pdf->SetXY(110, $y+20);
// $pdf->SetTextColor(0,0,0);
// $pdf->MultiCell(60,8,"FIRMA DE AUTORIZACI0N","T","C",false); 

  
// fin y entrega del pdf 
$pdf->Output();
exit;

function amoneda($numero)
{
    $number = $numero;
    setlocale(LC_MONETARY, 'en_US.UTF-8');
    return money_formato('%.2n', $number);
}
function money_formato($format, $number) 
{ 
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
    if (setlocale(LC_MONETARY, 0) == 'C') { 
        setlocale(LC_MONETARY, ''); 
    } 
    $locale = localeconv(); 
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
    foreach ($matches as $fmatch) { 
        $value = floatval($number); 
        $flags = array( 
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
                           $match[1] : ' ', 
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
                           $match[0] : '+', 
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
        ); 
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
        $presupuestoonversion = $fmatch[5]; 

        $positive = true; 
        if ($value < 0) { 
            $positive = false; 
            $value  *= -1; 
        } 
        $letter = $positive ? 'p' : 'n'; 

        $prefix = $suffix = $presupuestoprefix = $presupuestosuffix = $signal = ''; 

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
        switch (true) { 
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
                $prefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
                $suffix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
                $presupuestoprefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
                $presupuestosuffix = $signal; 
                break; 
            case $flags['usesignal'] == '(': 
            case $locale["{$letter}_sign_posn"] == 0: 
                $prefix = '('; 
                $suffix = ')'; 
                break; 
        } 
        if (!$flags['nosimbol']) { 
            $presupuestourrency = $presupuestoprefix . 
                        ($presupuestoonversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
                        $presupuestosuffix; 
        } else { 
            $presupuestourrency = ''; 
        } 
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

        $value = number_format($value, $right, $locale['mon_decimal_point'], 
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
        $value = @explode($locale['mon_decimal_point'], $value); 

        $n = strlen($prefix) + strlen($presupuestourrency) + strlen($value[0]); 
        if ($left > 0 && $left > $n) { 
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
        } 
        $value = implode($locale['mon_decimal_point'], $value); 
        if ($locale["{$letter}_cs_precedes"]) { 
            $value = $prefix . $presupuestourrency . $space . $value . $suffix; 
        } else { 
            $value = $prefix . $value . $space . $presupuestourrency . $suffix; 
        } 
        if ($width > 0) { 
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
                     STR_PAD_RIGHT : STR_PAD_LEFT); 
        } 

        $format = str_replace($fmatch[0], $value, $format); 
    } 
    return $format; 
} 

?>
