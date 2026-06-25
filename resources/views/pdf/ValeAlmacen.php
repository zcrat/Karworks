<?php

include("mc_table.php");
define('FPDF_FONTPATH', 'font/');
 use Carbon\Carbon;
$pdf = new PDF_MC_Table();
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage('P','A4');

 
    $datos=$element->detallesGenerales;
    $vehiculo=$datos->vehiculo;
    $pdf->SetLineWidth(0.8); 
    $pdf->rect(0,0,210,108,'');
    $pdf->SetLineWidth(0.2); 
    $tw = 210;
    $th = 108;
    $gapx=2;
    $gapy=2;
    $mx = 10;
    $my = 10;
    $color_azul = [0, 102, 204];
    $color_blanco = [255, 255, 255];
    $color_negro = [0, 0, 0];
    $color_red = [255, 0, 0];
    setRGBFill($pdf,$color_azul);

    $text=12;
    $pdf->SetFont('Arial','B',$text);
    setRGBColor($pdf,$color_blanco);

    $px = $mx;
    $py = $my;
    
    $w=150;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->Cell($w,$h,"O R D E N    D E    C O M P R A   /   V A L E    D E    A L M A C E N",1,1,"C",TRUE);

    setRGBColor($pdf,$color_negro);
    $px+=$w+$gapx;

    $text=8;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $w=$tw-$mx-$px;
    $h=$text/2;
    $pdf->Cell($w,$h,"FOLIO",1,1,"C",FALSE);
    $py+=$h;
    $pdf->SetXY($px,$py);
    $text=12;
    $h=$text/2;

    $pdf->SetFont('Arial','B',$text);
    setRGBColor($pdf,$color_red);

    $pdf->Cell($w,$h,"No#   ".str_pad($element->id, 5, "0", STR_PAD_LEFT)." - ".str_pad($element->num, 2, "0", STR_PAD_LEFT),1,1,"C",FALSE);

    $px = $mx;
    $py = $my+6+2;//6 es del margen azul
    $text=6;
    $w=7;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    setRGBColor($pdf,$color_negro);
    $pdf->Cell($w,$h,"Fecha:",0,1,"C",false);
    $pdf->SetFont('Arial','',$text);
    $px+=$w;
    $pdf->SetXY($px,$py);
    $w=20;
    $pdf->Cell($w,$h,$element->created_at,'B',1,"C",false);
    
    $px+=$w+$gapx;
    $pdf->SetFont('Arial','B',$text);
    $pdf->SetXY($px,$py);
    $w=6;
    $pdf->Cell($w,$h,"Para:",0,1,"C",false);
    $px+=$w;
    $pdf->SetFont('Arial','B',$text);
    $pdf->SetXY($px,$py);
    $w=20;
    $pdf->SetFont('Arial','B',$text);
    $pdf->Cell($w,$h,$element->Destino??'','B',1,"C",false);

    $pdf->SetXY($mx-0.5,$py+5);
    $pdf->SetFont('Arial','B',$text);
    setRGBColor($pdf,$color_negro);
    $pdf->Cell(10,$h,"Tecnico:",0,1,"l",false);
    $pdf->SetXY($mx-0.5+10,$py+5);
    $pdf->SetFont('Arial','',$text);
    $pdf->Cell(46,$h,$datos->RecepcionVehicular->tecnico->nombre??'No Definido','B',1,"l",false);
    $py = $my+6+1;
    $px+=$gapx;
    $px+=$w;
    $w=(160-$px)/3;
    $text=8;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Cell($w,$h,"PROVEEDOR",1,1,"C",false);
    
    $text=6;
    $py+=$h;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Multicell($w,$h,"Pedido  -  Hora \n ",1,"C",false);
    
    $px+=$w;
    $py = $my+6+1;
    $text=8;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Cell($w,$h,"ALMACEN",1,1,"C",false);
    
    $text=6;
    $py+=$h;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Multicell($w,$h,"Pedido  -  Hora \n ",1,"C",false);
    
    $px+=$w;
    $py = $my+6+1;
    $text=8;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Cell($w,$h,"TECNICO",1,1,"C",false);
    
    $text=6;
    $py+=$h;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Multicell($w,$h,"Pedido  -  Hora \n ",1,"C",false);
    
     setRGBColor($pdf,$color_azul);
    $px+=$w+$gapx;
    $py=21;
    $w=$tw-$mx-$px;
    $h=$text/2;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$text);
    $pdf->Multicell($w,$h,"Por Favor Realice El Pedido \nPara Lo Siguiente",0,"C",false);

     setRGBColor($pdf,$color_negro);
    

    $px = $mx;
    //$py+=($h*2)+1;
    $py=$mx+6+9+1+1;
    $w=13;
    $h=60;
    if($datos->modulo->factura_emisor_id == 4){
        $pdf->Image('img/'.$datos->modulo->FacturaEmisor->logotipo_emisor,$px,$py+(($h-$w)/2),$w,$w);
    }else{
        $pdf->Image('img/vales/'.$datos->modulo->FacturaEmisor->logotipo_emisor,$px,$py,$w,$h);
    }
    $px+=$w+$gapx;
    $pdf->SetFont('Arial','B',$h/8);
    $incremento = $h/11;
    printrow("CLAVE","CANT"," DESCRIPCION","","","",$pdf,$px,$py,$tw,$incremento);
    $pdf->SetFont('Arial','',$h/8);
    $index = 0;
    $conceptos = $element->Conceptos;
    $py+=$incremento;
    for($i=$incremento;$i<$h-$incremento;$i+=$incremento){
        $datos1 = $conceptos[$index] ?? (object) ['Descripcion' => '', 'Cantidad' => ''];
        
        printrow("",$datos1->Cantidad ,$datos1->Descripcion,"","","",$pdf,$px,$py,$tw,$incremento);
        $index++;
        $py+=$incremento;
    }
    $py += $gapy;
    $px = $mx;
    $w=20;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"#Economico",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,$vehiculo->no_economico,'LRB',1,"C",false);
    $px += $w;
    $w=15;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"Placas",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,$vehiculo->placas,'LRB',1,"C",false);
    $px += $w;
    $w=20;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"Ord. Ser.",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,$datos->OrdenServicio,'LRB',1,"C",false);
    $px += $w;
    $w=43;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,utf8_decode("Marca-Modelo-Año-Motor-Serie"),'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/10);
    $pdf->MultiCell($w,3,$vehiculo->marca->nombre."-".$vehiculo->modelo->nombre."-".$vehiculo->anio."-".$element->Tipo_Motor."\n".$vehiculo->vim ,'LRB' ,"C",false);
    $px += $w;
    $w=12;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"KM",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,$datos->kilometraje_entrada,'LRB',1,"C",false);
    $px += $w;
    $w=30;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"Autorizado",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,$element->Autorizado->name,'LRB',1,"C",false);
    $px += $w;
    $w=25;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"Almacen",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,"",'LRB',1,"C",false);
    $px += $w;
    $w=25;
    $pdf->SetXY($px,$py);
    $pdf->SetFont('Arial','B',$h/8);
    $pdf->Cell($w,3,"Recibido",'LTR',1,"C",false);
    $pdf->SetXY($px,$py+3);
    $pdf->SetFont('Arial','',$h/8);
    $pdf->Cell($w,6,"",'LRB',1,"C",false);
    // fin y entrega del pdf 
