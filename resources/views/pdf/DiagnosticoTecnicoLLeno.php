<?php

include("mc_table.php");
define('FPDF_FONTPATH', 'font/');
 use Carbon\Carbon;
$pdf = new PDF_Mc_Table();
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$conceptos=array_values($datos->conceptos->toArray());
$indexconcepto=0;
$page=1;
$totalpages=calcularTotalPaginas($pdf, $conceptos, 105, 260);
do{
    $pdf->AddPage();
    $pdf->Image('img/'.$datos->logo,140,5,70,15);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',16);
    $pdf->SetXY(10,10);
    $pdf->Cell(190,10,"VEHICULO TERMINADO",0,1,"L",false);
    $pdf->SetXY(80,10);
    $pdf->Cell(50,10,$datos->orden_servicio,0,1,"C",false);
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
    $pdf->Multicell($t-10,($tamanioy-$diferencia)/2,utf8_decode($datos->empresa),0,"L",false);
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
    $pdf->Cell($t,$tamanioy-$diferencia,$datos->zona,0,1,"L");
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
    $pdf->Multicell($t-10,$fonttext-5,$vehiculo->marca."\n".$vehiculo->modelo,0,"L",false);
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
    $pdf->Cell($t,$tamanioy-$diferencia,$vehiculo->color,0,1,"L");
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
    $pdf->Cell($t-10,$fonttitlle,utf8_decode($datos->tecnico),0,1,"L");
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
    $t=166;
    $pdf->SetFont('Arial','B',$fonttext-2);
    $pdf->SetXY($x, $y);
    $pdf->Cell($t,$tamanioy,utf8_decode("DESCRIPCION"),0,1,"C");
    $pdf->Line($t+$x, $y, $t+$x, $y+$tamanioy);
    
    
    $x=10;
    $y+=$tamanioy;
    $incremento=6;
    $pdf->SetFont('Arial','B',$incremento);
    for($i=$y;$i<=260;$i+=$incremento){
        $descripcion=$conceptos[$indexconcepto]->descripcion??'' ;
        $lineas = getLineCount($pdf, $descripcion  , 166);

        $x1=$x;
        $pdf->SetXY($x1, $i);
        $t=8;
        $pdf->Cell($t,$incremento*($lineas),utf8_decode($indexconcepto+1),1,1,"C");
        $x1+=$t;
        $t=16;
        $pdf->SetXY($x1, $i);
        $pdf->Cell($t,$incremento*($lineas),$conceptos[$indexconcepto]->cantidad??'',1,1,"C");
        $x1+=$t;
        $pdf->SetXY($x1, $i);
        $pdf->MultiCell(166,$incremento,$descripcion,1,"C");

        unset($conceptos[$indexconcepto]);
        $i+=$incremento*($lineas-1);
        $indexconcepto++;


    }
    $pdf->SetXY(70,280);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(70,8,"Pagina $page de $totalpages",0,"C",false);
    $page++;
}while(count($conceptos) > 0);
    $y = 280;
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
function getLineCount($pdf, $text, $width) {
    // Si el texto está vacío, regresamos 1 como mínimo
    if (trim($text) === '') {
        return 1;
    }

    // Dividir por saltos de línea explícitos
    $paragraphs = explode("\n", $text);
    $lineCount = 0;

    foreach ($paragraphs as $paragraph) {
        $words = explode(' ', $paragraph);
        $currentWidth = 0;
        $lineCount++; // al menos una línea por cada párrafo

        foreach ($words as $word) {
            $wordWidth = $pdf->GetStringWidth($word . ' ');
            if ($currentWidth + $wordWidth > $width) {
                $lineCount++;
                $currentWidth = $wordWidth;
            } else {
                $currentWidth += $wordWidth;
            }
        }
    }
    return max(1, $lineCount);
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
function calcularTotalPaginas($pdf, $conceptos, $y, $limite) {
    $index = 0;
    $paginas = 0;
    $incremento = 6;
    $yInicial = $y;

    while ($index < count($conceptos)) {
        $y = $yInicial;

        while ($y <= $limite && $index < count($conceptos)) {
            $descripcion = $conceptos[$index]->descripcion ?? '';
            $lineas = getLineCount($pdf, $descripcion, 166);
            $alto = $incremento * $lineas;

            if ($y + $alto > $limite) {
                if ($y === $yInicial) {
                    $index++;
                }
                break;
            }

            $y += $alto;
            $index++;
        }

        $paginas++;
    }

    return $paginas;
}

?>
