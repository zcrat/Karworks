<?php 
require ("fpdf.php");

class PDF_MC_Table extends FPDF 
{ 
var $widths; 
var $aligns; 

function SetWidths($w) 
{ 
    //Set the array of column widths 
    $this->widths=$w; 
} 

function SetAligns($a) 
{ 
    //Set the array of column alignments 
    $this->aligns=$a; 
} 

function fill($f)
{
	//juego de arreglos de relleno
	$this->fill=$f;
}

function Row($data, $fill = false)
{
    $nb = 0;

    for ($i = 0; $i < count($data); $i++) {
        $nb = max(
            $nb,
            $this->NbLines($this->widths[$i], $data[$i])
        );
    }

    $lineHeight = 5;
    $h = $lineHeight * $nb;

    $this->CheckPageBreak($h);

    for ($i = 0; $i < count($data); $i++) {
        $w = $this->widths[$i];
        $alignment = $this->aligns[$i] ?? 'L';

        $x = $this->GetX();
        $y = $this->GetY();

        // DF = dibuja el borde y aplica fondo
        // D  = solamente dibuja el borde
        $style = $fill ? 'DF' : 'D';

        $this->Rect($x, $y, $w, $h, $style);

        $this->MultiCell(
            $w,
            $lineHeight,
            $data[$i],
            0,
            $alignment
        );

        $this->SetXY($x + $w, $y);
    }

    $this->Ln($h);
}

function CheckPageBreak($h) 
{ 
    //If the height h would cause an overflow, add a new page immediately 
    if($this->GetY()+$h>$this->PageBreakTrigger) 
        $this->AddPage($this->CurOrientation); 
} 

function NbLines($w,$txt) 
{ 
    //Computes the number of lines a MultiCell of width w will take 
    $cw=&$this->CurrentFont['cw']; 
    if($w==0) 
        $w=$this->w-$this->rMargin-$this->x; 
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize; 
    $s=str_replace("\r",'',$txt); 
    $nb=strlen($s); 
    if($nb>0 and $s[$nb-1]=="\n") 
        $nb--; 
    $sep=-1; 
    $i=0; 
    $j=0; 
    $l=0; 
    $nl=1; 
    while($i<$nb) 
    { 
        $c=$s[$i]; 
        if($c=="\n") 
        { 
            $i++; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
            continue; 
        } 
        if($c==' ') 
            $sep=$i; 
        $l+=$cw[$c]; 
        if($l>$wmax) 
        { 
            if($sep==-1) 
            { 
                if($i==$j) 
                    $i++; 
            } 
            else 
                $i=$sep+1; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
        } 
        else 
            $i++; 
    } 
    return $nl; 
}
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 4/3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k ));

    $xc = $x+$w-$r; $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k ));
    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

    $xc = $x+$w-$r; $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k ));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

    $xc = $x+$r; $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k ));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

    $xc = $x+$r; $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}
function Polygon($points, $style='D')
{
    $k = $this->k;
    $h = $this->h;

    if(!empty($points))
    {
        $this->_out(sprintf('%.2F %.2F m', $points[0]*$k, ($h - $points[1])*$k));
        for($i = 2; $i < count($points); $i += 2)
            $this->_out(sprintf('%.2F %.2F l', $points[$i]*$k, ($h - $points[$i+1])*$k));
        $this->_out('h'); // Cierra el camino
        if($style=='F')
            $op = 'f';
        elseif($style=='FD' || $style=='DF')
            $op = 'B';
        else
            $op = 'S';
        $this->_out($op);
    }
}
function Circle($x, $y, $r, $style='D')
{
    $k = $this->k;
    $h = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 4/3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($h-$y)*$k ));
    $this->_Arc($x+$r, $y-$r*$MyArc, $x+$r*$MyArc, $y-$r, $x, $y-$r);
    $this->_Arc($x-$r*$MyArc, $y-$r, $x-$r, $y-$r*$MyArc, $x-$r, $y);
    $this->_Arc($x-$r, $y+$r*$MyArc, $x-$r*$MyArc, $y+$r, $x, $y+$r);
    $this->_Arc($x+$r*$MyArc, $y+$r, $x+$r, $y+$r*$MyArc, $x+$r, $y);
    $this->_out($op);
}
function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ',
        $x1*$this->k,
        ($h-$y1)*$this->k,
        $x2*$this->k,
        ($h-$y2)*$this->k,
        $x3*$this->k,
        ($h-$y3)*$this->k));
}


} 
?>      