$pdf->Output();
exit;

function printrow($t1,$t2,$t3,$t4,$t5,$t6,$pdf,$px,$py,$tw,$incremento){
        $x1=$px;
        $pdf->SetXY($x1,$py);
        $w1=28;
        $pdf->Cell($w1,$incremento,utf8_decode($t1),1,1,"C",false);
        $x1=$x1+$w1;
        $pdf->SetXY($x1,$py);
        $w1=9;
        $pdf->Cell($w1,$incremento,utf8_decode($t2),1,1,"C",false);
        $x1+=$w1;
        $w1=$tw-$x1-54-10;
        $pdf->SetXY($x1,$py);
        $pdf->Cell($w1,$incremento,utf8_decode($t3),1,1,"C",false);
        $x1+=$w1;
        $w1=18;
        $pdf->SetXY($x1,$py);
        $pdf->Cell($w1,$incremento,utf8_decode($t4),1,1,"C",false);
        $x1+=$w1;
        $w1=18;
        $pdf->SetXY($x1,$py);
        $pdf->Cell($w1,$incremento,utf8_decode($t5),1,1,"C",false);
        $x1+=$w1;
        $w1=18;
        $pdf->SetXY($x1,$py);
        $pdf->Cell($w1,$incremento,utf8_decode($t6),1,1,"C",false);
}
function setRGBFill($pdf, $rgb) {
    $pdf->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
}
function setRGBColor($pdf, $rgb) {
    $pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
}
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
        $conversion = $fmatch[5]; 

        $positive = true; 
        if ($value < 0) { 
            $positive = false; 
            $value  *= -1; 
        } 
        $letter = $positive ? 'p' : 'n'; 

        $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
        switch (true) { 
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
                $prefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
                $suffix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
                $cprefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
                $csuffix = $signal; 
                break; 
            case $flags['usesignal'] == '(': 
            case $locale["{$letter}_sign_posn"] == 0: 
                $prefix = '('; 
                $suffix = ')'; 
                break; 
        } 
        if (!$flags['nosimbol']) { 
            $currency = $cprefix . 
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
                        $csuffix; 
        } else { 
            $currency = ''; 
        } 
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

        $value = number_format($value, $right, $locale['mon_decimal_point'], 
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
        $value = @explode($locale['mon_decimal_point'], $value); 

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
        if ($left > 0 && $left > $n) { 
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
        } 
        $value = implode($locale['mon_decimal_point'], $value); 
        if ($locale["{$letter}_cs_precedes"]) { 
            $value = $prefix . $currency . $space . $value . $suffix; 
        } else { 
            $value = $prefix . $value . $space . $currency . $suffix; 
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