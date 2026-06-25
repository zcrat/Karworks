<?php

include("mc_table_presupuestos.php");
define('FPDF_FONTPATH', 'font/');
use Carbon\Carbon;
$pdf = new PDF_Mc_Table();

$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$mostrar=false;
if ($presupuesto){
     if($presupuesto->detallesGenerales->modulo->factura_emisor_id == 4 ){
        $pdf->Image('img/'.$presupuesto->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor,10,5,20,20);
    }else{
        $mostrar=true;
        $pdf->Image('img/'.$presupuesto->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor,10,5,70,20);
    }
    $pdf->Image('img/logos-empresas/'.$presupuesto->detallesGenerales->Empresa->logo,140,5,50,20);
//$pdf->Image('img/logos-empresas/'.$presupuesto->logo,140,10,-200);


$pdf->SetXY(10, 25);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,6,"FORMATO DE COTIZACION UNICA",0,1,"L",false);
$pdf->SetXY(10, 31);
$pdf->SetTextColor(0,0,0);
date_default_timezone_set('America/Mexico_City');
if($presupuesto->detallesGenerales->modulo_id == 3 || ($presupuesto->detallesGenerales->modulo_id == 5 && $presupuesto->detallesGenerales->anio == 2026) ){
    $pdf->Cell(190,6,"MORELIA, MICH.  ",0,1,"R",false);
}else{
    $pdf->Cell(190,6,"MORELIA, MICH.  ".(Carbon::parse($presupuesto->created_at)->translatedFormat('j \\d\\e F \\d\\e Y')),0,1,"R",false);
}
$pdf->SetFont('Arial','',8);
$pdf->SetXY(10, 37);
$pdf->SetTextColor(0,0,0);

$pdf->MultiCell(130,4,$presupuesto->detallesGenerales->modulo->FacturaEmisor->nombre_emisor.' '.$presupuesto->detallesGenerales->modulo->FacturaEmisor->direccion,1,"",false);
$pdf->SetXY(140, 37);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"FOLIO:",1,"C",false);
$pdf->SetXY(170, 37);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,$presupuesto->Folio,1,"C",false);
$pdf->SetXY(140, 41);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"ID:",1,"C",false);
$pdf->SetXY(170, 41);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,$presupuesto->detallesGenerales->OrdenServicio,1,"C",false);
$pdf->SetXY(140, 45);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(60,4,"ADMINISTRADOR DE TRANSPORTES",1,"C",false);
$pdf->SetXY(140, 49);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(60,4,$presupuesto->detallesGenerales->AdministradorTrasporte->nombre,1,"C",false);
if($presupuesto->detallesGenerales->Orden){
    $pdf->SetXY(140, 53);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(30,4,"Orden",1,"C",false);
    $pdf->SetXY(170, 53);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(30,4,$presupuesto->detallesGenerales->Orden,1,"C",false);
}
$pdf->SetXY(30, 53);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"VEHICULO:",0,"",false);
$pdf->SetXY(60, 53);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(60,4,$presupuesto->detallesGenerales->vehiculo->marca->nombre."/".$presupuesto->detallesGenerales->vehiculo->modelo->nombre,0,"",false);
$pdf->SetXY(30, 57);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"PLACAS:",0,"",false);
$pdf->SetXY(60, 57);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->vehiculo->placas,0,"",false);
$pdf->SetXY(30, 61);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"VIN:",0,"",false);
$pdf->SetXY(60, 61);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->vehiculo->vim,0,"",false);
$pdf->SetXY(30, 65);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"KM ODOMETRO:",0,"",false);
$pdf->SetXY(60, 65);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->Kilometraje_entrada,0,"",false);
$pdf->SetXY(140, 57);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"UBICACION:",0,"",false);
$pdf->SetXY(160, 57);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->Ubicacion,0,"",false);
$pdf->SetXY(140, 61);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"AREA:",0,"",false);
$pdf->SetXY(160, 61);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->modulo->descripcion,0,"",false);
$pdf->SetXY(140, 65);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,4,"ECONOMICO:",0,"",false);
$pdf->SetXY(160, 65);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,4,$presupuesto->detallesGenerales->vehiculo->no_economico,0,"",false);

}

$pdf->SetXY(13, 69);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont("Arial","B",9);
$pdf->Cell(20,6,"CODIGO",1,0,"C",true);
$pdf->Cell(20,6,"CANTIDAD",1,0,"C",true);
$pdf->Cell(95,6,"DESCRIPCION",1,0,"C",true);
$pdf->Cell(25,6,"P.U.",1,0,"C",true);
$pdf->Cell(25,6,"TOTAL",1,1,"C",true);
$total=0;

// Los datos (en negro)
$pdf->SetTextColor(0,0,0);
$pdf->SetFont("Arial","B",8);



$pdf->SetFont("Arial","",7);
	
    
$pdf->SetX(13);
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(95,6,"","LR",0,"J");
$pdf->Cell(25,6,"","LR",0,"C");
$pdf->Cell(25,6,"","LR",1,"C");

	
$pdf->SetTextColor(0,0,0);
$pdf->SetFont("Arial","B",8);

 $pdf->SetX(13);
