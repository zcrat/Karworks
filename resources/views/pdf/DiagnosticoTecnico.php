<?php

include("mc_table.php");
define('FPDF_FONTPATH', 'font/');
 use Carbon\Carbon;
$pdf = new PDF_Mc_Table();

$pdf->AddPage();
 
    $datos=$presupuesto->detallesGenerales;
    // $pdf->Image('img/logos-empresas/'.$presupuesto->detallesGenerales->Empresa->logo,140,5,50,20);
    //$pdf->Image('img/logos-empresas/'.$presupuesto->logo,140,10,-200);
    $pdf->Image('img/'.$datos->modulo->FacturaEmisor->logotipo_emisor,140,5,70,15);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',16);
    $pdf->SetX(10);
    $pdf->Cell(190,10,"DIGNOSTICO TECNICO",0,1,"L",false);
    $pdf->SetXY(80,10);
    $pdf->Cell(50,10,$presupuesto->Folio,0,1,"C",false);
    $tamaniox=190;
    $tamanioy=10;
    $fonttitlle=6;
    $fonttext=10;
    $espaciadotext=5;
    $diferencia=($fonttext-$fonttitlle)/2;
    $y=35;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy); 
    $t=70;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Empresa:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+10, $y+1);
    $pdf->Multicell($t-10,($tamanioy-$diferencia)/2,$datos->Empresa->nombre,0,"L",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Fecha:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,Carbon::parse($datos->fecha_entrada)->toDateString(),0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Zona:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->zona->nombre,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $vehiculo=$datos->vehiculo;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Economico:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->no_economico,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Año:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->anio,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $y+=$tamanioy;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy); 
    $t=50;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($t,$fonttitlle-2,utf8_decode("Modelo\nMarca:"),0,"L",false);
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+10, $y);
    $pdf->Multicell($t-10,$fonttext-5,$vehiculo->marca->nombre."\n".$vehiculo->modelo->nombre,0,"L",false);
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=50;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Vim:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->vim,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Placas:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->placas,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Color:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->color->nombre,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=30;
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Kilometraje:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+$espaciadotext, $y+$diferencia);
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->kilometraje_entrada,0,1,"L");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $y+=$tamanioy;
    $tamanioy=6;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy);
    $pdf->SetFont('Arial','',$fonttitlle);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttitlle,utf8_decode("Tecnico:"),0,1,"L");
    $pdf->SetFont('Arial','B',$fonttext);
    $pdf->SetXY($x+10, $y);
    $pdf->Cell($t-10,$fonttitlle,utf8_decode($presupuesto->Tecnico),0,1,"L");
    $y+=$tamanioy;
    $tamanioy=35;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy);
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$fonttext-2,utf8_decode("Fallas Reportadas:"),1,1,"L");
    $pdf->SetFont('Arial','',$fonttext-2);
    $pdf->SetXY($x, $y+$fonttext-2);
    $pdf->Multicell($tamaniox,($fonttext-5),utf8_decode($datos->indicaciones_cliente),0,"L",false);
    $y+=$tamanioy+2;
    $tamanioy=7;
    $x=10;
    $pdf->Rect($x, $y, $tamaniox, $tamanioy);

    $t=8;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("NO"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t; 
    $t=16;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("CANTIDAD"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=105;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("DESCRIPCION"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=19;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("AUTOR"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=14;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("NUEVA"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=14;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("USADA"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    $x+=$t;
    $t=14;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("PAGADA"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    
    $x=10;
    $y+=$tamanioy;
    $incremento=6;
    $index=1;
    $pdf->SetFont('Arial','B',$incremento);
    for($i=$y;$i<=240;$i+=$incremento){
        $pdf->Rect($x, $i, $tamaniox, $incremento);
        $pdf->Cell(8,$incremento,utf8_decode($index),0,1,"C");
        $pdf->Line(18, $i, 18, $i+$incremento);
        $pdf->Line(34, $i, 34, $i+$incremento);
        $pdf->Line(139, $i, 139, $i+$incremento);
        $pdf->Line(158, $i, 158, $i+$incremento);
        $pdf->Line(172, $i, 172, $i+$incremento);
        $pdf->Line(186, $i, 186, $i+$incremento);
        $index++;
    }

    $y = 265;
    $pdf->SetXY(10,$y);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(60,8,"TECNICO","T","C",false);

    $pdf->SetXY(140,$y);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(60,8,"JEFE DE TALLER","T","C",false); 



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


 ?>