//un arreglo con alineacion de cada celda
//OTro arreglo pero con el contenido
//utf8_decode es para que escriba bien
//los acentos. 


$pdf->SetFont('Arial','', 7);

//un arreglo con su medida  a lo ancho
$total = 0;
$grupo='';
$total3=0;
foreach ($presupuesto->conceptos as $concepto){
 
    $grupoant = $grupo;
    $grupo = $concepto->DatosConcepto->Categorias_id;
        
    if($grupoant != $grupo){  

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont("Arial","B",6);
        $pdf->SetX(13);
        $pdf->Cell(20,6,$concepto->DatosConcepto->Categoria->num,"LR",0,"C");
        $pdf->Cell(20,6,"","LR",0,"C");
        $pdf->Cell(95,6,utf8_decode($concepto->DatosConcepto->Categoria->nombre),"LR",1,"C");

        
        $pdf->SetFont("Arial","",7);
  
    }
      $total = $total  + ($concepto->Cantidad*$concepto->Venta); 
          
    $pdf->SetX(13);
    $pdf->SetWidths(array(20,20,95,25,25));
    //un arreglo con alineacion de cada celda
    $pdf->SetAligns(array('C','C','L','C','C'));
    //OTro arreglo pero con el contenido
    //utf8_decode es para que escriba bien
    //los acentos. 
    $pdf->Row(array($concepto->DatosConcepto->num,$concepto->Cantidad,$concepto->DatosConcepto->descripcion,amoneda($concepto->Venta),amoneda($concepto->Cantidad*$concepto->Venta)));
//un arreglo con alineacion de cada celda
//OTro arreglo pero con el contenido
//utf8_decode es para que escriba bien
//los acentos. 
}
 
  

$pdf->SetX(13);
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(95,6,"","LR",0,"J");
$pdf->Cell(25,6,"","LR",0,"C");
$pdf->Cell(25,6,"","LR",1,"C");
$pdf->SetFont("Arial","B",8);

$pdf->SetX(13);
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(95,6,utf8_decode("GARANTÍA:"),1,0,"C");
$pdf->Cell(25,6,"","LR",0,"C");
$pdf->Cell(25,6,"","LR",1,"C");
$pdf->SetFont("Arial","",8);
$pdf->SetX(13);
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(95,6,$presupuesto->Garantia,1,0,"C");
$pdf->Cell(25,6,"","LR",0,"C");
$pdf->Cell(25,6,"","LR",1,"C");
$pdf->SetFont("Arial","B",8);

$pdf->SetX(13);
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(20,6,"","LR",0,"C");
$pdf->Cell(95,6,"TIEMPO DE ENTREGA:",1,0,"C");
$pdf->Cell(25,6,"","LR",0,"C");
$pdf->Cell(25,6,"","LR",1,"C");
$pdf->SetFont("Arial","",8);
$pdf->SetX(13);
$pdf->Cell(20,12,"","LR",0,"C");
$pdf->Cell(20,12,"","LR",0,"C");

$posX = $pdf->GetX(); // Guardar posición actual
$posY = $pdf->GetY(); // Guardar posición vertical

$pdf->MultiCell(95,6,($presupuesto->Observaciones??''),"TLR","C");

// Volver a la misma posición X para las siguientes celdas
$pdf->SetXY($posX + 95, $posY);
$pdf->Cell(25,12,"","LR",0,"C");
$pdf->Cell(25,12,"","LR",1,"C");

$pdf->SetX(13);
$pdf->Cell(20,1,"","LRB",0,"C");
$pdf->Cell(20,1,"","LRB",0,"C");
$pdf->Cell(95,1," ","LRB",0,"");
$pdf->Cell(25,1,"","LRB",0,"C");
$pdf->Cell(25,1,"","LRB",1,"C");

$pdf->SetX(13);
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(95,6,"",0,0,"J");
$pdf->Cell(25,6,"",0,0,"C");
$pdf->Cell(25,6,"",0,1,"C");

$pdf->SetX(13);
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(95,6,"",0,0,"J");
$pdf->Cell(25,6,"SUBTOTAL =",0,0,"R");
$pdf->Cell(25,6,amoneda($total),0,1,"R");

$pdf->SetX(13);
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(95,6,"",0,0,"J");
$pdf->Cell(25,6,"IVA =",0,0,"R");
$pdf->Cell(25,6,amoneda($total*0.16),0,1,"R");

$pdf->SetX(13);
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(20,6,"",0,0,"C");
$pdf->Cell(95,6,"",0,0,"J");
$pdf->Cell(25,6,"TOTAL =",0,0,"R");
$pdf->Cell(25,6,amoneda($total*1.16),"B",1,"R");

$y = $pdf->GetY();

if($mostrar){
    $pdf->Image('img/firma_odilon.png',56,$y+2,-300);
}

$pdf->SetFont('Arial','',7);
$pdf->SetXY(40, $y+20);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(60,8,"FIRMA DEL TALLER","T","C",false);

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